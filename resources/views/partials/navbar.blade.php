<nav class="topbar">
    <div class="nav-inner">
        <a class="brand" href="{{ auth()->user()?->role === 'member' ? route('member.dashboard') : route('dashboard') }}">Perpustakaan Digital Kampus</a>

        @auth
            <ul class="nav-links">
                @if (auth()->user()->role === 'member')
                    <li><a href="{{ route('member.dashboard') }}" class="{{ request()->routeIs('member.dashboard') ? 'active' : '' }}">Beranda</a></li>
                    <li><a href="{{ route('member.books.index') }}" class="{{ request()->routeIs('member.books.*') ? 'active' : '' }}">Jelajahi Buku</a></li>
                    <li><a href="{{ route('member.loans.index') }}" class="{{ request()->routeIs('member.loans.*') ? 'active' : '' }}">Pinjaman Saya</a></li>
                    <li><a href="{{ route('member.waiting-list.index') }}" class="{{ request()->routeIs('member.waiting-list.*') ? 'active' : '' }}">Waiting List</a></li>
                    <li><a href="{{ route('member.profile') }}" class="{{ request()->routeIs('member.profile') ? 'active' : '' }}">Profil Saya</a></li>
                @else
                    <li><a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Dashboard</a></li>
                    <li><a href="{{ route('books.index') }}" class="{{ request()->routeIs('books.*') ? 'active' : '' }}">Buku</a></li>
                    @if (auth()->user()->role === 'admin')
                        <li><a href="{{ route('categories.index') }}" class="{{ request()->routeIs('categories.*') ? 'active' : '' }}">Kategori</a></li>
                    @endif
                    <li><a href="{{ route('members.index') }}" class="{{ request()->routeIs('members.*') ? 'active' : '' }}">Anggota</a></li>
                    <li><a href="{{ route('loans.index') }}" class="{{ request()->routeIs('loans.index') || request()->routeIs('loans.create') || request()->routeIs('loans.show') || request()->routeIs('loans.edit') ? 'active' : '' }}">Peminjaman</a></li>
                    <li><a href="{{ route('loans.report') }}" class="{{ request()->routeIs('loans.report') ? 'active' : '' }}">Laporan</a></li>
                @endif
            </ul>

            <div class="navbar-user">
                @if (auth()->user()->role === 'member')
                    @php($unreadNotificationCount = auth()->user()->unreadNotifications()->count())
                    <a class="nav-bell" href="{{ route('member.notifications.index') }}" aria-label="Notifikasi" title="Notifikasi">
                        &#128276;
                        @if ($unreadNotificationCount > 0)
                            <span class="nav-count">{{ $unreadNotificationCount }}</span>
                        @endif
                    </a>
                @endif
                <span>{{ auth()->user()->name }} · {{ ucfirst(auth()->user()->role) }}</span>
                <form action="{{ route('logout') }}" method="POST" class="inline">
                    @csrf
                    <button type="submit" class="btn-logout">Keluar</button>
                </form>
            </div>
        @endauth

        @guest
            <div class="navbar-user">
                <a class="guest-link" href="{{ route('login') }}">Login</a>
                <a class="btn" href="{{ route('register') }}">Daftar Member</a>
            </div>
        @endguest
    </div>
</nav>
