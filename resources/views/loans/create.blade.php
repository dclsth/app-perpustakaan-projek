@extends('layouts.app')

@section('title', 'Input Peminjaman')

@section('content')
<div class='form-panel'>
    <h1>Input Peminjaman</h1>
    <p><a href="{{ route('loans.index') }}">&larr; Kembali ke daftar peminjaman</a></p>

    <form action="{{ route('loans.store') }}" method="POST">
        @csrf

        <label for="nim">NIM Peminjam</label>
        <input type="text" name="nim" id="nim" value="{{ old('nim') }}" required autofocus>
        @error('nim') <div class="error">{{ $message }}</div> @enderror

        <div class="context-note"><strong>Petugas pencatat</strong><br>{{ auth()->user()->name }} (otomatis dari akun yang login)</div>
        <p><strong>Tanggal pinjam:</strong> {{ today('Asia/Jakarta')->format('d-m-Y') }}</p>
        <p><strong>Batas pengembalian:</strong> {{ today('Asia/Jakarta')->addWeek()->format('d-m-Y') }} (otomatis 7 hari)</p>

        <label for="kode_buku">Kode Buku</label>
        <textarea name="kode_buku" id="kode_buku" rows="4" placeholder="Satu kode per baris, atau pisahkan dengan koma" required>{{ old('kode_buku') }}</textarea>
        @error('kode_buku') <div class="error">{{ $message }}</div> @enderror

        <h2>Referensi Kode Buku</h2>
        <ul>
            @forelse ($books as $book)
                <li>{{ $book->kode_buku }} â€” {{ $book->judul }} (stok: {{ $book->stok }}, status: {{ $book->sedang_dipinjam ? 'Sedang dipinjam' : 'Tersedia' }})</li>
            @empty
                <li>Belum ada data buku.</li>
            @endforelse
        </ul>

        <button type="submit" class="btn">Simpan Peminjaman</button>
    </form>
</div>
@endsection
