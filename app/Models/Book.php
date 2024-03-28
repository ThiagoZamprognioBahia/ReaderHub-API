<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Book extends Model
{
    use HasFactory, SoftDeletes;
    
    protected $fillable = [
        'name',
        'genre_id',
        'author',
        'year',
        'pages',
        'language',
        'edition',
        'publisher_id',
        'isbn',
    ];

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function publisher()
    {
        return $this->belongsTo(Publisher::class);
    }
}
