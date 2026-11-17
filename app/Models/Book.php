<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;

class Book extends Model
{
    use HasFactory;
    protected $fillable = [
        'judul', 'penulis', 'penerbit', 'tahun_terbit',
        'kode_buku', 'isbn', 'stok', 'category_id', 'sampul',
    ];

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function loanItems(): HasMany
    {
        return $this->hasMany(LoanItem::class);
    }

    public function reviews(): HasMany
    {
        return $this->hasMany(Review::class);
    }

    public function waitingListEntries(): HasMany
    {
        return $this->hasMany(WaitingListEntry::class);
    }

    public function scopeWithLoanStatus(Builder $query): Builder
    {
        return $query->withExists([
            'loanItems as sedang_dipinjam' => fn ($items) => $items->whereHas(
                'loan',
                fn ($loans) => $loans->whereIn('status', ['dipinjam', 'terlambat'])
            ),
        ]);
    }
}
