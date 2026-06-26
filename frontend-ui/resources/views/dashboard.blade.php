<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sistem Perpustakaan — EAI</title>
    <link
        href="https://fonts.googleapis.com/css2?family=DM+Serif+Display:ital@0;1&family=DM+Sans:wght@300;400;500&display=swap"
        rel="stylesheet">
    <style>
        *,
        *::before,
        *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        :root {
            --bg: #F7F4EF;
            --surface: #FDFCF9;
            --border: #E8E2D9;
            --text: #1C1917;
            --muted: #A09990;
            --cream: #EDE8DF;
            --accent: #2C2C2C;
            --green: #3D6B4F;
            --amber: #B5813A;
            --red: #A63D2F;
            --radius: 12px;
        }

        body {
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            color: var(--text);
            min-height: 100vh;
        }

        /* NAV */
        nav {
            background: var(--surface);
            border-bottom: 1px solid var(--border);
            padding: 0 2.5rem;
            height: 64px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .nav-brand {
            font-family: 'DM Serif Display', serif;
            font-size: 1.2rem;
            color: var(--text);
            letter-spacing: -0.01em;
        }

        .nav-brand span {
            font-family: 'DM Sans', sans-serif;
            font-size: 0.7rem;
            font-weight: 300;
            color: var(--muted);
            display: block;
            letter-spacing: 0.08em;
            text-transform: uppercase;
            margin-top: 1px;
        }

        .nav-badge {
            font-size: 0.7rem;
            font-weight: 500;
            color: var(--muted);
            letter-spacing: 0.1em;
            text-transform: uppercase;
            background: var(--cream);
            padding: 4px 12px;
            border-radius: 100px;
            border: 1px solid var(--border);
        }

        /* MAIN */
        main {
            max-width: 1200px;
            margin: 0 auto;
            padding: 2.5rem 2rem;
        }

        /* ALERTS */
        .alert {
            padding: 0.875rem 1.25rem;
            border-radius: var(--radius);
            font-size: 0.875rem;
            font-weight: 400;
            margin-bottom: 1.5rem;
            border: 1px solid;
            display: flex;
            align-items: center;
            gap: 0.5rem;
        }

        .alert-success {
            background: #F0F7F3;
            border-color: #B8D9C6;
            color: var(--green);
        }

        .alert-danger {
            background: #FDF1EF;
            border-color: #E8C4BE;
            color: var(--red);
        }

        /* GRID */
        .grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 1.5rem;
        }

        /* CARD */
        .card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: var(--radius);
            overflow: hidden;
            transition: box-shadow 0.2s ease;
        }

        .card:hover {
            box-shadow: 0 4px 24px rgba(0, 0, 0, 0.06);
        }

        .card-header {
            padding: 1.25rem 1.5rem;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            gap: 0.625rem;
        }

        .card-header .icon {
            width: 28px;
            height: 28px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.875rem;
            flex-shrink: 0;
        }

        .icon-blue {
            background: #EBF2FF;
        }

        .icon-green {
            background: #EBF5EF;
        }

        .icon-amber {
            background: #FBF4E8;
        }

        .card-header-text {
            flex: 1;
        }

        .card-title {
            font-family: 'DM Serif Display', serif;
            font-size: 0.95rem;
            color: var(--text);
            line-height: 1.2;
        }

        .card-subtitle {
            font-size: 0.68rem;
            color: var(--muted);
            font-weight: 400;
            letter-spacing: 0.06em;
            text-transform: uppercase;
            margin-top: 1px;
        }

        .card-body {
            padding: 1.5rem;
        }

        /* FORM */
        .form-group {
            margin-bottom: 0.625rem;
        }

        input,
        select {
            width: 100%;
            padding: 0.625rem 0.875rem;
            border: 1px solid var(--border);
            border-radius: 8px;
            background: var(--bg);
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            color: var(--text);
            outline: none;
            transition: border-color 0.15s, background 0.15s;
            appearance: none;
        }

        input::placeholder {
            color: var(--muted);
        }

        input:focus,
        select:focus {
            border-color: var(--accent);
            background: var(--surface);
        }

        select {
            background-image: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' width='12' height='8' viewBox='0 0 12 8'%3E%3Cpath d='M1 1l5 5 5-5' stroke='%23A09990' stroke-width='1.5' fill='none' stroke-linecap='round'/%3E%3C/svg%3E");
            background-repeat: no-repeat;
            background-position: right 0.875rem center;
            padding-right: 2.25rem;
            cursor: pointer;
        }

        .btn {
            width: 100%;
            padding: 0.625rem 1rem;
            border: none;
            border-radius: 8px;
            font-family: 'DM Sans', sans-serif;
            font-size: 0.8rem;
            font-weight: 500;
            cursor: pointer;
            transition: opacity 0.15s, transform 0.1s;
            margin-top: 0.375rem;
            letter-spacing: 0.01em;
        }

        .btn:hover {
            opacity: 0.88;
        }

        .btn:active {
            transform: scale(0.98);
        }

        .btn-blue {
            background: #2B5CE6;
            color: #fff;
        }

        .btn-green {
            background: var(--green);
            color: #fff;
        }

        .btn-amber {
            background: var(--amber);
            color: #fff;
        }

        /* DIVIDER */
        .divider {
            height: 1px;
            background: var(--border);
            margin: 1.25rem 0;
        }

        /* LIST */
        .list {
            list-style: none;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .list-item {
            padding: 0.75rem 1rem;
            border-radius: 8px;
            background: var(--bg);
            border: 1px solid var(--border);
            font-size: 0.825rem;
            transition: background 0.15s;
        }

        .list-item:hover {
            background: var(--cream);
        }

        .list-item strong {
            font-weight: 500;
            display: block;
            margin-bottom: 2px;
        }

        .list-item small {
            color: var(--muted);
            font-size: 0.75rem;
        }

        .empty {
            text-align: center;
            color: var(--muted);
            font-size: 0.8rem;
            padding: 1.5rem 0;
            font-style: italic;
        }

        /* LOAN ITEM */
        .loan-row {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 0.5rem;
            flex-wrap: wrap;
        }

        .loan-info {
            flex: 1;
        }

        .badge {
            font-size: 0.65rem;
            font-weight: 500;
            padding: 3px 8px;
            border-radius: 100px;
            letter-spacing: 0.04em;
            text-transform: uppercase;
            display: inline-block;
            margin-top: 3px;
        }

        .badge-borrowed {
            background: #FEF0EE;
            color: var(--red);
            border: 1px solid #F3C9C3;
        }

        .badge-returned {
            background: #EBF5EF;
            color: var(--green);
            border: 1px solid #B8D9C6;
        }

        .btn-return {
            font-size: 0.7rem;
            font-weight: 500;
            padding: 4px 10px;
            border-radius: 6px;
            background: transparent;
            border: 1px solid var(--border);
            color: var(--text);
            cursor: pointer;
            transition: background 0.15s, border-color 0.15s;
            white-space: nowrap;
            width: auto;
            margin-top: 0;
            font-family: 'DM Sans', sans-serif;
        }

        .btn-return:hover {
            background: var(--cream);
            border-color: var(--accent);
            opacity: 1;
        }

        /* PAGE HEADER */
        .page-header {
            margin-bottom: 2rem;
        }

        .page-title {
            font-family: 'DM Serif Display', serif;
            font-size: 1.75rem;
            color: var(--text);
            letter-spacing: -0.02em;
            line-height: 1.2;
        }

        .page-title em {
            font-style: italic;
            color: var(--muted);
        }

        .page-desc {
            font-size: 0.8rem;
            color: var(--muted);
            margin-top: 0.375rem;
            font-weight: 300;
            letter-spacing: 0.02em;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: 1fr;
            }

            nav {
                padding: 0 1.25rem;
            }

            main {
                padding: 1.5rem 1.25rem;
            }
        }
    </style>
