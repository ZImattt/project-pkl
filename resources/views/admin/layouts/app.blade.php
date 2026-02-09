<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dashboard Admin - Global Intermedia')</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        :root {
            --primary-red: #cc0000;
            --primary-dark: #1a1a2e;
            --primary-blue: #16213e;
            --accent-color: #0f3460;
            --light-gray: #f8f9fa;
            --medium-gray: #e9ecef;
            --dark-gray: #343a40;
            --sidebar-width: 250px;
            --sidebar-collapsed: 70px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            overflow-x: hidden;
            color: #333;
            min-height: 100vh;
            position: relative;
        }
        
        /* Sidebar Styles */
        #sidebar {
            width: var(--sidebar-width);
            background: linear-gradient(180deg, var(--primary-dark) 0%, var(--primary-blue) 100%);
            color: white;
            height: 100vh;
            position: fixed;
            left: 0;
            top: 0;
            z-index: 1050;
            transition: all 0.3s ease;
            box-shadow: 3px 0 15px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            transform: translateX(0);
        }
        
        #sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        #sidebar.collapsed .sidebar-header h3,
        #sidebar.collapsed .nav-text,
        #sidebar.collapsed .user-details {
            display: none;
        }
        
        #sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 15px 0;
        }
        
        #sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
        }
        
        #sidebar.collapsed .nav-badge {
            display: none;
        }
        
        #sidebar.collapsed .user-info {
            justify-content: center;
        }
        
        .sidebar-header {
            padding: 20px 15px;
            background-color: rgba(0, 0, 0, 0.2);
            border-bottom: 1px solid rgba(255, 255, 255, 0.1);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .sidebar-header h3 {
            margin: 0;
            font-size: 1.3rem;
            font-weight: 600;
            color: white;
            transition: opacity 0.3s;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .logo-icon {
            font-size: 1.8rem;
            color: white;
            background-color: var(--primary-red);
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
        }
        
        .toggle-btn {
            background: rgba(255, 255, 255, 0.1);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 4px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.3s;
        }
        
        .toggle-btn:hover {
            background: rgba(255, 255, 255, 0.2);
        }
        
        .sidebar-content {
            padding: 20px 0;
            flex: 1;
            overflow-y: auto;
        }
        
        .sidebar-content::-webkit-scrollbar {
            width: 5px;
        }
        
        .sidebar-content::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.05);
        }
        
        .sidebar-content::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.2);
            border-radius: 10px;
        }
        
        .nav-item {
            margin-bottom: 5px;
        }
        
        .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 12px 20px;
            display: flex;
            align-items: center;
            gap: 12px;
            text-decoration: none;
            transition: all 0.3s;
            position: relative;
            border-left: 3px solid transparent;
        }
        
        .nav-link:hover, 
        .nav-link.active {
            background-color: rgba(255, 255, 255, 0.1);
            color: white;
            border-left-color: var(--primary-red);
        }
        
        .nav-icon {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        .nav-text {
            font-size: 0.95rem;
            font-weight: 500;
            transition: opacity 0.3s;
        }
        
        .nav-badge {
            margin-left: auto;
            background-color: var(--primary-red);
            color: white;
            font-size: 0.7rem;
            padding: 2px 8px;
            border-radius: 10px;
        }
        
        .sidebar-footer {
            padding: 15px;
            background-color: rgba(0, 0, 0, 0.2);
            border-top: 1px solid rgba(255, 255, 255, 0.1);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background-color: var(--primary-red);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }
        
        .user-details {
            flex-grow: 1;
            transition: opacity 0.3s;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.9rem;
            color: white;
            margin-bottom: 2px;
        }
        
        .user-role {
            font-size: 0.75rem;
            color: rgba(255, 255, 255, 0.7);
        }
        
        .logout-btn {
            background: transparent;
            border: none;
            color: rgba(255, 255, 255, 0.7);
            cursor: pointer;
            font-size: 1.1rem;
            transition: color 0.3s;
        }
        
        .logout-btn:hover {
            color: white;
        }
        
        /* Main Content */
        #main-content {
            margin-left: var(--sidebar-width);
            padding: 20px;
            transition: all 0.3s ease;
            min-height: 100vh;
            width: calc(100% - var(--sidebar-width));
        }
        
        #main-content.expanded {
            margin-left: var(--sidebar-collapsed);
            width: calc(100% - var(--sidebar-collapsed));
        }
        
        .card-custom {
            background: white;
            border-radius: 12px;
            border: none;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
            transition: all 0.3s ease;
            overflow: hidden;
            margin-bottom: 20px;
            height: 100%;
        }
        
        .card-custom:hover {
            box-shadow: 0 6px 18px rgba(0, 0, 0, 0.12);
            transform: translateY(-2px);
        }
        
        .card-header-custom {
            background-color: white;
            border-bottom: 2px solid var(--medium-gray);
            padding: 15px 20px;
            font-weight: 600;
            color: var(--primary-dark);
        }
        
        .card-body {
            padding: 20px;
        }
        
        .stat-icon-container {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 60px;
            height: 60px;
            border-radius: 12px;
            margin-bottom: 15px;
        }
        
        .stat-icon {
            font-size: 1.8rem;
        }
        
        .stat-value {
            font-size: 2.2rem;
            font-weight: 700;
            margin-bottom: 5px;
            color: var(--dark-gray);
        }
        
        .stat-label {
            font-size: 0.9rem;
            color: #6c757d;
            font-weight: 500;
        }
        
        .quick-stat-item {
            background: white;
            border-radius: 10px;
            padding: 15px;
            text-align: center;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.05);
            transition: all 0.3s ease;
            height: 100%;
        }
        
        .quick-stat-item:hover {
            transform: translateY(-3px);
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
        }
        
        .quick-stat-icon {
            font-size: 2rem;
            margin-bottom: 10px;
        }
        
        .quick-stat-value {
            font-size: 1.8rem;
            font-weight: 700;
            margin-bottom: 5px;
        }
        
        .quick-stat-label {
            font-size: 0.85rem;
            color: #6c757d;
        }
        
        .activity-item {
            padding: 12px 0;
            border-bottom: 1px solid var(--medium-gray);
            display: flex;
            align-items: center;
            gap: 12px;
        }
        
        .activity-item:last-child {
            border-bottom: none;
        }
        
        .activity-icon {
            width: 40px;
            height: 40px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
            flex-shrink: 0;
        }
        
        .activity-content {
            flex: 1;
        }
        
        .activity-title {
            font-weight: 600;
            margin-bottom: 3px;
        }
        
        .activity-time {
            font-size: 0.8rem;
            color: #6c757d;
        }
        
        .badge {
            font-weight: 500;
            padding: 5px 10px;
            font-size: 0.85rem;
        }
        
        .btn-outline-custom {
            background-color: transparent;
            border: 1px solid var(--primary-red);
            color: var(--primary-red);
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .btn-outline-custom:hover {
            background-color: var(--primary-red);
            color: white;
            transform: translateY(-2px);
        }
        
        .btn-primary-custom {
            background-color: var(--primary-red);
            border-color: var(--primary-red);
            color: white;
            font-weight: 500;
            padding: 8px 20px;
            border-radius: 6px;
            transition: all 0.3s;
        }
        
        .btn-primary-custom:hover {
            background-color: #b30000;
            border-color: #b30000;
            color: white;
            transform: translateY(-2px);
            box-shadow: 0 4px 8px rgba(204, 0, 0, 0.2);
        }
        
        /* Mobile Menu Button */
        .mobile-menu-btn {
            display: none;
            background-color: var(--primary-red);
            color: white;
            border: none;
            width: 40px;
            height: 40px;
            border-radius: 8px;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            z-index: 1040;
            position: fixed;
            top: 20px;
            left: 20px;
        }
        
        /* Sidebar Overlay for Mobile */
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }
        
        .sidebar-overlay.active {
            display: block;
        }
        
        /* Responsive Design */
        @media (max-width: 992px) {
            #sidebar {
                transform: translateX(-100%);
                transition: transform 0.3s ease;
                width: 280px;
            }
            
            #sidebar.mobile-open {
                transform: translateX(0);
                box-shadow: 3px 0 15px rgba(0, 0, 0, 0.3);
            }
            
            #sidebar.collapsed {
                width: 280px;
            }
            
            #sidebar.collapsed.mobile-open {
                width: 280px;
            }
            
            #main-content {
                margin-left: 0;
                width: 100%;
                padding: 20px;
            }
            
            #main-content.expanded {
                margin-left: 0;
                width: 100%;
            }
            
            .mobile-menu-btn {
                display: flex !important;
            }
            
            .sidebar-header .toggle-btn {
                display: none;
            }
            
            .dashboard-header {
                margin-top: 60px;
                flex-direction: column;
                align-items: flex-start;
                gap: 15px;
            }
        }
        
        @media (max-width: 768px) {
            .mobile-menu-btn {
                top: 15px;
                left: 15px;
                width: 36px;
                height: 36px;
                font-size: 1.1rem;
            }
            
            .dashboard-header {
                margin-top: 60px;
            }
            
            #sidebar {
                width: 250px;
            }
        }
        
        @media (max-width: 576px) {
            #main-content {
                padding: 15px;
            }
            
            .mobile-menu-btn {
                top: 12px;
                left: 12px;
                width: 34px;
                height: 34px;
                font-size: 1rem;
            }
        }
        
        /* Animations */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(20px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.5s ease-out;
        }
        
        .text-primary { color: var(--primary-red) !important; }
        .bg-primary-light { background-color: rgba(204, 0, 0, 0.1) !important; }
        .bg-success-light { background-color: rgba(40, 167, 69, 0.1) !important; }
        .bg-warning-light { background-color: rgba(255, 193, 7, 0.1) !important; }
        .bg-info-light { background-color: rgba(23, 162, 184, 0.1) !important; }
        .bg-danger-light { background-color: rgba(220, 53, 69, 0.1) !important; }
        
        .icon-primary { color: var(--primary-red) !important; }
        .icon-success { color: #28a745 !important; }
        .icon-warning { color: #ffc107 !important; }
        .icon-danger { color: #dc3545 !important; }
        .icon-info { color: #17a2b8 !important; }
    </style>
    
    @stack('styles')
</head>
<body>
    <!-- Sidebar Overlay for Mobile -->
    <div class="sidebar-overlay" id="sidebarOverlay"></div>

    <!-- Mobile Menu Button -->
    <button class="mobile-menu-btn" id="mobileMenuToggle">
        <i class="fas fa-bars"></i>
    </button>

    <!-- Sidebar -->
    <nav id="sidebar">
        <div class="sidebar-header">
            <div class="logo">
                <div class="logo-icon">
                    <i class="fas fa-tachometer-alt"></i>
                </div>
                <h3>Admin Global</h3>
            </div>
            <button class="toggle-btn" id="sidebarToggle">
                <i class="fas fa-chevron-left"></i>
            </button>
        </div>
        
        <div class="sidebar-content">
            <ul class="nav flex-column">
                <li class="nav-item">
                    <a href="{{ route('admin.dashboard') }}" class="nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                        <i class="fas fa-tachometer-alt nav-icon"></i>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.registrations.index') }}" class="nav-link {{ request()->routeIs('admin.registrations.*') ? 'active' : '' }}">
                        <i class="fas fa-users nav-icon"></i>
                        <span class="nav-text">Data Pendaftaran</span>
                        @if(isset($totalPending) && $totalPending > 0)
                            <span class="nav-badge">{{ $totalPending }}</span>
                        @endif
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.peserta.pkl') }}" class="nav-link {{ request()->routeIs('admin.peserta.pkl') ? 'active' : '' }}">
                        <i class="fas fa-user-graduate nav-icon"></i>
                        <span class="nav-text">Peserta PKL</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a href="{{ route('admin.export') }}" class="nav-link {{ request()->routeIs('admin.export*') ? 'active' : '' }}">
                        <i class="fas fa-file-export nav-icon"></i>
                        <span class="nav-text">Export Data</span>
                    </a>
                </li>
            </ul>
        </div>
        
        <div class="sidebar-footer">
            <div class="user-info">
                <div class="user-avatar">
                    <i class="fas fa-user"></i>
                </div>
                <div class="user-details">
                    <div class="user-name">Admin Global</div>
                    <div class="user-role">Administrator</div>
                </div>
                <form id="sidebar-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">
                    @csrf
                </form>
                <button class="logout-btn" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i>
                </button>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main id="main-content">
        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
    
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const sidebar = document.getElementById('sidebar');
            const mainContent = document.getElementById('main-content');
            const sidebarToggle = document.getElementById('sidebarToggle');
            const mobileMenuToggle = document.getElementById('mobileMenuToggle');
            const sidebarOverlay = document.getElementById('sidebarOverlay');
            
            // Desktop sidebar toggle
            sidebarToggle.addEventListener('click', function() {
                sidebar.classList.toggle('collapsed');
                mainContent.classList.toggle('expanded');
                
                const icon = this.querySelector('i');
                if (sidebar.classList.contains('collapsed')) {
                    icon.classList.remove('fa-chevron-left');
                    icon.classList.add('fa-chevron-right');
                } else {
                    icon.classList.remove('fa-chevron-right');
                    icon.classList.add('fa-chevron-left');
                }
            });
            
            // Mobile menu toggle
            mobileMenuToggle.addEventListener('click', function() {
                sidebar.classList.add('mobile-open');
                sidebarOverlay.classList.add('active');
                document.body.style.overflow = 'hidden';
            });
            
            // Close sidebar on overlay click
            sidebarOverlay.addEventListener('click', function() {
                sidebar.classList.remove('mobile-open');
                this.classList.remove('active');
                document.body.style.overflow = 'auto';
            });
            
            // Close sidebar on escape key
            document.addEventListener('keydown', function(e) {
                if (e.key === 'Escape' && sidebar.classList.contains('mobile-open')) {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Close sidebar when clicking outside on mobile
            document.addEventListener('click', function(event) {
                if (window.innerWidth < 992) {
                    const isClickInsideSidebar = sidebar.contains(event.target);
                    const isClickOnMobileMenuBtn = mobileMenuToggle.contains(event.target);
                    const isClickOnOverlay = sidebarOverlay.contains(event.target);
                    
                    if (!isClickInsideSidebar && !isClickOnMobileMenuBtn && !isClickOnOverlay && 
                        sidebar.classList.contains('mobile-open')) {
                        sidebar.classList.remove('mobile-open');
                        sidebarOverlay.classList.remove('active');
                        document.body.style.overflow = 'auto';
                    }
                }
            });
            
            // Adjust for mobile on load
            if (window.innerWidth < 992) {
                sidebar.classList.remove('collapsed');
                mainContent.classList.remove('expanded');
            }
            
            // Handle window resize
            window.addEventListener('resize', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.remove('mobile-open');
                    sidebarOverlay.classList.remove('active');
                    document.body.style.overflow = 'auto';
                }
            });
            
            // Animations
            const cards = document.querySelectorAll('.fade-in');
            cards.forEach((card, index) => {
                card.style.animationDelay = `${index * 0.1}s`;
            });
        });
    </script>
    
    @stack('scripts')
</body>
</html>