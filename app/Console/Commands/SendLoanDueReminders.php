<?php

namespace App\Console\Commands;

use App\Models\Loan;
use App\Models\User;
use App\Notifications\LoanDueReminder;
use Illuminate\Console\Command;

class SendLoanDueReminders extends Command
{
    protected $signature = 'loans:send-due-reminders';

    protected $description = 'Kirim pengingat pengembalian buku satu hari sebelum jatuh tempo.';

    public function handle(): int
    {
        $today = today('Asia/Jakarta');
        $dueDate = $today->copy()->addDay()->toDateString();
        $sent = 0;

        $loans = Loan::with(['member.user', 'loanItems.book'])
            ->whereIn('status', ['dipinjam', 'terlambat'])
            ->whereDate('tanggal_kembali', $dueDate)
            ->get();

        foreach ($loans as $loan) {
            $member = $loan->member;
            $user = $member?->user ?? ($member
                ? User::where('email', $member->email)->where('role', 'member')->first()
                : null);

            if (! $user || $user->role !== 'member') {
                continue;
            }

            $alreadySent = $user->notifications()
                ->where('type', LoanDueReminder::class)
                ->where('data->loan_id', $loan->id)
                ->whereDate('created_at', $today->toDateString())
                ->exists();

            if (! $alreadySent) {
                $user->notify(new LoanDueReminder($loan));
                $sent++;
            }
        }

        $this->info("Pengingat terkirim: {$sent}.");

        return self::SUCCESS;
    }
}
