<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Member;
use App\Models\WaitingListEntry;
use Illuminate\Http\Request;

class MemberPortalController extends Controller
{
    public function dashboard()
    {
        $member = auth()->user()->member ?? Member::where('email', auth()->user()->email)->first();

        return view('member.dashboard', [
            'totalBuku' => Book::count(),
            'bukuTerbaru' => Book::latest()->take(5)->get(),
            'pinjamanAktif' => $member
                ? $member->loans()->whereIn('status', ['dipinjam', 'terlambat'])->count()
                : 0,
        ]);
    }

    public function books()
    {
        $member = auth()->user()->member ?? Member::where('email', auth()->user()->email)->first();
        $waitingBookIds = $member?->waitingListEntries()->pluck('book_id')->all() ?? [];
        $books = Book::with('category')->withAvg('reviews', 'rating')->withCount('reviews')->withLoanStatus()
            ->orderBy('judul')->paginate(12);

        return view('member.books.index', compact('books', 'waitingBookIds'));
    }

    public function profile(Request $request)
    {
        $member = $request->user()->member ?? Member::where('email', $request->user()->email)->first();

        return view('member.profile', compact('member'));
    }

    public function loans(Request $request)
    {
        $member = $request->user()->member ?? Member::where('email', $request->user()->email)->first();
        $loans = $member
            ? $member->loans()->with('loanItems.book')->orderByDesc('tanggal_pinjam')->get()
            : collect();

        return view('member.loans.index', [
            'memberTerhubung' => (bool) $member,
            'pinjamanAktif' => $loans->whereIn('status', ['dipinjam', 'terlambat']),
            'riwayatPinjaman' => $loans->where('status', 'dikembalikan'),
        ]);
    }

    public function waitingList(Request $request)
    {
        $member = $request->user()->member ?? Member::where('email', $request->user()->email)->first();
        $entries = $member
            ? $member->waitingListEntries()->with('book')->latest()->get()
            : collect();

        return view('member.waiting-list.index', [
            'entries' => $entries,
            'memberTerhubung' => (bool) $member,
        ]);
    }

    public function joinWaitingList(Request $request, Book $book)
    {
        $member = $request->user()->member ?? Member::where('email', $request->user()->email)->first();
        abort_unless($member, 403, 'Akun ini belum terhubung dengan data anggota.');

        $isBorrowed = $book->loanItems()->whereHas('loan', fn ($query) => $query
            ->whereIn('status', ['dipinjam', 'terlambat']))->exists();

        if (! $isBorrowed) {
            return back()->with('error', 'Buku sudah tersedia dan tidak perlu masuk waiting list.');
        }

        $entry = WaitingListEntry::firstOrCreate(
            ['member_id' => $member->id, 'book_id' => $book->id],
            ['status' => 'menunggu']
        );

        return back()->with('success', $entry->wasRecentlyCreated
            ? 'Buku berhasil ditambahkan ke waiting list.'
            : 'Buku tersebut sudah ada di waiting list Anda.');
    }

    public function leaveWaitingList(Request $request, int $entry)
    {
        $member = $request->user()->member ?? Member::where('email', $request->user()->email)->first();
        abort_unless($member, 403, 'Akun ini belum terhubung dengan data anggota.');

        $member->waitingListEntries()->whereKey($entry)->firstOrFail()->delete();

        return back()->with('success', 'Buku dihapus dari waiting list.');
    }
}
