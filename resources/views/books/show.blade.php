@extends('layouts.app')

@section('title', 'Detail Buku')

@section('content')
    <div class="detail-panel book-detail">
        <h1>Detail Buku</h1>
        <p><a href="{{ auth()->user()->role === 'member' ? route('member.books.index') : route('books.index') }}">&larr; Kembali ke daftar buku</a></p>

        <div class="table-wrap"><table>
            <tr><th>Judul</th><td>{{ $book->judul }}</td></tr>
            <tr><th>Kode Buku</th><td>{{ $book->kode_buku ?? '-' }}</td></tr>
            <tr><th>Penulis</th><td>{{ $book->penulis }}</td></tr>
            <tr><th>Penerbit</th><td>{{ $book->penerbit }}</td></tr>
            <tr><th>Tahun Terbit</th><td>{{ $book->tahun_terbit }}</td></tr>
            <tr><th>ISBN</th><td>{{ $book->isbn ?? '-' }}</td></tr>
            <tr><th>Stok</th><td>{{ $book->stok }}</td></tr>
            <tr><th>Status Peminjaman</th><td>{{ $book->sedang_dipinjam ? 'Sedang dipinjam' : 'Tersedia' }}</td></tr>
            <tr><th>Kategori</th><td>{{ $book->category->nama_kategori ?? '-' }}</td></tr>
        </table></div>

        <section class="review-section">
            <h2>Ulasan dan Rating</h2>
            <p class="rating">
                @if ($book->reviews_count)
                    ★ {{ number_format($book->reviews_avg_rating, 1) }} / 5 ({{ $book->reviews_count }} ulasan)
                @else
                    Belum ada rating
                @endif
            </p>

            @auth
                @if (auth()->user()->role === 'member' && $canReview)
                    <h3>{{ $myReview ? 'Perbarui ulasan Anda' : 'Tulis ulasan' }}</h3>
                    <form class="review-form" method="POST" action="{{ route('member.books.reviews.store', $book) }}">
                        @csrf
                        <label for="rating">Rating</label>
                        <select id="rating" name="rating" required>
                            <option value="">Pilih rating</option>
                            @foreach (range(5, 1) as $score)
                                <option value="{{ $score }}" @selected(old('rating', $myReview?->rating) == $score)>{{ $score }} - {{ $score === 1 ? 'Buruk' : ($score === 5 ? 'Sangat baik' : 'Bintang') }}</option>
                            @endforeach
                        </select>
                        @error('rating') <p class="error">{{ $message }}</p> @enderror

                        <label for="ulasan">Ulasan (opsional)</label>
                        <textarea id="ulasan" name="ulasan" maxlength="2000">{{ old('ulasan', $myReview?->ulasan) }}</textarea>
                        @error('ulasan') <p class="error">{{ $message }}</p> @enderror
                        <p><button type="submit">{{ $myReview ? 'Simpan perubahan' : 'Kirim ulasan' }}</button></p>
                    </form>
                @elseif (auth()->user()->role === 'member')
                    <p>Ulasan dapat diberikan setelah Anda selesai meminjam dan mengembalikan buku ini.</p>
                @endif
            @endauth

            @forelse ($book->reviews as $review)
                <article class="review-card">
                    <strong>{{ $review->user->name }}</strong>
                    <span class="rating">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</span>
                    <small> · {{ $review->updated_at->format('d-m-Y') }}</small>
                    @if ($review->ulasan)
                        <p>{{ $review->ulasan }}</p>
                    @endif
                </article>
            @empty
                <p>Jadilah yang pertama memberikan ulasan untuk buku ini.</p>
            @endforelse
        </section>
    </div>
@endsection
