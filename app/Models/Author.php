<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Author extends Model
{
    use HasFactory;

    protected $fillable = [
        'name' ,
        'gender',
        'position',
        'nidn',
        'employment_status',
        'front_title',
        'back_title',
        'code',
        'work_location',
    ];

    public function journals(): BelongsToMany
    {
    return $this->belongsToMany(
        Journal::class,
            'authors_journals',
            'id_authors',
            'id_journals');
    }
}
