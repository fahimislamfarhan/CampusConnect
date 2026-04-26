<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\PdfText;
use Illuminate\Support\Facades\Http;

class PdfTextController extends Controller
{
    public function create()
    {
        return view('pdf_texts.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'pdf_file' => 'required|file|mimes:pdf,jpg,jpeg,png|max:10240',
        ]);

        $file = $request->file('pdf_file');
        $path = $file->store('pdf_texts', 'public');

        $extractedText = 'OCR failed or no text detected. You can manually edit this text.';

        try {
            $response = Http::timeout(20)
                ->attach(
                    'file',
                    file_get_contents($file->getRealPath()),
                    $file->getClientOriginalName()
                )
                ->post('https://api.ocr.space/parse/image', [
                    'apikey' => env('OCR_SPACE_API_KEY', 'helloworld'),
                    'language' => 'eng',
                    'isOverlayRequired' => 'false',
                    'OCREngine' => '2',
                    'filetype' => strtoupper($file->getClientOriginalExtension()),
                ]);

            $data = $response->json();

            if (isset($data['ParsedResults'][0]['ParsedText']) && trim($data['ParsedResults'][0]['ParsedText']) !== '') {
                $extractedText = $data['ParsedResults'][0]['ParsedText'];
            } elseif (isset($data['ErrorMessage'])) {
                $extractedText = 'OCR Error: ' . json_encode($data['ErrorMessage']);
            }
        } catch (\Exception $e) {
            $extractedText = 'OCR service unavailable or timed out. You can manually edit this text.';
        }

        $pdfText = PdfText::create([
            'user_id' => auth()->id(),
            'file_path' => $path,
            'extracted_text' => $extractedText,
        ]);

        return redirect()->route('pdf-texts.edit', $pdfText->id);
    }

    public function edit($id)
    {
        $pdfText = PdfText::findOrFail($id);

        if ($pdfText->user_id != auth()->id()) {
            abort(403);
        }

        return view('pdf_texts.edit', compact('pdfText'));
    }

    public function update(Request $request, $id)
    {
        $pdfText = PdfText::findOrFail($id);

        if ($pdfText->user_id != auth()->id()) {
            abort(403);
        }

        $request->validate([
            'extracted_text' => 'required|string',
        ]);

        $pdfText->update([
            'extracted_text' => $request->extracted_text,
        ]);

        return back()->with('success', 'Text updated successfully!');
    }

    public function download($id)
    {
        $pdfText = PdfText::findOrFail($id);

        if ($pdfText->user_id != auth()->id()) {
            abort(403);
        }

        $fileName = 'converted_text_' . $pdfText->id . '.txt';

        return response($pdfText->extracted_text)
            ->header('Content-Type', 'text/plain')
            ->header('Content-Disposition', 'attachment; filename="' . $fileName . '"');
    }
}
