<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Member · Perpustakaan Kampus</title>
    <style>
        :root { --navy:#123b5b; --deep:#0b2d49; --teal:#0d9488; --soft:#e4f6f3; --muted:#698092; }
        * { box-sizing:border-box; }
        body { min-height:100vh;margin:0;padding:28px 16px;display:grid;place-items:center;color:var(--navy);background:radial-gradient(circle at top left,#d8f1eb 0,transparent 36%),linear-gradient(135deg,#edf5f7,#f8fbfb 55%,#e8f4f2);font:15px/1.5 Inter,ui-sans-serif,system-ui,-apple-system,"Segoe UI",sans-serif; }
        .auth-card { width:min(100%,560px);padding:34px;border:1px solid #dce8ec;border-radius:22px;background:#fff;box-shadow:0 24px 60px rgba(18,59,91,.13); }
        .auth-mark { width:48px;height:48px;display:grid;place-items:center;border-radius:15px;background:var(--soft);color:var(--teal);font-size:24px; }
        .auth-kicker { margin:20px 0 5px;color:var(--teal);font-size:12px;font-weight:800;letter-spacing:.12em;text-transform:uppercase; }
        h1 { margin:0;color:var(--deep);font-size:27px;letter-spacing:-.04em; }
        .auth-copy { margin:8px 0 20px;color:var(--muted); }
        label { display:block;margin:13px 0 6px;color:var(--navy);font-size:13px;font-weight:700; }
        input,textarea { display:block;width:100%;min-height:43px;padding:10px 12px;border:1px solid #cbdce2;border-radius:10px;background:#fbfdfd;color:var(--navy);font:inherit; }
        textarea { min-height:86px;resize:vertical; }
        input:focus,textarea:focus { outline:0;border-color:var(--teal);box-shadow:0 0 0 3px rgba(13,148,136,.13);background:#fff; }
        .auth-button { width:100%;height:46px;margin-top:20px;border:0;border-radius:10px;background:var(--teal);color:#fff;font:inherit;font-weight:750;cursor:pointer; }
        .auth-button:hover { background:#08766f; }
        .auth-footer { margin:22px 0 0;color:var(--muted);text-align:center;font-size:13px; }
        a { color:#08766f;font-weight:700;text-decoration:none; }
        a:hover { text-decoration:underline; }
        .error { margin-top:5px;color:#b83d4b;font-size:13px; }
        @media(max-width:520px) { .auth-card { padding:24px; } }
    </style>
</head>
<body>
    <main class="auth-card">
        <div class="auth-mark" aria-hidden="true">&#128218;</div>
        <p class="auth-kicker">Perpustakaan Kampus</p>
        <h1>Daftar akun member</h1>
        <p class="auth-copy">Akun login dan data anggota akan dibuat sekaligus.</p>
        <form action="{{ route('register') }}" method="POST">
            @csrf
            <label for="nama">Nama lengkap</label>
            <input id="nama" name="nama" value="{{ old('nama') }}" required maxlength="100">
            @error('nama') <div class="error">{{ $message }}</div> @enderror

            <label for="nim">NIM</label>
            <input id="nim" name="nim" value="{{ old('nim') }}" required maxlength="20">
            @error('nim') <div class="error">{{ $message }}</div> @enderror

            <label for="email">Email kampus</label>
            <input id="email" name="email" type="email" value="{{ old('email') }}" placeholder="nama@pens.ac.id" required maxlength="100">
            @error('email') <div class="error">{{ $message }}</div> @enderror

            <label for="nomor_telepon">Nomor telepon</label>
            <input id="nomor_telepon" name="nomor_telepon" value="{{ old('nomor_telepon') }}" required maxlength="15">
            @error('nomor_telepon') <div class="error">{{ $message }}</div> @enderror

            <label for="alamat">Alamat</label>
            <textarea id="alamat" name="alamat" required maxlength="2000">{{ old('alamat') }}</textarea>
            @error('alamat') <div class="error">{{ $message }}</div> @enderror

            <label for="password">Password (minimal 8 karakter)</label>
            <input id="password" name="password" type="password" required autocomplete="new-password">
            @error('password') <div class="error">{{ $message }}</div> @enderror

            <label for="password_confirmation">Konfirmasi password</label>
            <input id="password_confirmation" name="password_confirmation" type="password" required autocomplete="new-password">
            <button class="auth-button" type="submit">Buat akun member</button>
        </form>
        <p class="auth-footer">Sudah punya akun? <a href="{{ route('login') }}">Login</a></p>
    </main>
</body>
</html>