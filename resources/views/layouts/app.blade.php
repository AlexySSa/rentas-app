<!DOCTYPE html>
<html lang="es" data-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistema de alquiler de autos')</title>
    <style>
        :root {
            --bg: radial-gradient(circle at top, #f1f6ff 0%, #e8eef6 45%, #dde6f0 100%);
            --surface: rgba(255, 255, 255, 0.82);
            --surface-strong: #ffffff;
            --surface-muted: rgba(241, 245, 249, 0.8);
            --text: #16233b;
            --muted: #5f7390;
            --border: rgba(140, 160, 186, 0.28);
            --primary: #17345f;
            --primary-soft: rgba(53, 98, 165, 0.12);
            --success: #0f766e;
            --success-soft: rgba(15, 118, 110, 0.16);
            --warning: #9a6700;
            --warning-soft: rgba(154, 103, 0, 0.16);
            --danger: #b42318;
            --danger-soft: rgba(180, 35, 24, 0.14);
            --shadow: 0 20px 45px rgba(22, 35, 59, 0.09);
        }

        html[data-theme="dark"] {
            --bg: radial-gradient(circle at top, #162033 0%, #101827 48%, #0a1120 100%);
            --surface: rgba(15, 23, 42, 0.86);
            --surface-strong: #0f172a;
            --surface-muted: rgba(30, 41, 59, 0.82);
            --text: #e2e8f0;
            --muted: #94a3b8;
            --border: rgba(100, 116, 139, 0.28);
            --primary: #93c5fd;
            --primary-soft: rgba(147, 197, 253, 0.14);
            --success: #5eead4;
            --success-soft: rgba(45, 212, 191, 0.18);
            --warning: #fcd34d;
            --warning-soft: rgba(252, 211, 77, 0.16);
            --danger: #fda4af;
            --danger-soft: rgba(251, 113, 133, 0.14);
            --shadow: 0 20px 45px rgba(0, 0, 0, 0.35);
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif;
            background: var(--bg);
            color: var(--text);
            transition: background 0.25s ease, color 0.25s ease;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
        }

        .site-header {
            position: sticky;
            top: 0;
            z-index: 10;
            backdrop-filter: blur(16px);
            background: color-mix(in srgb, var(--surface) 92%, transparent);
            border-bottom: 1px solid var(--border);
        }

        .site-header__inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            padding: 18px 0;
        }

        .brand {
            display: grid;
            gap: 4px;
        }

        .brand__title {
            font-size: 1.28rem;
            font-weight: 800;
            color: var(--primary);
        }

        .toolbar {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .brand__subtitle {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .nav {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
        }

        .nav a {
            padding: 10px 14px;
            border-radius: 999px;
            color: var(--muted);
            font-weight: 700;
            transition: background 0.2s ease, color 0.2s ease;
        }

        .nav a.active,
        .nav a:hover {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .content {
            padding: 34px 0 44px;
        }

        .card,
        .stats-card {
            background: var(--surface);
            border: 1px solid var(--border);
            border-radius: 22px;
            box-shadow: var(--shadow);
            backdrop-filter: blur(14px);
        }

        .card {
            padding: 26px;
        }

        .page-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 20px;
        }

        .page-header h2 {
            margin: 0 0 6px;
            font-size: 1.9rem;
        }

        .page-header p {
            margin: 0;
            color: var(--muted);
        }

        .grid {
            display: grid;
            gap: 20px;
        }

        .stats-grid {
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        }

        .stats-card {
            padding: 20px 22px;
        }

        .stats-card small {
            display: block;
            color: var(--muted);
            margin-bottom: 10px;
            text-transform: uppercase;
            letter-spacing: 0.06em;
        }

        .stats-card strong {
            font-size: 2rem;
            color: var(--primary);
        }

        .theme-toggle,
        .btn,
        button {
            border: 0;
            border-radius: 12px;
            padding: 11px 16px;
            font-weight: 700;
            cursor: pointer;
            transition: transform 0.2s ease, opacity 0.2s ease, background 0.2s ease;
        }

        .theme-toggle:hover,
        .btn:hover,
        button:hover {
            transform: translateY(-1px);
        }

        .theme-toggle {
            background: var(--surface-muted);
            color: var(--text);
            border: 1px solid var(--border);
        }

        .btn-primary {
            background: var(--primary);
            color: #fff;
        }

        html[data-theme="dark"] .btn-primary {
            color: #081120;
        }

        .btn-secondary {
            background: var(--primary-soft);
            color: var(--primary);
        }

        .btn-danger {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .user-box {
            display: flex;
            align-items: center;
            gap: 12px;
            color: var(--muted);
            font-size: 0.95rem;
        }

        .table-wrapper {
            overflow-x: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        th,
        td {
            padding: 14px 12px;
            border-bottom: 1px solid var(--border);
            text-align: left;
            vertical-align: top;
        }

        th {
            color: var(--muted);
            font-size: 0.85rem;
            text-transform: uppercase;
            letter-spacing: 0.04em;
        }

        .table-actions {
            display: flex;
            gap: 10px;
            flex-wrap: wrap;
        }

        .badge {
            display: inline-flex;
            align-items: center;
            padding: 6px 10px;
            border-radius: 999px;
            font-size: 0.84rem;
            font-weight: 700;
        }

        .badge.disponible,
        .badge.pagado,
        .badge.finalizado {
            background: var(--success-soft);
            color: var(--success);
        }

        .badge.alquilado,
        .badge.pendiente,
        .badge.activo {
            background: var(--warning-soft);
            color: var(--warning);
        }

        .badge.mantenimiento,
        .badge.cancelado {
            background: var(--danger-soft);
            color: var(--danger);
        }

        .form-grid {
            display: grid;
            gap: 18px;
            grid-template-columns: repeat(auto-fit, minmax(240px, 1fr));
        }

        .field {
            display: grid;
            gap: 8px;
        }

        .field--full {
            grid-column: 1 / -1;
        }

        label {
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 14px;
            border-radius: 12px;
            border: 1px solid var(--border);
            font: inherit;
            color: var(--text);
            background: var(--surface-strong);
        }

        textarea {
            min-height: 120px;
            resize: vertical;
        }

        .helper {
            color: var(--muted);
            font-size: 0.92rem;
        }

        .alert {
            margin-bottom: 16px;
            padding: 14px 16px;
            border-radius: 14px;
            border: 1px solid transparent;
        }

        .alert-success {
            background: var(--success-soft);
            border-color: color-mix(in srgb, var(--success) 25%, transparent);
            color: var(--success);
        }

        .alert-error {
            background: var(--danger-soft);
            border-color: color-mix(in srgb, var(--danger) 25%, transparent);
            color: var(--danger);
        }

        .empty-state {
            padding: 24px;
            text-align: center;
            color: var(--muted);
            background: var(--surface-muted);
            border: 1px dashed var(--border);
            border-radius: 16px;
        }

        .pagination {
            margin-top: 18px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
            color: var(--muted);
        }

        .auth-shell {
            min-height: calc(100vh - 120px);
            display: grid;
            place-items: center;
        }

        .panel-cover {
            min-height: 100%;
        }

        @media (max-width: 768px) {
            .site-header__inner,
            .toolbar,
            .page-header,
            .user-box {
                flex-direction: column;
                align-items: flex-start;
            }

            .nav,
            .toolbar {
                width: 100%;
            }

            .nav a,
            .theme-toggle {
                width: 100%;
                text-align: center;
            }
        }
    </style>
</head>
<body>
    <header class="site-header">
        <div class="container site-header__inner">
            <div class="brand">
                <a class="brand__title" href="{{ auth()->check() ? route('dashboard') : route('login') }}">Car Rental System</a>
                <span class="brand__subtitle">Gestión de autos, clientes, alquileres y pagos</span>
            </div>

            @auth
                <nav class="nav">
                    <a href="{{ route('dashboard') }}" class="{{ request()->routeIs('dashboard') ? 'active' : '' }}">Inicio</a>
                    <a href="{{ route('autos.index') }}" class="{{ request()->routeIs('autos.*') ? 'active' : '' }}">Autos</a>
                    <a href="{{ route('clientes.index') }}" class="{{ request()->routeIs('clientes.*') ? 'active' : '' }}">Clientes</a>
                    <a href="{{ route('alquileres.index') }}" class="{{ request()->routeIs('alquileres.*') ? 'active' : '' }}">Alquileres</a>
                    <a href="{{ route('pagos.index') }}" class="{{ request()->routeIs('pagos.*') ? 'active' : '' }}">Pagos</a>
                </nav>

                <button type="button" class="theme-toggle" id="theme-toggle">Modo oscuro</button>

                <div class="user-box">
                    <span>{{ auth()->user()->name }} | {{ auth()->user()->role?->description ?? 'Sin rol' }}</span>
                    <form method="POST" action="{{ route('logout') }}">
                        @csrf
                        <button type="submit" class="btn-secondary">Cerrar sesión</button>
                    </form>
                </div>
            @endauth
        </div>
    </header>

    <main class="content">
        <div class="container">
            @if (session('success'))
                <div class="alert alert-success">{{ session('success') }}</div>
            @endif

            @if (session('error'))
                <div class="alert alert-error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="alert alert-error">
                    <strong>Se encontraron errores en el formulario.</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @yield('content')
        </div>
    </main>
    <script>
        (() => {
            const root = document.documentElement;
            const toggle = document.getElementById('theme-toggle');
            const savedTheme = localStorage.getItem('theme') || 'light';

            const applyTheme = (theme) => {
                // Persistimos el tema para no resetearlo cada vez que el usuario recarga.
                root.setAttribute('data-theme', theme);

                if (toggle) {
                    toggle.textContent = theme === 'dark' ? 'Modo claro' : 'Modo oscuro';
                }
            };

            applyTheme(savedTheme);

            toggle?.addEventListener('click', () => {
                const nextTheme = root.getAttribute('data-theme') === 'dark' ? 'light' : 'dark';
                localStorage.setItem('theme', nextTheme);
                applyTheme(nextTheme);
            });
        })();
    </script>
</body>
</html>
