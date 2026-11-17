@extends('layouts.app')

@section('title', 'Beranda Member')

@section('content')
    <h1>Halo, {{ auth()->user()->name }}!</h1>
    <p>Selamat datang di area member. Kamu dapat menjelajahi koleksi buku dan melihat profil akunmu.</p>

    <div class="stats-grid">
        <div class="stat-card">
            <div class="value">{{ $totalBuku }}</div>
            <div class="label">Judul buku tersedia di katalog</div>
        </div>
        <a class="stat-card" href="{{ route('member.loans.index') }}">
            <div class="value">{{ $pinjamanAktif }}</div>
            <div class="label">Pinjaman aktif · lihat riwayat</div>
        </a>
    </div>

    <h2>Buku terbaru</h2>
    @forelse ($bukuTerbaru as $book)
        <p><a href="{{ route('member.books.show', $book) }}">{{ $book->judul }}</a> — {{ $book->penulis }}</p>
    @empty
        <p>Belum ada buku di katalog.</p>
    @endforelse
    <p><a class="btn" href="{{ route('member.books.index') }}">Jelajahi semua buku</a></p>
@endsection
