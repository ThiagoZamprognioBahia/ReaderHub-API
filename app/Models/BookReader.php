<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookReader extends Model
{
    protected $table = 'books_readers';

    protected $fillable = [
        'reader_id',
        'book_id',
    ];

    public function reader()
    {
        return $this->belongsTo(Reader::class, 'reader_id');
    }

    public function book()
    {
        return $this->belongsTo(Book::class, 'book_id');
    }
}