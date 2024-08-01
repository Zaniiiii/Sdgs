<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AuthorJournal extends Model
{
    use HasFactory;

    protected $table = 'authors_journals';

    protected $fillable = [
        'id_authors',
        'id_journals',
    ];
}
