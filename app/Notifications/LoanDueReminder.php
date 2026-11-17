<?php

namespace App\Notifications;

use App\Models\Loan;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class LoanDueReminder extends Notification
{
    use Queueable;

    public function __construct(private readonly Loan $loan)
    {
    }

    public function via(object $notifiable): array
    {
        return ['database'];
    }

    public function toArray(object $notifiable): array
    {
        return [
            'kind' => 'loan_due',
            'loan_id' => $this->loan->id,
            'due_date' => $this->loan->tanggal_kembali,
            'book_titles' => $this->loan->loanItems->map(fn ($item) => $item->book?->judul)->filter()->values()->all(),
            'message' => 'Pengingat: buku pinjaman Anda harus dikembalikan pada '.$this->loan->tanggal_kembali.'.',
        ];
    }
}