</head>

<body>

    <nav>
        <div class="nav-brand">
            Booklyst Library
            <span>Enterprise Application Integration</span>
        </div>
        <div class="nav-badge">Service-to-Service</div>
    </nav>

    <main>

        @if(session('success'))
            <div class="alert alert-success">✓ &nbsp;{{ session('success') }}</div>
        @endif
        @if(session('error'))
            <div class="alert alert-danger">✕ &nbsp;{{ session('error') }}</div>
        @endif

        <div class="page-header">
            <h1 class="page-title">Dashboard <em>Perpustakaan</em></h1>
            <p class="page-desc">Kelola anggota, koleksi buku, dan peminjaman dari satu halaman.</p>
        </div>

        <div class="grid">

            {{-- USER SERVICE --}}
            <div class="card">
                <div class="card-header">
                    <div class="icon icon-blue">👤</div>
                    <div class="card-header-text">
                        <div class="card-title">User Service</div>
                        <div class="card-subtitle">Port 8001 · Provider</div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="/members" method="POST">
                        @csrf
                        <div class="form-group">
                            <input name="name" placeholder="Nama lengkap" required>
                        </div>
                        <div class="form-group">
                            <input name="email" type="email" placeholder="Alamat email" required>
                        </div>
                        <div class="form-group">
                            <input name="phone" placeholder="Nomor HP">
                        </div>
                        <button class="btn btn-blue" type="submit">Tambah Member</button>
                    </form>
                    <div class="divider"></div>
                    <ul class="list">
                        @forelse($members as $m)
                            <li class="list-item">
                                <strong>{{ $m['name'] }}</strong>
                                <small>{{ $m['email'] }}</small>
                            </li>
                        @empty
                            <p class="empty">Belum ada anggota terdaftar</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- BOOK SERVICE --}}
            <div class="card">
                <div class="card-header">
                    <div class="icon icon-green">📖</div>
                    <div class="card-header-text">
                        <div class="card-title">Book Service</div>
                        <div class="card-subtitle">Port 8002 · Provider</div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="/books" method="POST">
                        @csrf
                        <div class="form-group">
                            <input name="title" placeholder="Judul buku" required>
                        </div>
                        <div class="form-group">
                            <input name="author" placeholder="Nama pengarang" required>
                        </div>
                        <div class="form-group">
                            <input name="isbn" placeholder="Nomor ISBN" required>
                        </div>
                        <div class="form-group">
                            <input name="stock" type="number" placeholder="Jumlah stok" value="1" min="1">
                        </div>
                        <button class="btn btn-green" type="submit">Tambah Buku</button>
                    </form>
                    <div class="divider"></div>
                    <ul class="list">
                        @forelse($books as $b)
                            <li class="list-item">
                                <strong>{{ $b['title'] }}</strong>
                                <small>{{ $b['author'] }} &nbsp;·&nbsp; Stok: {{ $b['stock'] }}</small>
                            </li>
                        @empty
                            <p class="empty">Belum ada buku terdaftar</p>
                        @endforelse
                    </ul>
                </div>
            </div>

            {{-- LOAN SERVICE --}}
            <div class="card">
                <div class="card-header">
                    <div class="icon icon-amber">🔄</div>
                    <div class="card-header-text">
                        <div class="card-title">Loan Service</div>
                        <div class="card-subtitle">Port 8003 · Consumer + Provider</div>
                    </div>
                </div>
                <div class="card-body">
                    <form action="/loans" method="POST">
                        @csrf
                        <div class="form-group">
                            <select name="member_id" required>
                                <option value="">Pilih anggota</option>
                                @foreach($members as $m)
                                    <option value="{{ $m['id'] }}">{{ $m['name'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <select name="book_id" required>
                                <option value="">Pilih buku</option>
                                @foreach($books as $b)
                                    <option value="{{ $b['id'] }}">{{ $b['title'] }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="form-group">
                            <input name="loan_date" type="date" value="{{ date('Y-m-d') }}">
                        </div>
                        <button class="btn btn-amber" type="submit">Pinjam Buku</button>
                    </form>
                    <div class="divider"></div>
                    <ul class="list">
                        @forelse($loans as $l)
                            <li class="list-item">
                                <div class="loan-row">
                                    <div class="loan-info">
                                        <strong>Member #{{ $l['member_id'] }} → Buku #{{ $l['book_id'] }}</strong>
                                        <span
                                            class="badge {{ $l['status'] === 'borrowed' ? 'badge-borrowed' : 'badge-returned' }}">
                                            {{ $l['status'] === 'borrowed' ? 'Dipinjam' : 'Dikembalikan' }}
                                        </span>
                                    </div>
                                    @if($l['status'] === 'borrowed')
                                        <form action="/loans/{{ $l['id'] }}/return" method="POST">
                                            @csrf @method('PUT')
                                            <button class="btn-return" type="submit">Kembalikan</button>
                                        </form>
                                    @endif
                                </div>
                            </li>
                        @empty
                            <p class="empty">Belum ada peminjaman aktif</p>
                        @endforelse
                    </ul>
                </div>
            </div>

        </div>
    </main>

</body>

</html>