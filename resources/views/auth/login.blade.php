<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login · Perpustakaan Kampus</title>
    <style>
        :root { --navy:#123b5b; --deep:#0b2d49; --teal:#0d9488; --soft:#e4f6f3; --muted:#698092; --line:#dce8ec; }
        * { box-sizing:border-box; }
        body { min-height:100vh; margin:0; padding:24px; display:grid; place-items:center; color:var(--navy); background:radial-gradient(circle at top left,#d8f1eb 0,transparent 36%),linear-gradient(135deg,#edf5f7,#f8fbfb 55%,#e8f4f2); font:15px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif; }
        .auth-card { width:min(100%,420px); padding:34px; border:1px solid rgba(220,232,236,.9); border-radius:22px; background:#fff; box-shadow:0 24px 60px rgba(18,59,91,.13); }
        .auth-mark { width:48px;height:48px;display:grid;place-items:center;border-radius:15px;background:var(--soft);color:var(--teal);font-size:24px; }
        .auth-kicker { margin:20px 0 5px;color:var(--teal);font-size:12px;font-weight:800;letter-spacing:.12em;text-transform:uppercase; }
        h1 { margin:0;color:var(--deep);font-size:27px;letter-spacing:-.04em; }
        .auth-copy { margin:8px 0 20px;color:var(--muted); }
        label { display:block;margin:15px 0 6px;color:var(--navy);font-size:13px;font-weight:700; }
        input:not([type=checkbox]) { display:block;width:100%;height:44px;padding:10px 12px;border:1px solid #cbdce2;border-radius:10px;background:#fbfdfd;color:var(--navy);font:inherit; }
        input:focus { outline:0;border-color:var(--teal);box-shadow:0 0 0 3px rgba(13,148,136,.13); }
        .checkbox-row { display:flex;align-items:center;gap:8px;margin:15px 0;color:var(--muted);font-size:13px; }
        .checkbox-row label { margin:0;font-weight:500; }
        .checkbox-row input { accent-color:var(--teal); }
        .auth-button { width:100%;height:46px;margin-top:18px;border:0;border-radius:10px;background:var(--teal);color:#fff;font:inherit;font-weight:750;cursor:pointer; }
        .auth-button:hover { background:#08766f; }
        .auth-footer { margin:22px 0 0;color:var(--muted);text-align:center;font-size:13px; }
        a { color:#08766f;font-weight:700;text-decoration:none; }
        a:hover { text-decoration:underline; }
        .error { margin-top:5px;color:#b83d4b;font-size:13px; }
        .alert-success { margin-bottom:16px;padding:12px 14px;border:1px solid #b9e6d9;border-radius:10px;background:#e7f7f1;color:#126852;font-weight:600; }
    </style>
</head>
<body>
    <main class="auth-card">
        <div class="auth-mark" aria-hidden="true">&#128218;</div>
        <p class="auth-kicker">Perpustakaan Kampus</p>
        <h1>Selamat datang</h1>
        <p class="auth-copy">Login untuk melanjutkan ke akun perpustakaan Anda.</p>

        @if (session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf
            <label for="email">Email</label>
            <input type="email" name="email" id="email" value="{{ old('email') }}" autocomplete="username" autofocus required>
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password</label>
            <input type="password" name="password" id="password" autocomplete="current-password" required>
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <div class="checkbox-row">
                <input type="checkbox" name="remember" id="remember">
                <label for="remember">Ingat saya</label>
            </div>
            <button type="submit" class="auth-button">Login</button>
        </form>
        <p class="auth-footer">Belum punya akun? <a href="{{ route('register') }}">Daftar sebagai member</a></p>
    </main>
</body>
</html>