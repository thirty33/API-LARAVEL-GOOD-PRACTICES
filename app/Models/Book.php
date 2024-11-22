<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Book extends Model
{
    protected $fillable = [
        'author_id',
        'genre_id',
        'title',
        'isbn',
        'pages',
        'stock',
        'published_at',
    ];

    protected function casts(): array
    {
        return [
            'published_at' => 'date:Y-m-d',
            'stock' => 'integer',
            'pages' => 'integer',
        ];
    }

    public function author(): BelongsTo
    {
        return $this->belongsTo(Author::class);
    }

    public function genre(): BelongsTo
    {
        return $this->belongsTo(Genre::class);
    }

    public function loans(): HasMany
    {
        return $this->hasMany(Loan::class);
    }
}
