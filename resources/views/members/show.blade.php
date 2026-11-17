@extends('layouts.app')

@section('title', 'Detail Anggota')

@section('content')
<div class='detail-panel'>
    <h1>Detail Anggota</h1>
    <p><a href="{{ route('members.index') }}">&larr; Kembali ke daftar anggota</a></p>

    <div class="table-wrap"><table class="info">
        <tr>
            <th>Nama</th>
            <td>{{ $member->nama }}</td>
        </tr>
        <tr>
            <th>NIM</th>
            <td>{{ $member->nim }}</td>
        </tr>
        <tr>
            <th>Email</th>
            <td>{{ $member->email }}</td>
        </tr>
        <tr>
            <th>Nomor Telepon</th>
            <td>{{ $member->nomor_telepon }}</td>
        </tr>
        <tr>
            <th>Alamat</th>
            <td>{{ $member->alamat }}</td>
        </tr>
        <tr>
            <th>Status</th>
            <td>{{ ucfirst($member->status) }}</td>
        </tr>
        <tr>
            <th>Akun login</th>
            <td>{{ $member->user ? $member->user->email.' ('.$member->user->role.')' : 'Belum terhubung' }}</td>
        </tr>
    </table></div>

    <h2>Riwayat Peminjaman</h2>
    <p><em>Diambil lewat relasi <code>$member->loans</code> - satu anggota bisa punya banyak transaksi peminjaman.</em></p>

    <div class="table-wrap"><table>
        <thead>
            <tr>
                <th>Tanggal Pinjam</th>
                <th>Tanggal Kembali</th>
                <th>Petugas</th>
                <th>Buku</th>
                <th>Status</th>
            </tr>
        </thead>
        <tbody>
            @forelse ($member->loans as $loan)
                <tr>
                    <td>{{ $loan->tanggal_pinjam }}</td>
                    <td>{{ $loan->tanggal_kembali }}</td>
                    {{-- Menggunakan panah bertingkat untuk relasi --}}
                    <td>{{ $loan->user->name ?? 'Tidak ada data' }}</td>
                    <td>
                        @foreach ($loan->loanItems as $item)
                            {{ $item->book->kode_buku ?? '-' }} â€” {{ $item->book->judul }}@if (!$loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ ucfirst($loan->status) }}</td>
                </tr>
            @empty
                <tr>
                    <td colspan="5">Anggota ini belum pernah meminjam buku.</td>
                </tr>
            @endforelse
        </tbody>
    </table></div>
</div>
@endsection
