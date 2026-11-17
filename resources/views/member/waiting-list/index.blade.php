@extends('layouts.app')

@section('title', 'Waiting List Buku')

@section('content')
    <h1>Waiting List Buku</h1>
    <p>Jika buku dikembalikan, notifikasi akan dikirim ke member yang memasukkannya ke waiting list.</p>

    @if (! $memberTerhubung)
        <p>Data anggota belum terhubung dengan akun ini. Hubungi petugas perpustakaan.</p>
    @endif

    <div class="table-wrap"><table>
        <thead>
            <tr><th>Kode Buku</th><th>Judul</th><th>Status</th><th>Aksi</th></tr>
        </thead>
        <tbody>
            @forelse ($entries as $entry)
                <tr>
                    <td>{{ $entry->book->kode_buku ?? '-' }}</td>
                    <td>{{ $entry->book->judul ?? 'Buku tidak tersedia' }}</td>
                    <td>
                        @if ($entry->status === 'diberitahu')
                            Buku sudah dikembalikan — notifikasi dikirim
                        @else
                            Menunggu buku dikembalikan
                        @endif
                    </td>
                    <td>
                        <form class="danger-action" method="POST" action="{{ route('member.waiting-list.destroy', $entry->id) }}">
                            @csrf
                            @method('DELETE')
                            <button type="submit">Hapus</button>
                        </form>
                    </td>
                </tr>
            @empty
                <tr><td colspan="4">Waiting list Anda masih kosong.</td></tr>
            @endforelse
        </tbody>
    </table></div>
@endsection
