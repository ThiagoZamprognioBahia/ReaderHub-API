<?php

namespace App\Console\Commands;

use App\Mail\EmailBirthday;
use App\Models\BookReader;
use App\Models\Reader;
use Carbon\Carbon;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Redis;

class SendEmailBirthday extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:send-email-birthday';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Send congratulatory emails to readers whose birthday is today';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // Search for readers whose birthday is today
        $readersBirthday = Reader::whereMonth('birthday', '=', Carbon::now()->month)
            ->whereDay('birthday', '=', Carbon::now()->day)
            ->get();

        if ($readersBirthday->isEmpty()) {
            return;
        }

        // Send congratulations emails to each reader
        foreach ($readersBirthday as $reader) {
            // Retrieves relevant information from the reader
            $name = $reader->name;
            $quantityBooksReadYear = $this->getQuantityBooksReadYear($reader->id);
            $quantityPagesReadTotal = $this->getQuantityPagesReadTotal($reader->id);
            
            // Send congratulations email
            Mail::to($reader->email)->send(new EmailBirthday($name, $quantityBooksReadYear, $quantityPagesReadTotal));
        }

        $this->info('E-mails de aniversário enviados com sucesso!');
    }

    private function getQuantityBooksReadYear($readerId)
    {
        $currentyear = Carbon::now()->year;

        // Counts the number of records in the BookReader table for the reader ($readerId) in the current year
        $quantityBooksRead = BookReader::where('reader_id', $readerId)
            ->whereYear('created_at', $currentyear)
            ->count();

        return $quantityBooksRead;
    }

    private function getQuantityPagesReadTotal($readerId)
    {
        return Redis::get('total_pages_read:' . $readerId) ?? 0;
    }
}
