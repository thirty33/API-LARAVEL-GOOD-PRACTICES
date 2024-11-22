<?php

namespace App\Models;

use App\Models\Scopes\UserScope;
use App\Traits\HasUserId;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Loan extends Model
{
    use HasUserId;

    protected $fillable = [
        'user_id',
        'book_id',
        'loaned_at',
        'returned_at',
        'due_date',
        'returned',
    ];

    protected function casts(): array
    {
        return [
            'loaned_at' => 'datetime',
            'returned_at' => 'datetime',
            'due_date' => 'datetime',
            'returned' => 'boolean',
        ];
    }

    protected static function boot(): void
    {
        parent::boot();

        static::addGlobalScope(new UserScope());
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }

    public function isOwner(): bool
    {
        return $this->user_id === auth()->id();
    }
}
