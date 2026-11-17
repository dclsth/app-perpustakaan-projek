<?php

namespace App\Notifications;

use App\Models\Book;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class BookAvailableNotification extends Notification
{
    use Queueable;

    public function __construct(private readonly Book $book)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'book_available',
            'book_id' => $this->book->id,
            'kode_buku' => $this->book->kode_buku,
            'judul' => $this->book->judul,
            'message' => 'Buku '.$this->book->judul.' ('.$this->book->kode_buku.') sudah dikembalikan dan tersedia.',
        ];
    }
}
