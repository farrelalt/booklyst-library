<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Member extends Model
{
    protected $table    = 'members'; // arahkan ke tabel members, bukan users
    protected $fillable = ['name', 'email', 'phone'];
}