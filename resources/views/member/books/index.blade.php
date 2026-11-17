@extends('layouts.app')

@section('title', 'Jelajahi Buku')

@section('content')
    <h1>Katalog Buku</h1>
    <p>Pilih buku untuk melihat detail, rating, dan ulasan.</p>

    <div class="stats-grid">
        @forelse ($books as $book)
            <article class="stat-card book-card">
                <h2><a href="{{ route('member.books.show', $book->id) }}">{{ $book->judul }}</a></h2>
                <p>Kode buku: <strong>{{ $book->kode_buku ?? '-' }}</strong></p>
                <p>{{ $book->penulis }} · {{ $book->tahun_terbit }}</p>
                <p>{{ $book->category->nama_kategori ?? 'Tanpa kategori' }}</p>
                <p>Status: <strong>{{ $book->sedang_dipinjam ? 'Sedang dipinjam' : 'Tersedia' }}</strong></p>
                <p>{{ $book->reviews_count ? '★ '.number_format($book->reviews_avg_rating, 1).' / 5' : 'Belum ada rating' }}</p>
                <a class="btn" href="{{ route('member.books.show', $book->id) }}">Lihat detail</a>
                @if ($book->sedang_dipinjam)
                    @if (in_array($book->id, $waitingBookIds))
                        <p><strong>Sudah masuk waiting list Anda.</strong></p>
                    @else
                        <form method="POST" action="{{ route('member.waiting-list.store', $book) }}" class="form-spaced">
                            @csrf
                            <button class="btn" type="submit">Masuk Waiting List</button>
                        </form>
                    @endif
                @endif
            </article>
        @empty
            <p>Belum ada buku di katalog.</p>
        @endforelse
    </div>

    {{ $books->links() }}
@endsection
