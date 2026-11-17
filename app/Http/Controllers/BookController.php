<?php

namespace App\Http\Controllers;

use App\Models\Book;
use App\Models\Category;
use App\Models\Member;
use App\Http\Requests\StoreBookRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;


class BookController extends Controller
{


    public function index()
    {
        $books = Book::with('category')->withLoanStatus()->paginate(10);

        return view('books.index', compact('books'));
    }

    public function create()
    {
        $categories = Category::all();

        return view('books.create', compact('categories'));
    }

    public function store(StoreBookRequest $request)
    {
        $validated = $request->validated();
        $book = DB::transaction(function () use ($validated) {
            Category::query()->orderBy('id')->lockForUpdate()->get();
            $validated['kode_buku'] = $this->generateBookCode($validated['category_id']);

            return Book::create($validated);
        });

        return redirect()->route('books.index')
            ->with('success', "Buku \"{$validated['judul']}\" berhasil ditambahkan.");
    }

    public function show(string $id)
    {
        $book = Book::with(['category', 'reviews.user'])->withAvg('reviews', 'rating')->withCount('reviews')->withLoanStatus()->findOrFail($id);
        $myReview = auth()->check()
            ? $book->reviews->firstWhere('user_id', auth()->id())
            : null;
        $member = auth()->check() && auth()->user()->role === 'member'
            ? (auth()->user()->member ?? Member::where('email', auth()->user()->email)->first())
            : null;
        $canReview = $member && $member->loans()
            ->where('status', 'dikembalikan')
            ->whereHas('loanItems', fn ($items) => $items->where('book_id', $book->id))
            ->exists();

        return view('books.show', compact('book', 'myReview', 'canReview'));
    }

    public function storeReview(Request $request, string $book)
    {
        $book = Book::findOrFail($book);
        abort_unless($request->user()->role === 'member', 403, 'Hanya member yang dapat memberikan ulasan.');

        $member = $request->user()->member
            ?? Member::where('email', $request->user()->email)->first();
        $hasReturnedBook = $member && $member->loans()
            ->where('status', 'dikembalikan')
            ->whereHas('loanItems', fn ($items) => $items->where('book_id', $book->id))
            ->exists();

        abort_unless($hasReturnedBook, 403, 'Ulasan hanya dapat diberikan setelah buku selesai dipinjam dan dikembalikan.');

        $validated = $request->validate([
            'rating' => ['required', 'integer', 'between:1,5'],
            'ulasan' => ['nullable', 'string', 'max:2000'],
        ]);

        $book->reviews()->updateOrCreate(
            ['user_id' => $request->user()->id],
            $validated
        );

        return redirect()->route('member.books.show', $book)->with('success', 'Ulasan dan rating berhasil disimpan.');
    }

    public function edit(string $id)
    {
        $book = Book::findOrFail($id);
        $categories = Category::all();

        return view('books.edit', compact('book', 'categories'));
    }


    public function update(Request $request, string $id)
    {
        $book = Book::findOrFail($id);

        $validated = $request->validate([
            'judul' => 'required|string|max:200',
            'penulis' => 'required|string|max:100',
            'penerbit' => 'required|string|max:100',
            'tahun_terbit' => 'required|integer|min:1900|max:'.date('Y'),
            'isbn' => 'nullable|string|max:20',
            'stok' => 'required|integer|min:0',
            'category_id' => 'required|integer|exists:categories,id',
        ]);

        DB::transaction(function () use ($book, $validated) {
            if ((int) $book->category_id !== (int) $validated['category_id']) {
                Category::query()->orderBy('id')->lockForUpdate()->get();
                $validated['kode_buku'] = $this->generateBookCode($validated['category_id']);
            }

            $book->update($validated);
        });

        return redirect()->route('books.index')
            ->with('success', "Buku \"{$validated['judul']}\" berhasil diperbarui.");
    }

    public function destroy(string $id)
    {
        $book = Book::findOrFail($id);
        $book->delete();

        return redirect()->route('books.index')
            ->with('success', 'Buku berhasil dihapus.');
    }

    private function generateBookCode(int $categoryId): string
    {
        $category = Category::findOrFail($categoryId);
        $prefix = strtoupper(substr(trim($category->nama_kategori), 0, 1));
        $highestNumber = Book::where('kode_buku', 'like', $prefix.'%')->pluck('kode_buku')
            ->map(function ($code) use ($prefix) {
                return preg_match('/^'.preg_quote($prefix, '/').'([0-9]+)$/', $code, $matches)
                    ? (int) $matches[1]
                    : 0;
            })
            ->max() ?? 0;

        return $prefix.str_pad((string) ($highestNumber + 1), 3, '0', STR_PAD_LEFT);
    }
}
