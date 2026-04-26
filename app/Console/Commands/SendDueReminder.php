<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\BorrowRequest;
use Illuminate\Support\Facades\Http;
use Carbon\Carbon;

class SendDueReminder extends Command
{
    protected $signature = 'app:send-due-reminder';

    protected $description = 'Send reminder notifications 24 hours before borrowed item return date';

    public function handle()
    {
        $tomorrow = Carbon::tomorrow()->toDateString();

        $requests = BorrowRequest::with(['user', 'resource'])
            ->where('status', 'approved')
            ->whereDate('return_date', $tomorrow)
            ->whereNull('reminder_sent_at')
            ->get();

        foreach ($requests as $request) {
            $message = 'CampusConnect Reminder: "' . $request->resource->title .
                '" is due tomorrow (' . $request->return_date . '). Please return it on time.';

            if (env('SMS_ENABLED') == 'true' && env('SMS_API_URL') && env('SMS_API_KEY') && $request->user->phone) {
                Http::post(env('SMS_API_URL'), [
                    'api_key' => env('SMS_API_KEY'),
                    'msg' => $message,
                    'to' => $request->user->phone,
                ]);
            }

            $request->update([
                'reminder_sent_at' => now()
            ]);

            $this->info('Reminder sent for request ID: ' . $request->id);
        }

        if ($requests->count() == 0) {
            $this->info('No due reminders found.');
        }

        return 0;
    }
}