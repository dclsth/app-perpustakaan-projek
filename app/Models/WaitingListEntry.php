<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class WaitingListEntry extends Model
{
    protected $table = 'waiting_lists';

    protected $fillable = ['member_id', 'book_id', 'status', 'notified_at'];

    protected function casts(): array
    {
        return ['notified_at' => 'datetime'];
    }

    public function member(): BelongsTo
    {
        return $this->belongsTo(Member::class);
    }

    public function book(): BelongsTo
    {
        return $this->belongsTo(Book::class);
    }
}
