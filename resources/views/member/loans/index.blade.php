@extends('layouts.app')

@section('title', 'Pinjaman Saya')

@section('content')
    <h1>Pinjaman Saya</h1>

    @if (! $memberTerhubung)
        <div class="alert alert-warning">
            Akun ini belum terhubung dengan data anggota. Hubungi petugas agar peminjaman dapat ditampilkan.
        </div>
    @endif

    <h2>Sedang Dipinjam</h2>
    <div class="table-wrap"><table>
        <thead>
            <tr><th>Buku</th><th>Tanggal Pinjam</th><th>Batas Kembali</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse ($pinjamanAktif as $loan)
                <tr>
                    <td>
                        @foreach ($loan->loanItems as $item)
                            {{ $item->book->judul ?? 'Buku tidak tersedia' }}@if (! $loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ \Illuminate\Support\Carbon::parse($loan->tanggal_pinjam)->format('d-m-Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($loan->tanggal_kembali)->format('d-m-Y') }}</td>
                    <td><span class="badge badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="4">Tidak ada pinjaman yang sedang aktif.</td></tr>
            @endforelse
        </tbody>
    </table></div>

    <h2>Riwayat Peminjaman</h2>
    <div class="table-wrap"><table>
        <thead>
            <tr><th>Buku</th><th>Tanggal Pinjam</th><th>Batas Kembali</th><th>Dikembalikan</th><th>Status</th></tr>
        </thead>
        <tbody>
            @forelse ($riwayatPinjaman as $loan)
                <tr>
                    <td>
                        @foreach ($loan->loanItems as $item)
                            {{ $item->book->judul ?? 'Buku tidak tersedia' }}@if (! $loop->last), @endif
                        @endforeach
                    </td>
                    <td>{{ \Illuminate\Support\Carbon::parse($loan->tanggal_pinjam)->format('d-m-Y') }}</td>
                    <td>{{ \Illuminate\Support\Carbon::parse($loan->tanggal_kembali)->format('d-m-Y') }}</td>
                    <td>{{ $loan->tanggal_dikembalikan ? \Illuminate\Support\Carbon::parse($loan->tanggal_dikembalikan)->format('d-m-Y') : '-' }}</td>
                    <td><span class="badge badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                </tr>
            @empty
                <tr><td colspan="5">Belum ada riwayat peminjaman.</td></tr>
            @endforelse
        </tbody>
    </table></div>
@endsection
