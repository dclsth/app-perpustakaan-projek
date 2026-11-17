<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Perpustakaan Digital Kampus')</title>
    <style>
        :root {
            color-scheme: light;
            --navy: #123b5b;
            --navy-deep: #0b2d49;
            --teal: #0d9488;
            --teal-dark: #08766f;
            --teal-soft: #e4f6f3;
            --ink: #18364d;
            --muted: #698092;
            --line: #dce8ec;
            --surface: #ffffff;
            --canvas: #f2f7f8;
            --danger: #b83d4b;
            --shadow: 0 12px 30px rgba(18, 59, 91, .07);
        }
        * { box-sizing: border-box; }
        html { min-width: 320px; background: var(--canvas); }
        body { margin: 0; color: var(--ink); background: var(--canvas); font: 15px/1.55 Inter, ui-sans-serif, system-ui, -apple-system, BlinkMacSystemFont, "Segoe UI", sans-serif; }
        a { color: var(--teal-dark); text-decoration: none; }
        a:hover { color: var(--navy); text-decoration: underline; }
        .topbar { position: sticky; top: 0; z-index: 20; background: var(--navy-deep); box-shadow: 0 4px 18px rgba(11, 45, 73, .15); }
        .nav-inner { width: min(1280px, 100%); min-height: 72px; margin: 0 auto; padding: 12px 28px; display: flex; align-items: center; justify-content: space-between; gap: 22px; }
        .brand { flex: 0 0 auto; color: #fff; font-size: 16px; font-weight: 750; letter-spacing: -.02em; white-space: nowrap; }
        .brand:hover { color: #fff; text-decoration: none; }
        .nav-links { display: flex; align-items: center; justify-content: center; gap: 5px; margin: 0; padding: 0; list-style: none; flex-wrap: wrap; }
        .nav-links a { display: block; padding: 9px 11px; border-radius: 9px; color: #d7e9ef; font-size: 13px; font-weight: 600; white-space: nowrap; transition: background .18s ease, color .18s ease; }
        .nav-links a:hover, .nav-links a.active { background: rgba(45, 190, 171, .17); color: #fff; text-decoration: none; }
        .guest-link { color: #e5f0f3; font-weight: 700; }
        .navbar-user { display: flex; flex: 0 0 auto; align-items: center; gap: 12px; color: #e5f0f3; font-size: 13px; }
        .nav-bell { position: relative; display: inline-flex; width: 38px; height: 38px; align-items: center; justify-content: center; border: 1px solid rgba(216, 236, 240, .22); border-radius: 12px; color: #fff; font-size: 19px; }
        .nav-bell:hover { background: rgba(45, 190, 171, .18); color: #fff; text-decoration: none; }
        .nav-count { position: absolute; top: -6px; right: -6px; min-width: 19px; padding: 2px 5px; border: 2px solid var(--navy-deep); border-radius: 99px; background: #f07878; color: #fff; text-align: center; font-size: 10px; font-weight: 800; }
        .btn-logout { padding: 8px 12px; border: 1px solid rgba(216, 236, 240, .3); border-radius: 9px; background: transparent; color: #fff; font: inherit; font-weight: 650; cursor: pointer; }
        .btn-logout:hover { background: rgba(255,255,255,.1); }
        .main-container { width: min(1180px, 100%); min-height: calc(100vh - 150px); margin: 0 auto; padding: 34px 28px 56px; }
        main h1 { margin: 0 0 8px; color: var(--navy-deep); font-size: clamp(25px, 3vw, 32px); line-height: 1.2; letter-spacing: -.035em; }
        main h2 { margin: 28px 0 12px; color: var(--navy); font-size: 20px; letter-spacing: -.02em; }
        main h3 { color: var(--navy); }
        main p { color: var(--muted); }
        code { padding: 2px 6px; border-radius: 5px; background: #e8f1f3; color: var(--navy); }
        .page-intro { margin: 0 0 22px; color: var(--muted); }
        .btn, button, input[type="submit"] { display: inline-flex; min-height: 39px; align-items: center; justify-content: center; gap: 7px; padding: 9px 15px; border: 1px solid var(--teal); border-radius: 9px; background: var(--teal); color: #fff; font: inherit; font-size: 14px; font-weight: 700; line-height: 1.2; cursor: pointer; transition: background .18s ease, transform .18s ease, box-shadow .18s ease; }
        .btn:hover, button:hover, input[type="submit"]:hover { border-color: var(--teal-dark); background: var(--teal-dark); color: #fff; text-decoration: none; box-shadow: 0 5px 12px rgba(13,148,136,.18); }
        .btn:active, button:active { transform: translateY(1px); }
        .btn-secondary { border-color: var(--line); background: #fff; color: var(--navy); }
        .btn-danger { border-color: #f0cdd1; background: #fff6f6; color: var(--danger); }
        .danger-action button { min-height: 32px; padding: 6px 10px; border-color: #f0cdd1; background: #fff6f6; color: var(--danger); font-size: 12px; }
        .danger-action button:hover { border-color: #e8aeb6; background: #ffeaec; color: #963744; box-shadow: none; }
        .inline { display: inline; }
        .form-panel, .detail-panel { max-width: 760px; padding: 26px; border: 1px solid var(--line); border-radius: 16px; background: var(--surface); box-shadow: var(--shadow); }
        .form-panel form { margin-top: 18px; }
        label { display: block; margin: 16px 0 6px; color: var(--navy); font-size: 13px; font-weight: 700; }
        input:not([type="checkbox"]):not([type="radio"]), select, textarea { display: block; width: 100%; min-height: 42px; padding: 10px 12px; border: 1px solid #cbdce2; border-radius: 9px; outline: none; background: #fbfdfd; color: var(--ink); font: inherit; transition: border-color .18s ease, box-shadow .18s ease, background .18s ease; }
        textarea { min-height: 100px; resize: vertical; }
        input:focus, select:focus, textarea:focus { border-color: var(--teal); background: #fff; box-shadow: 0 0 0 3px rgba(13,148,136,.12); }
        input[type="checkbox"], input[type="radio"] { accent-color: var(--teal); }
        small, .help-text { color: var(--muted); }
        .error { margin: 5px 0 0; color: var(--danger); font-size: 13px; }
        .form-actions { display: flex; gap: 10px; margin-top: 22px; flex-wrap: wrap; }
        .form-spaced { margin-top: 12px; }
        .context-note, .readonly { margin: 8px 0 16px; padding: 12px 14px; border: 1px solid var(--line); border-radius: 10px; background: #f5faf9; color: var(--muted); }
        .toolbar { display: flex; align-items: center; justify-content: space-between; gap: 16px; margin: 15px 0 18px; flex-wrap: wrap; }
        .toolbar form { display: flex; align-items: center; gap: 8px; }
        .toolbar input { min-width: 230px; }
        .section-action { margin-top: 24px; }
        .alert { margin: 0 0 20px; padding: 13px 16px; border: 1px solid transparent; border-radius: 11px; font-weight: 600; }
        .alert-success { border-color: #b9e6d9; background: #e7f7f1; color: #126852; }
        .alert-error { border-color: #f0cdd1; background: #fff1f2; color: #963744; }
        .alert-warning { border-color: #f3dfab; background: #fff8e6; color: #795d1a; }
        .table-wrap { width: 100%; overflow-x: auto; border: 1px solid var(--line); border-radius: 13px; background: var(--surface); box-shadow: var(--shadow); }
        table { width: 100%; border-collapse: separate; border-spacing: 0; background: var(--surface); }
        th, td { padding: 12px 14px; border-bottom: 1px solid #e8eff1; text-align: left; vertical-align: middle; }
        thead th { background: #eaf5f4; color: var(--navy); font-size: 12px; font-weight: 800; letter-spacing: .04em; text-transform: uppercase; white-space: nowrap; }
        thead th:first-child { border-top-left-radius: 12px; }
        thead th:last-child { border-top-right-radius: 12px; }
        tbody tr:last-child td { border-bottom: 0; }
        tbody tr:hover { background: #f7fbfb; }
        td { color: #375267; }
        .stats-grid, .book-grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(210px, 1fr)); gap: 16px; margin: 22px 0; }
        .stat-card { display: block; min-width: 0; padding: 22px; border: 1px solid var(--line); border-radius: 15px; background: var(--surface); color: inherit; box-shadow: var(--shadow); transition: transform .18s ease, border-color .18s ease, box-shadow .18s ease; }
        a.stat-card:hover { transform: translateY(-2px); border-color: #9cd7cf; color: inherit; text-decoration: none; box-shadow: 0 15px 30px rgba(18,59,91,.1); }
        .stat-card .value { color: var(--teal-dark); font-size: 34px; font-weight: 800; line-height: 1; }
        .stat-card .label { margin-top: 9px; color: var(--muted); font-size: 13px; }
        .stat-card h2 { margin: 0 0 12px; font-size: 18px; }
        .book-card { text-align: left; }
        .badge { display: inline-flex; align-items: center; padding: 5px 9px; border-radius: 99px; font-size: 12px; font-weight: 750; white-space: nowrap; }
        .badge-dikembalikan, .badge-aktif { background: #e1f5ed; color: #137554; }
        .badge-dipinjam { background: #e3f3f7; color: #17647a; }
        .badge-terlambat, .badge-nonaktif { background: #fff0e7; color: #a55325; }
        .review-section { margin-top: 34px; padding-top: 10px; border-top: 1px solid var(--line); }
        .review-card, .notification-card { margin: 12px 0; padding: 16px 18px; border: 1px solid var(--line); border-radius: 12px; background: var(--surface); }
        .notification-card.unread { border-color: #a9dcd4; background: #effaf8; }
        .rating { color: #ad7722; font-weight: 750; }
        .info th { width: 190px; background: #f5faf9; color: var(--navy); }
        .loans-table-wrap { overflow-x: auto; border: 1px solid var(--line); border-radius: 13px; background: var(--surface); box-shadow: var(--shadow); }
        .loans-table { min-width: 1050px; }
        .loans-table td { vertical-align: top; }
        .loan-book-list { margin: 0; padding: 0; list-style: none; }
        .loan-book-list li + li { margin-top: 4px; padding-top: 4px; border-top: 1px solid #e8eff1; }
        .loan-actions { white-space: nowrap; }
        .text-button { min-height: 0; padding: 4px 0; border: 0; background: transparent; color: var(--teal-dark); text-decoration: underline; }
        .text-button:hover { border: 0; background: transparent; color: var(--navy); box-shadow: none; }
        .notification-time { display: block; margin-top: 8px; color: var(--muted); }
        .pagination { display: flex; align-items: center; gap: 8px; margin-top: 18px; flex-wrap: wrap; }
        .pagination a, .pagination span { padding: 7px 11px; border: 1px solid var(--line); border-radius: 8px; background: #fff; }
        .pagination .pagination-current { border-color: var(--teal); background: var(--teal); color: #fff; font-weight: 750; }
        .pagination .pagination-disabled { color: #a1b1ba; }
        footer { padding: 22px; border-top: 1px solid var(--line); background: #eaf2f4; color: var(--muted); text-align: center; font-size: 13px; }
        @media (max-width: 1000px) {
            .nav-inner { align-items: flex-start; flex-wrap: wrap; gap: 10px 16px; }
            .nav-links { order: 3; width: 100%; justify-content: flex-start; }
            .navbar-user { margin-left: auto; }
        }
        @media (max-width: 640px) {
            .nav-inner { padding: 12px 16px; }
            .brand { font-size: 14px; }
            .nav-links { gap: 3px; }
            .nav-links a { padding: 8px; font-size: 12px; }
            .navbar-user { gap: 8px; font-size: 12px; }
            .navbar-user > span { max-width: 115px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; }
            .btn-logout { padding: 7px 9px; }
            .main-container { padding: 24px 15px 40px; }
            .toolbar { align-items: stretch; }
            .toolbar form { width: 100%; }
            .toolbar input { min-width: 0; }
            .form-panel, .detail-panel { padding: 19px; }
            th, td { padding: 10px; }
            .stats-grid, .book-grid { grid-template-columns: 1fr; }
        }
    </style>
</head>
<body>
    @include('partials.navbar')

    <main class="main-container">
        @include('partials.alert')
        @yield('content')
    </main>

    <footer>
        &copy; {{ date('Y') }} Sistem Perpustakaan Digital Kampus
    </footer>
</body>
</html>
