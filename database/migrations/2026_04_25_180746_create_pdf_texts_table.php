<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('pdf_texts', function (Blueprint $table) {
            $table->id();

            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            $table->string('file_path');   // uploaded PDF
            $table->longText('extracted_text')->nullable(); // OCR text

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('pdf_texts');
    }
};
