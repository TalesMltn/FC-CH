<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Concretera Huancayo - @yield('title', 'Dashboard')</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff6600;
            --primary-dark: #ff3300;
            --dark: #111;
            --dark-light: #1e1e1e;
            --gray: #333;
            --light: #f8f9fa;
            --text-light: #ccc;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; font-family: 'Montserrat', sans-serif; }

        body {
            background: var(--dark);
            color: var(--light);
            display: flex;
            height: 100vh;
            overflow: hidden;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background: var(--gray);
            padding: 20px;
            box-shadow: 5px 0 15px rgba(0,0,0,0.5);
            position: fixed;
            height: 100%;
            overflow-y: auto;
            z-index: 1000;
        }

        .logo {
            text-align: center;
            margin-bottom: 40px;
            padding: 20px 0;
            border-bottom: 1px solid rgba(255,102,0,0.3);
        }

        .logo h2 {
            color: var(--primary);
            font-size: 28px;
            font-weight: 700;
            text-shadow: 0 0 15px rgba(255,102,0,0.6);
        }

        .menu-item {
            display: block;
            padding: 14px 20px;
            color: var(--text-light);
            text-decoration: none;
            border-radius: 10px;
            margin-bottom: 8px;
            transition: all 0.3s;
            font-weight: 600;
        }

        .menu-item i {
            margin-right: 12px;
            width: 20px;
            text-align: center;
        }

        .menu-item:hover, .menu-item.active {
            background: var(--primary);
            color: white;
            transform: translateX(5px);
            box-shadow: 0 5px 15px rgba(255,102,0,0.4);
        }

        /* Main content */
        .main-content {
            margin-left: 260px;
            width: calc(100% - 260px);
            padding: 30px;
            overflow-y: auto;
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 30px;
            padding-bottom: 20px;
            border-bottom: 1px solid rgba(255,102,0,0.2);
        }

        .header h1 {
            color: var(--primary);
            font-size: 32px;
        }

        .user-info {
            display: flex;
            align-items: center;
            color: var(--text-light);
        }

        .user-info .name {
            font-weight: 600;
            margin-right: 15px;
        }

        .user-info .role {
            background: var(--primary);
            padding: 5px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 700;
        }

        .logout-btn {
            background: var(--primary-dark);
            color: white;
            padding: 10px 20px;
            border-radius: 50px;
            text-decoration: none;
            font-weight: 600;
            transition: all 0.3s;
            margin-left: 20px;
        }

        .logout-btn:hover {
            background: #cc0000;
            transform: translateY(-3px);
        }

        /* Responsive */
        @media (max-width: 992px) {
            .sidebar {
                width: 80px;
                padding: 20px 10px;
            }
            .sidebar .logo h2, .sidebar .menu-item span {
                display: none;
            }
            .main-content {
                margin-left: 80px;
                width: calc(100% - 80px);
            }
        }
    </style>
</head>
<body>
    <!-- Sidebar -->
    <aside class="sidebar">
        <div class="logo">
            <h2>Concretera<br>Huancayo</h2>
        </div>
        <nav>
            <a href="{{ route('home') }}" class="menu-item {{ request()->is('home') ? 'active' : '' }}">
                <i class="fas fa-home"></i><span>Dashboard</span>
            </a>
            <a href="/categories" class="menu-item {{ request()->is('categories*') ? 'active' : '' }}">
                <i class="fas fa-tags"></i><span>Categorías</span>
            </a>
            <a href="/persons" class="menu-item {{ request()->is('persons*') ? 'active' : '' }}">
                <i class="fas fa-users"></i><span>Personas</span>
            </a>
            <a href="/contracts" class="menu-item {{ request()->is('contracts*') ? 'active' : '' }}">
                <i class="fas fa-file-contract"></i><span>Contratos</span>
            </a>
            <a href="/transactions" class="menu-item {{ request()->is('transactions*') ? 'active' : '' }}">
                <i class="fas fa-exchange-alt"></i><span>Transacciones</span>
            </a>
            <a href="/payrolls" class="menu-item {{ request()->is('payrolls*') ? 'active' : '' }}">
                <i class="fas fa-money-check-alt"></i><span>Nómina</span>
            </a>
            <a href="/loans" class="menu-item {{ request()->is('loans*') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-usd"></i><span>Préstamos</span>
            </a>

            <!-- REPORTES -->
            <a href="{{ route('reports.index') }}" class="menu-item {{ request()->is('reports*') ? 'active' : '' }}">
                <i class="fas fa-chart-pie"></i><span>Reportes Generales</span>
            </a>
            <a href="{{ route('reports.sales') }}" class="menu-item {{ request()->is('reports/sales') ? 'active' : '' }}">
                <i class="fas fa-file-invoice-dollar"></i><span>Reporte de Ventas</span>
            </a>
            <a href="{{ route('reports.payments') }}" class="menu-item {{ request()->is('reports/payments') ? 'active' : '' }}">
                <i class="fas fa-money-bill-wave"></i><span>Reporte de Cobros</span>
            </a>
            <a href="{{ route('reports.payroll') }}" class="menu-item {{ request()->is('reports/payroll') ? 'active' : '' }}">
                <i class="fas fa-users-cog"></i><span>Reporte de Nómina</span>
            </a>
            <a href="{{ route('reports.loans') }}" class="menu-item {{ request()->is('reports/loans') ? 'active' : '' }}">
                <i class="fas fa-hand-holding-usd"></i><span>Reporte de Préstamos</span>
            </a>

            <a href="/exports" class="menu-item {{ request()->is('exports*') ? 'active' : '' }}">
                <i class="fas fa-file-export"></i><span>Exportar</span>
            </a>

            <a href="{{ route('exports.index') }}" class="menu-item {{ request()->is('exports*') ? 'active' : '' }}">
                <i class="fas fa-file-export"></i><span>Exportar Reportes</span>
            </a>

        </nav>
    </aside>

    <!-- Main Content -->
    <div class="main-content">
        <header class="header">
            <h1>@yield('page-title', 'Dashboard')</h1>
            <div class="user-info">
                <div>
                    <div class="name">{{ auth()->user()->name }}</div>
                </div>
                <span class="role">
                    {{ auth()->user()->role == 'developer' ? 'Desarrollador' : (auth()->user()->role == 'admin' ? 'Administrador' : 'Usuario') }}
                </span>
                <a href="{{ route('logout') }}" class="logout-btn" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Cerrar Sesión
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                    @csrf
                </form>
            </div>
        </header>

        <main>
            @yield('content')
        </main>
    </div>
</body>
</html>