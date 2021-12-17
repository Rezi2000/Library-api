<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookAuthor extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $table = 'author_book';

    protected $fillable = [
        'author_id',
        'book_id'
    ];
}
