<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JournalSdg extends Model
{
    use HasFactory;

    protected $table = 'journals_sdgs';

    protected $fillable = [
        'id_journals',
        'id_sdgs',
    ];
}
