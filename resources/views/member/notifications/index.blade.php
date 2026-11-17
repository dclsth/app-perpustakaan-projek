@extends('layouts.app')

@section('title', 'Notifikasi')

@section('content')
    <h1>Notifikasi</h1>
    <p>{{ $unreadCount }} notifikasi belum dibaca.</p>

    @forelse ($notifications as $notification)
        <article class="notification-card {{ $notification->read_at ? '' : 'unread' }}">
            <p>{{ $notification->data['message'] ?? 'Ada notifikasi baru.' }}</p>
            @if (($notification->data['kind'] ?? null) === 'book_available')
                <p>Kode: {{ $notification->data['kode_buku'] ?? '-' }}</p>
                <a href="{{ route('member.books.show', $notification->data['book_id']) }}">Lihat detail buku</a>
            @elseif (($notification->data['kind'] ?? null) === 'loan_due')
                @if (! empty($notification->data['book_titles']))
                    <p>Buku: {{ implode(', ', $notification->data['book_titles']) }}</p>
                @endif
                <a href="{{ route('member.loans.index') }}">Lihat pinjaman saya</a>
            @endif
            <small class="notification-time">{{ $notification->created_at->format('d-m-Y H:i') }}</small>
            @if (! $notification->read_at)
                <form method="POST" action="{{ route('member.notifications.read', $notification->id) }}" class="form-spaced">
                    @csrf
                    <button type="submit">Tandai sudah dibaca</button>
                </form>
            @endif
        </article>
    @empty
        <p>Belum ada notifikasi.</p>
    @endforelse

    {{ $notifications->links() }}
@endsection
