<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Loan;
use App\Models\Member;
use App\Models\User;
use App\Models\WaitingListEntry;
use App\Notifications\BookAvailableNotification;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;
use Illuminate\Validation\ValidationException;


class LoanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $loans = Loan::with(['member', 'user', 'loanItems.book'])->paginate(10);

        return view('loans.index', compact('loans'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $members = Member::all();
        $books = Book::withLoanStatus()->orderBy('kode_buku')->get();

        return view('loans.create', compact('members', 'books'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $bookCodes = collect(preg_split('/[\\r\\n,;]+/', strtoupper(trim((string) $request->input('kode_buku')))))
            ->map(fn ($code) => trim($code))
            ->filter()
            ->values();

        $validated = $request->validate([
            'nim' => ['required', 'string', 'exists:members,nim'],
            'kode_buku' => ['required', 'string'],
        ]);

        if ($bookCodes->isEmpty()) {
            return back()->withErrors(['kode_buku' => 'Masukkan minimal satu kode buku.'])->withInput();
        }

        if ($bookCodes->unique()->count() !== $bookCodes->count()) {
            return back()->withErrors(['kode_buku' => 'Kode buku yang sama tidak boleh dimasukkan lebih dari sekali.'])->withInput();
        }

        $member = Member::where('nim', $validated['nim'])->firstOrFail();
        if ($member->status !== 'aktif') {
            return back()->withErrors(['nim' => 'Status anggota tidak aktif sehingga tidak dapat meminjam buku.'])->withInput();
        }

        DB::transaction(function () use ($member, $bookCodes) {
            $books = Book::whereIn('kode_buku', $bookCodes)->lockForUpdate()->get()->keyBy('kode_buku');
            $missingCodes = $bookCodes->diff($books->keys());
            if ($missingCodes->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'kode_buku' => 'Kode buku tidak ditemukan: '.$missingCodes->implode(', '),
                ]);
            }

            $unavailableCodes = $books->filter(fn ($book) => $book->loanItems()
                ->whereHas('loan', fn ($loanQuery) => $loanQuery->whereIn('status', ['dipinjam', 'terlambat']))
                ->exists())->pluck('kode_buku');

            if ($unavailableCodes->isNotEmpty()) {
                throw ValidationException::withMessages([
                    'kode_buku' => 'Buku sedang dipinjam dan belum dikembalikan: '.$unavailableCodes->implode(', '),
                ]);
            }

            $tanggalPinjam = today('Asia/Jakarta');
            $loan = Loan::create([
                'member_id' => $member->id,
                'user_id' => Auth::id(),
                'tanggal_pinjam' => $tanggalPinjam->toDateString(),
                'tanggal_kembali' => $tanggalPinjam->copy()->addWeek()->toDateString(),
            ]);

            foreach ($books as $book) {
                $loan->loanItems()->create(['book_id' => $book->id]);
            }
        });

        return redirect()->route('loans.index')
            ->with('success', 'Transaksi peminjaman berhasil dibuat.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $loan = Loan::with(['member', 'user', 'loanItems.book'])->findOrFail($id);

        return view('loans.show', compact('loan'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $loan = Loan::with(['member', 'user', 'loanItems.book'])->findOrFail($id);

        return view('loans.edit', compact('loan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $loan = Loan::findOrFail($id);

        $validated = $request->validate([
            'tanggal_kembali' => 'required|date|after_or_equal:tanggal_pinjam',
            'status' => 'required|in:dipinjam,dikembalikan,terlambat',
        ], [
            'tanggal_kembali.required' => 'Tanggal kembali wajib diisi.',
            'tanggal_kembali.after_or_equal' => 'Tanggal kembali tidak boleh sebelum tanggal pinjam.',
        ]);

        $wasReturned = $loan->status === 'dikembalikan';
        if (! $wasReturned && $validated['status'] === 'dikembalikan') {
            $validated['tanggal_dikembalikan'] = today('Asia/Jakarta')->toDateString();
        }

        $loan->update($validated);
        if (! $wasReturned && $loan->status === 'dikembalikan') {
            $this->notifyWaitingMembers($loan);
        }

        return redirect()->route('loans.index')
            ->with('success', 'Transaksi peminjaman berhasil diperbarui.');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(string $id)
    {
        $loan = Loan::findOrFail($id);
        $loan->loanItems()->delete();
        $loan->delete();

        return redirect()->route('loans.index')
            ->with('success', 'Transaksi peminjaman berhasil dihapus.');
    }

    public function kembalikan(string $id)
    {
        $loan = Loan::findOrFail($id);
        $wasReturned = $loan->status === 'dikembalikan';

        $loan->update([
            'status' => 'dikembalikan',
            'tanggal_dikembalikan' => today('Asia/Jakarta')->toDateString(),
        ]);

        if (! $wasReturned) {
            $this->notifyWaitingMembers($loan);
        }

        return redirect()->route('loans.index')
            ->with('success', 'Buku berhasil dikembalikan.');
    }

    private function notifyWaitingMembers(Loan $loan): void
    {
        $loan->loadMissing('loanItems.book');

        foreach ($loan->loanItems as $item) {
            $book = $item->book;
            if (! $book) {
                continue;
            }

            $stillBorrowed = $book->loanItems()
                ->where('loan_id', '!=', $loan->id)
                ->whereHas('loan', fn ($query) => $query->whereIn('status', ['dipinjam', 'terlambat']))
                ->exists();

            if ($stillBorrowed) {
                continue;
            }

            $entries = WaitingListEntry::with(['member.user'])
                ->where('book_id', $book->id)
                ->where('status', 'menunggu')
                ->get();

            foreach ($entries as $entry) {
                $member = $entry->member;
                $user = $member?->user ?? ($member
                    ? User::where('email', $member->email)->where('role', 'member')->first()
                    : null);

                if ($user) {
                    $user->notify(new BookAvailableNotification($book));
                }

                $entry->update([
                    'status' => 'diberitahu',
                    'notified_at' => now('Asia/Jakarta'),
                ]);
            }
        }
    }

    public function report(Request $request)
    {
        $body = ['data' => [], 'meta' => null];

        try {
            $response = Http::get(config('services.internal_api.base_url').'/api/loans', [
                'page' => $request->query('page', 1),
            ]);

            if ($response->successful()) {
                $body = $response->json();
            }
        } catch (ConnectionException $e) {
            // Server internal API (port 8011) tidak bisa dihubungi - tabel laporan tampil kosong.
        }

        return view('loans.report', [
            'loans' => $body['data'] ?? [],
            'meta' => $body['meta'] ?? null,
        ]);
    }
}
