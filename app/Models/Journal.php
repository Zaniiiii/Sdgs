<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Journal extends Model
{
    use HasFactory;
    
    protected $fillable = [
        'title','abstract','lecturer'
    ];

    public function sdgs(): BelongsToMany
    {
    return $this->belongsToMany(
            Sdg::class,
            'journals_sdgs',
            'id_journals',
            'id_sdgs');
    }

    public function authors(): BelongsToMany
    {
    return $this->belongsToMany(
        Author::class,
            'authors_journals',
            'id_journals',
            'id_authors'
        );
    }
}
