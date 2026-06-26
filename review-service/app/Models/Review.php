<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{
   protected $fillable = [
    'member_id',
    'book_id',
    'rating',
    'comment',
];

    protected $casts = [
        'rating' => 'integer',
    ];
}