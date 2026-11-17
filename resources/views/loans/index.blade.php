@extends('layouts.app')

@section('title', 'Daftar Peminjaman')

@section('content')
    <h1>Daftar Peminjaman</h1>
    <p><a href="{{ route('loans.create') }}" class="btn">+ Tambah Peminjaman</a></p>

    <div class="loans-table-wrap">
        <table class="loans-table">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>NIM</th>
                    <th>Anggota</th>
                    <th>Petugas</th>
                    <th>Kode Buku</th>
                    <th>Judul Buku</th>
                    <th>Tgl Pinjam</th>
                    <th>Tgl Kembali</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody>
                @forelse ($loans as $loan)
                    <tr>
                        <td>{{ $loan->id }}</td>
                        <td>{{ $loan->member->nim }}</td>
                        <td>{{ $loan->member->nama }}</td>
                        <td>{{ $loan->user->name }}</td>
                        <td>
                            <ul class="loan-book-list">
                                @foreach ($loan->loanItems as $item)
                                    <li>{{ $item->book->kode_buku ?? '-' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>
                            <ul class="loan-book-list">
                                @foreach ($loan->loanItems as $item)
                                    <li>{{ $item->book->judul ?? 'Buku tidak tersedia' }}</li>
                                @endforeach
                            </ul>
                        </td>
                        <td>{{ $loan->tanggal_pinjam }}</td>
                        <td>{{ $loan->tanggal_kembali }}</td>
                        <td><span class="badge badge-{{ $loan->status }}">{{ ucfirst($loan->status) }}</span></td>
                        <td class="loan-actions">
                            <a href="{{ route('loans.show', $loan->id) }}">Detail</a> |
                            <a href="{{ route('loans.edit', $loan->id) }}">Edit</a> |
                            <form class="inline danger-action" action="{{ route('loans.destroy', $loan->id) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button type="submit">Hapus</button>
                            </form>
                            @if ($loan->status === 'dipinjam')
                                <form class="inline" action="{{ route('loans.kembalikan', $loan->id) }}" method="POST" onsubmit="return confirm('Yakin ingin mengembalikan buku ini?');">
                                    @csrf
                                    @method('PUT')
                                    <button type="submit" class="text-button">Kembalikan</button>
                                </form>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr><td colspan="10">Belum ada data peminjaman.</td></tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{ $loans->links() }}
@endsection
