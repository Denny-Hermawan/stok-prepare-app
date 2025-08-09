<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>{{ config('app.name', 'Stok Prepare App') }}</title>

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Font Awesome -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css" rel="stylesheet">
    <!-- Google Fonts -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        :root {
            --primary-color: #4f46e5;
            --primary-dark: #3730a3;
            --secondary-color: #f8fafc;
            --accent-color: #06b6d4;
            --text-primary: #1e293b;
            --text-secondary: #64748b;
            --border-color: #e2e8f0;
            --sidebar-width: 280px;
            --header-height: 70px;
            --shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            --shadow-lg: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }

        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }

        body {
            font-family: 'Inter', sans-serif;
            background: linear-gradient(135deg, #f8fafc 0%, #e2e8f0 100%);
            color: var(--text-primary);
            min-height: 100vh;
        }

        /* Header Navbar */
        .modern-navbar {
            background: linear-gradient(135deg, var(--primary-color) 0%, var(--primary-dark) 100%);
            box-shadow: var(--shadow-lg);
            border: none;
            height: var(--header-height);
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            z-index: 1050;
        }

        .modern-navbar .navbar-brand {
            font-weight: 700;
            font-size: 1.25rem;
            color: white !important;
            text-decoration: none;
            display: flex;
            align-items: center;
        }

        .modern-navbar .navbar-brand i {
            background: rgba(255, 255, 255, 0.2);
            padding: 8px;
            border-radius: 10px;
            margin-right: 12px;
            font-size: 1.1rem;
        }

        /* Sidebar */
        .modern-sidebar {
            background: white;
            width: var(--sidebar-width);
            height: calc(100vh - var(--header-height));
            position: fixed;
            top: var(--header-height);
            left: 0;
            transform: translateX(-100%);
            transition: transform 0.3s ease;
            box-shadow: var(--shadow-lg);
            border-right: 1px solid var(--border-color);
            z-index: 1049;
            overflow-y: auto;
        }

        .modern-sidebar.show {
            transform: translateX(0);
        }

        .sidebar-nav {
            padding: 24px 16px;
        }

        .nav-item {
            margin-bottom: 8px;
        }

        .nav-link {
            color: var(--text-secondary);
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            font-weight: 500;
            font-size: 0.95rem;
            transition: all 0.3s ease;
            position: relative;
            overflow: hidden;
        }

        .nav-link:hover {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .nav-link.active {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            box-shadow: var(--shadow);
        }

        .nav-link i {
            width: 20px;
            margin-right: 12px;
            font-size: 1rem;
        }

        /* Main Content */
        .main-content {
            margin-left: 0;
            padding: calc(var(--header-height) + 24px) 24px 24px;
            min-height: 100vh;
            transition: margin-left 0.3s ease;
        }

        /* Cards */
        .modern-card {
            background: white;
            border-radius: 16px;
            box-shadow: var(--shadow);
            border: 1px solid var(--border-color);
            overflow: hidden;
            transition: all 0.3s ease;
        }

        .modern-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
        }

        .modern-card .card-header {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
            border-bottom: 1px solid var(--border-color);
            padding: 20px 24px;
        }

        .modern-card .card-body {
            padding: 24px;
        }

        /* Stats Cards */
        .stats-card {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
            color: white;
            border: none;
            position: relative;
            overflow: hidden;
        }

        .stats-card::before {
            content: '';
            position: absolute;
            top: -50%;
            right: -50%;
            width: 100%;
            height: 100%;
            background: radial-gradient(circle, rgba(255,255,255,0.1) 0%, transparent 70%);
            transform: rotate(45deg);
        }

        .stats-card .card-body {
            position: relative;
            z-index: 1;
        }


        .card-footer {
            background-color: rgba(0, 0, 0, 0.03) !important;
            border-top: 1px solid rgba(0, 0, 0, 0.125);
        }

        /* Buttons */
        .btn-modern {
            padding: 12px 24px;
            border-radius: 12px;
            font-weight: 600;
            font-size: 0.9rem;
            border: none;
            transition: all 0.3s ease;
            display: inline-flex;
            align-items: center;
            gap: 8px;
        }

        .btn-modern:hover {
            transform: translateY(-2px);
            box-shadow: var(--shadow);
        }

        .btn-primary.btn-modern {
            background: linear-gradient(135deg, var(--primary-color), var(--accent-color));
        }

        /* Tables */
        .modern-table {
            border-radius: 12px;
            overflow: hidden;
            box-shadow: var(--shadow);
        }

        .modern-table thead {
            background: linear-gradient(135deg, #f8fafc 0%, #f1f5f9 100%);
        }

        .modern-table thead th {
            border: none;
            padding: 16px;
            font-weight: 600;
            color: var(--text-primary);
            font-size: 0.9rem;
        }

        .modern-table tbody td {
            padding: 16px;
            border-color: var(--border-color);
            vertical-align: middle;
        }

        .modern-table tbody tr:hover {
            background-color: #f8fafc;
        }

        /* Alerts */
        .alert-modern {
            border: none;
            border-radius: 12px;
            padding: 16px 20px;
            margin-bottom: 20px;
        }

        /* Mobile Styles */
        @media (max-width: 768px) {
            :root {
                --sidebar-width: 100%;
            }

            .main-content {
                padding: calc(var(--header-height) + 16px) 16px 16px;
            }

            .modern-card .card-body {
                padding: 16px;
            }

            .modern-card .card-header {
                padding: 16px;
            }
        }

        @media (min-width: 769px) {
            .modern-sidebar {
                transform: translateX(0);
            }

            .main-content {
                margin-left: var(--sidebar-width);
            }
        }

        /* Overlay for mobile */
        .sidebar-overlay {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.5);
            z-index: 1048;
            opacity: 0;
            visibility: hidden;
            transition: all 0.3s ease;
        }

        .sidebar-overlay.show {
            opacity: 1;
            visibility: visible;
        }

        /* Loading animation */
        .loading-spinner {
            display: inline-block;
            width: 20px;
            height: 20px;
            border: 2px solid #f3f3f3;
            border-top: 2px solid var(--primary-color);
            border-radius: 50%;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            0% { transform: rotate(0deg); }
            100% { transform: rotate(360deg); }
        }

        /* Custom scrollbar */
        .modern-sidebar::-webkit-scrollbar {
            width: 6px;
        }

        .modern-sidebar::-webkit-scrollbar-track {
            background: #f1f1f1;
        }

        .modern-sidebar::-webkit-scrollbar-thumb {
            background: var(--primary-color);
            border-radius: 3px;
        }

        .modern-sidebar::-webkit-scrollbar-thumb:hover {
            background: var(--primary-dark);
        }
    </style>
</head>
<body>
    @include('layouts.navbar')
    @include('layouts.sidebar')

    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <main class="main-content">
        @if (session('success'))
            <div class="alert alert-success alert-modern alert-dismissible fade show" role="alert">
                <i class="fas fa-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-modern alert-dismissible fade show" role="alert">
                <i class="fas fa-exclamation-circle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
            </div>
        @endif

        @yield('content')
    </main>

    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        // Sidebar toggle functionality
        document.addEventListener('DOMContentLoaded', function() {
            const sidebarToggle = document.getElementById('sidebarToggle');
            const sidebar = document.getElementById('modernSidebar');
            const overlay = document.getElementById('sidebarOverlay');

            function toggleSidebar() {
                sidebar.classList.toggle('show');
                overlay.classList.toggle('show');
                document.body.classList.toggle('sidebar-open');
            }

            function closeSidebar() {
                sidebar.classList.remove('show');
                overlay.classList.remove('show');
                document.body.classList.remove('sidebar-open');
            }

            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', toggleSidebar);
            }

            if (overlay) {
                overlay.addEventListener('click', closeSidebar);
            }

            // Close sidebar when clicking nav links on mobile
            const navLinks = document.querySelectorAll('.nav-link');
            navLinks.forEach(link => {
                link.addEventListener('click', function() {
                    if (window.innerWidth <= 768) {
                        closeSidebar();
                    }
                });
            });

            // Auto-close sidebar on window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth > 768) {
                    closeSidebar();
                }
            });
        });
    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    @yield('scripts')
</body>
</html>
