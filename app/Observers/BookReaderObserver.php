<?php

namespace App\Observers;

use App\Models\BookReader;
use Illuminate\Support\Facades\Redis;

class BookReaderObserver
{
    /**
     * Handle the BookReader "created" event.
     */
    public function created(BookReader $bookReader)
    {
        $reader = $bookReader->reader;

        // Increment the total books read
        $reader->increment('total_books_read');

        // Increment the total pages read by the number of pages in the book
        $reader->increment('total_pages_read', $bookReader->book->pages);

        Redis::set('total_books_read:' . $reader->id, $reader->total_books_read);
        Redis::set('total_pages_read:' . $reader->id, $reader->total_pages_read);
    }

    /**
     * Handle the BookReader "updated" event.
     */
    public function updated(BookReader $bookReader): void
    {
        //
    }

    /**
     * Handle the BookReader "deleted" event.
     */
    public function deleted(BookReader $bookReader): void
    {
        $reader = $bookReader->reader;

        // Increment the total books read
        $reader->decrement('total_books_read');

        // Increment the total pages read by the number of pages in the book
        $reader->decrement('total_pages_read', $bookReader->book->pages);

        Redis::set('total_books_read:' . $reader->id, $reader->total_books_read);
        Redis::set('total_pages_read:' . $reader->id, $reader->total_pages_read);
        
    }

    /**
     * Handle the BookReader "restored" event.
     */
    public function restored(BookReader $bookReader): void
    {
        //
    }

    /**
     * Handle the BookReader "force deleted" event.
     */
    public function forceDeleted(BookReader $bookReader): void
    {
        //
    }
}
