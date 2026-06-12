<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', 'Admin - Global Intermedia')</title>
    
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    
    <style>
        /* ========== SIMPLIFIED DESIGN SYSTEM ========== */
        :root {
            --primary: #2c3e50;
            --primary-light: #34495e;
            --accent: #e74c3c;
            --accent-hover: #c0392b;
            --bg-sidebar: #1a252f;
            --text-sidebar: #ecf0f1;
            --text-muted-sidebar: #95a5a6;
            --border-light: rgba(255,255,255,0.08);
            --card-shadow: 0 2px 8px rgba(0,0,0,0.05);
            --transition: all 0.2s ease;
            --sidebar-width: 260px;
            --sidebar-collapsed: 72px;
        }
        
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
        }
        
        body {
            font-family: 'Inter', system-ui, -apple-system, 'Segoe UI', Roboto, sans-serif;
            background: #f4f6f9;
            color: #2c3e50;
            overflow-x: hidden;
        }
        
        /* SIDEBAR - SIMPLE & CLEAN */
        #sidebar {
            position: fixed;
            left: 0;
            top: 0;
            width: var(--sidebar-width);
            height: 100vh;
            background: var(--bg-sidebar);
            color: var(--text-sidebar);
            z-index: 1050;
            transition: width 0.2s ease;
            display: flex;
            flex-direction: column;
            box-shadow: 1px 0 10px rgba(0,0,0,0.08);
            overflow: hidden;
        }
        
        #sidebar.collapsed {
            width: var(--sidebar-collapsed);
        }
        
        #sidebar.collapsed .logo-text,
        #sidebar.collapsed .nav-text,
        #sidebar.collapsed .user-details,
        #sidebar.collapsed .logout-text {
            display: none;
        }
        
        #sidebar.collapsed .sidebar-header {
            justify-content: center;
            padding: 18px 0;
        }
        
        #sidebar.collapsed .nav-link {
            justify-content: center;
            padding: 12px 0;
        }
        
        #sidebar.collapsed .user-info {
            justify-content: center;
        }
        
        #sidebar.collapsed .submenu {
            display: none !important;
        }
        
        /* Header sidebar */
        .sidebar-header {
            padding: 18px 20px;
            border-bottom: 1px solid var(--border-light);
            display: flex;
            align-items: center;
            justify-content: space-between;
            min-height: 70px;
        }
        
        .logo {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .logo-icon {
            background: var(--accent);
            width: 34px;
            height: 34px;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 1.2rem;
            color: white;
        }
        
        .logo-text {
            font-weight: 600;
            font-size: 1rem;
            letter-spacing: -0.2px;
        }
        
        .logo-text span:first-child { color: white; }
        .logo-text span:last-child { color: var(--accent); }
        
        .toggle-btn {
            background: rgba(255,255,255,0.1);
            border: none;
            color: white;
            width: 30px;
            height: 30px;
            border-radius: 6px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: 0.2s;
        }
        
        .toggle-btn:hover {
            background: rgba(255,255,255,0.2);
        }
        
        /* Navigation */
        .sidebar-content {
            flex: 1;
            padding: 20px 12px;
            overflow-y: auto;
        }
        
        .nav {
            list-style: none;
            padding: 0;
        }
        
        .nav-item {
            margin-bottom: 4px;
        }
        
        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 10px 14px;
            border-radius: 10px;
            color: var(--text-sidebar);
            text-decoration: none;
            transition: 0.2s;
            cursor: pointer;
            font-weight: 450;
            font-size: 0.9rem;
        }
        
        .nav-link:hover,
        .nav-link.active {
            background: rgba(255,255,255,0.08);
            color: white;
        }
        
        .nav-icon {
            font-size: 1.2rem;
            width: 24px;
            text-align: center;
        }
        
        .nav-text {
            flex: 1;
        }
        
        .nav-badge {
            background: var(--accent);
            color: white;
            font-size: 0.7rem;
            font-weight: 600;
            padding: 2px 8px;
            border-radius: 30px;
            min-width: 22px;
            text-align: center;
        }
        
        .dropdown-icon {
            font-size: 0.75rem;
            transition: transform 0.2s;
            opacity: 0.7;
        }
        
        .nav-link[aria-expanded="true"] .dropdown-icon {
            transform: rotate(180deg);
        }
        
        /* Submenu sederhana */
        .submenu {
            list-style: none;
            padding-left: 0;
            margin: 4px 0 4px 36px;
            max-height: 0;
            overflow: hidden;
            transition: max-height 0.25s ease;
        }
        
        .submenu.show {
            max-height: 180px;
        }
        
        .submenu .nav-link {
            padding: 8px 14px;
            font-size: 0.85rem;
        }
        
        /* Sidebar footer - user & logout */
        .sidebar-footer {
            padding: 16px 16px 20px;
            border-top: 1px solid var(--border-light);
        }
        
        .user-info {
            display: flex;
            align-items: center;
            gap: 12px;
            margin-bottom: 14px;
        }
        
        .user-avatar {
            width: 38px;
            height: 38px;
            background: var(--accent);
            border-radius: 10px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: white;
        }
        
        .user-details {
            overflow: hidden;
        }
        
        .user-name {
            font-weight: 600;
            font-size: 0.85rem;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }
        
        .user-role {
            font-size: 0.7rem;
            opacity: 0.7;
        }
        
        .logout-btn-sidebar {
            background: rgba(231, 76, 60, 0.15);
            border: none;
            width: 100%;
            padding: 10px;
            border-radius: 10px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 10px;
            font-size: 0.85rem;
            transition: 0.2s;
            cursor: pointer;
        }
        
        .logout-btn-sidebar:hover {
            background: var(--accent);
            color: white;
        }
        
        /* MAIN CONTENT */
        #main-content {
            margin-left: var(--sidebar-width);
            padding: 24px 28px;
            transition: margin-left 0.2s ease;
            min-height: 100vh;
        }
        
        #main-content.expanded {
            margin-left: var(--sidebar-collapsed);
        }
        
        /* Mobile & Overlay */
        .mobile-menu-btn {
            display: none;
            position: fixed;
            top: 18px;
            left: 18px;
            width: 44px;
            height: 44px;
            background: var(--accent);
            border: none;
            border-radius: 12px;
            color: white;
            font-size: 1.3rem;
            z-index: 1060;
            cursor: pointer;
            box-shadow: 0 2px 8px rgba(0,0,0,0.15);
            align-items: center;
            justify-content: center;
        }
        
        .sidebar-overlay {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0,0,0,0.5);
            z-index: 1040;
            backdrop-filter: blur(2px);
        }
        
        @media (max-width: 991px) {
            #sidebar {
                transform: translateX(-100%);
                width: 260px !important;
                transition: transform 0.2s ease;
            }
            #sidebar.mobile-open {
                transform: translateX(0);
            }
            .toggle-btn {
                display: none;
            }
            #main-content {
                margin-left: 0 !important;
                padding: 20px 16px;
            }
            .mobile-menu-btn {
                display: flex;
            }
            body.sidebar-open {
                overflow: hidden;
            }
        }
        
        /* Simple animation for badges */
        .badge-updated {
            animation: pulse 0.4s ease;
        }
        
        @keyframes pulse {
            0% { transform: scale(1); opacity: 1; }
            50% { transform: scale(1.2); background-color: #e74c3c; }
            100% { transform: scale(1); }
        }
        
        /* Toast sederhana */
        .simple-toast {
            position: fixed;
            bottom: 24px;
            right: 24px;
            background: #2c3e50;
            color: white;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 0.85rem;
            z-index: 1100;
            box-shadow: 0 4px 12px rgba(0,0,0,0.15);
            display: flex;
            align-items: center;
            gap: 12px;
            pointer-events: auto;
        }
        
        /* Print styles */
        @media print {
            #sidebar, .mobile-menu-btn, .sidebar-overlay, .logout-btn-sidebar {
                display: none !important;
            }
            #main-content {
                margin-left: 0 !important;
                padding: 0 !important;
            }
        }
        
        /* misc */
        .fade-in {
            animation: fadeIn 0.3s ease;
        }
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(6px);}
            to { opacity: 1; transform: translateY(0);}
        }
    </style>
    
    @stack('styles')
</head>
<body>

<div class="sidebar-overlay" id="sidebarOverlay"></div>
<button class="mobile-menu-btn" id="mobileMenuToggle"><i class="fas fa-bars"></i></button>

<nav id="sidebar">
    <div class="sidebar-header">
        <div class="logo">
            <div class="logo-icon"><i class="fas fa-globe"></i></div>
            <div class="logo-text"><span>Global</span><span>Intermedia</span></div>
        </div>
        <button class="toggle-btn" id="sidebarToggle"><i class="fas fa-chevron-left"></i></button>
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
                @php
                    use App\Models\Pendaftaran;
                    use App\Models\Kelompok;
                    $pendingIndividu = Pendaftaran::where('status', 'pending')->count();
                    $pendingKelompok = Kelompok::where('status', 'pending')->count();
                    $totalPending = $pendingIndividu + $pendingKelompok;
                    $isPendaftaranActive = request()->routeIs('admin.individu.*') || request()->routeIs('admin.kelompok.*');
                @endphp
                <a class="nav-link {{ $isPendaftaranActive ? 'active' : '' }}" onclick="toggleSubmenu(event)" role="button" aria-expanded="{{ $isPendaftaranActive ? 'true' : 'false' }}">
                    <i class="fas fa-user-plus nav-icon"></i>
                    <span class="nav-text">Pendaftaran</span>
                    <span class="nav-badge" id="badgePendaftaran" style="{{ $totalPending > 0 ? '' : 'display: none;' }}">{{ $totalPending }}</span>
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <ul class="submenu {{ $isPendaftaranActive ? 'show' : '' }}" id="pendaftaranSubmenu">
                    <li class="nav-item">
                        <a href="{{ route('admin.individu.index') }}" class="nav-link {{ request()->routeIs('admin.individu.*') ? 'active' : '' }}">
                            <i class="fas fa-user nav-icon"></i>
                            <span class="nav-text">Individu</span>
                            <span class="nav-badge" id="badgeIndividuSub" style="{{ $pendingIndividu > 0 ? '' : 'display: none;' }}">{{ $pendingIndividu }}</span>
                        </a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('admin.kelompok.index') }}" class="nav-link {{ request()->routeIs('admin.kelompok.*') ? 'active' : '' }}">
                            <i class="fas fa-users nav-icon"></i>
                            <span class="nav-text">Kelompok</span>
                            <span class="nav-badge" id="badgeKelompokSub" style="{{ $pendingKelompok > 0 ? '' : 'display: none;' }}">{{ $pendingKelompok }}</span>
                        </a>
                    </li>
                </ul>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.peserta.index') }}" class="nav-link {{ request()->routeIs('admin.peserta.*') ? 'active' : '' }}">
                    <i class="fas fa-user-graduate nav-icon"></i>
                    <span class="nav-text">Semua Peserta PKL</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a href="{{ route('admin.statistik.index') }}" class="nav-link {{ request()->routeIs('admin.statistik.*') ? 'active' : '' }}">
                    <i class="fas fa-chart-bar nav-icon"></i>
                    <span class="nav-text">Statistik</span>
                </a>
            </li>
        </ul>
    </div>
    
    <div class="sidebar-footer">
        <div class="user-info">
            <div class="user-avatar"><i class="fas fa-user-cog"></i></div>
            <div class="user-details">
                <div class="user-name">{{ Auth::user()->name ?? 'Administrator' }}</div>
                <div class="user-role">Admin</div>
            </div>
        </div>
        <form id="sidebar-logout-form" action="{{ route('admin.logout') }}" method="POST" class="d-none">@csrf</form>
        <button class="logout-btn-sidebar" onclick="event.preventDefault(); document.getElementById('sidebar-logout-form').submit();">
            <i class="fas fa-sign-out-alt"></i>
            <span class="logout-text">Logout</span>
        </button>
    </div>
</nav>

<main id="main-content">
    @yield('content')
</main>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>
<script>
    // ============================================
    // REAL-TIME & BADGE MANAGEMENT (SEDERHANA, TIDAK DIUBAH INTI)
    // ============================================
    let badgeUpdateInterval = null;
    let echoInstance = null;
    let isEchoConnected = false;
    
    let pendingCounts = {
        individu: {{ $pendingIndividu ?? 0 }},
        kelompok: {{ $pendingKelompok ?? 0 }}
    };
    
    // fetch counts via AJAX
    function fetchPendingCounts() {
        $.ajax({
            url: '{{ route("admin.individu.pending-counts") }}',
            method: 'GET',
            dataType: 'json',
            timeout: 5000,
            success: function(resp) {
                if (resp.success) {
                    updateAllBadges(resp.pending_individu, resp.pending_kelompok);
                }
            },
            error: function() { /* silent fallback */ }
        });
    }
    
    function updateAllBadges(individu, kelompok) {
        updateBadge('badgeIndividuSub', individu, pendingCounts.individu);
        updateBadge('badgeKelompokSub', kelompok, pendingCounts.kelompok);
        const total = individu + kelompok;
        const prevTotal = pendingCounts.individu + pendingCounts.kelompok;
        updateBadge('badgePendaftaran', total, prevTotal);
        pendingCounts.individu = individu;
        pendingCounts.kelompok = kelompok;
    }
    
    function updateBadge(badgeId, newCount, oldCount) {
        const badge = document.getElementById(badgeId);
        if (!badge) return;
        badge.textContent = newCount;
        if (newCount > 0) badge.style.display = '';
        else badge.style.display = 'none';
        
        if (newCount !== oldCount) {
            badge.classList.remove('badge-updated');
            void badge.offsetWidth;
            badge.classList.add('badge-updated');
            setTimeout(() => badge.classList.remove('badge-updated'), 400);
        }
    }
    
    function startPolling() {
        if (badgeUpdateInterval) return;
        fetchPendingCounts();
        badgeUpdateInterval = setInterval(fetchPendingCounts, 10000);
    }
    
    function stopPolling() {
        if (badgeUpdateInterval) {
            clearInterval(badgeUpdateInterval);
            badgeUpdateInterval = null;
        }
    }
    
    // Echo init (tidak diubah)
    function initEcho() {
        if (typeof Echo !== 'undefined' && typeof io !== 'undefined') {
            try {
                window.Echo = new Echo({
                    broadcaster: 'socket.io',
                    host: window.location.hostname + ':6001',
                    auth: { headers: { 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') } }
                });
                echoInstance = window.Echo;
                echoInstance.channel('admin-dashboard')
                    .listen('.pending-counts-updated', (data) => {
                        updateAllBadges(data.pending_individu, data.pending_kelompok);
                    })
                    .listen('.new-registration', (data) => {
                        updateAllBadges(data.pending_individu, data.pending_kelompok);
                        showSimpleNotif(data.message || 'Pendaftaran baru perlu ditinjau');
                    });
                if (echoInstance.connector && echoInstance.connector.socket) {
                    echoInstance.connector.socket.on('connect', () => { isEchoConnected = true; stopPolling(); fetchPendingCounts(); });
                    echoInstance.connector.socket.on('disconnect', () => { isEchoConnected = false; startPolling(); });
                }
                stopPolling();
            } catch(e) { startPolling(); }
        } else { startPolling(); }
    }
    
    function showSimpleNotif(msg) {
        if (Notification.permission === 'granted') {
            new Notification('Global Intermedia', { body: msg, icon: '/favicon.ico' });
        }
        // toast
        let toast = document.createElement('div');
        toast.className = 'simple-toast';
        toast.innerHTML = `<i class="fas fa-bell" style="color: #e74c3c;"></i><span>${msg}</span><button style="background:none; border:none; color:white; margin-left:10px; cursor:pointer;" onclick="this.parentElement.remove()">✕</button>`;
        document.body.appendChild(toast);
        setTimeout(() => toast.remove(), 5000);
    }
    
    // toggle submenu
    function toggleSubmenu(event) {
        event.preventDefault();
        const link = event.currentTarget;
        const submenu = document.getElementById('pendaftaranSubmenu');
        const sidebar = document.getElementById('sidebar');
        const isCollapsed = sidebar.classList.contains('collapsed') && window.innerWidth >= 992;
        
        if (isCollapsed) {
            // expand sidebar first
            const toggleBtn = document.getElementById('sidebarToggle');
            sidebar.classList.remove('collapsed');
            document.getElementById('main-content').classList.remove('expanded');
            if (toggleBtn) {
                const icon = toggleBtn.querySelector('i');
                icon.classList.remove('fa-chevron-right');
                icon.classList.add('fa-chevron-left');
            }
            setTimeout(() => {
                submenu.classList.add('show');
                link.setAttribute('aria-expanded', 'true');
            }, 80);
        } else {
            submenu.classList.toggle('show');
            const expanded = submenu.classList.contains('show');
            link.setAttribute('aria-expanded', expanded);
        }
    }
    
    // Global update dari luar
    window.updateSidebarBadge = function(individu, kelompok) {
        updateAllBadges(individu, kelompok);
    };
    
    document.addEventListener('DOMContentLoaded', function() {
        const sidebar = document.getElementById('sidebar');
        const mainContent = document.getElementById('main-content');
        const sidebarToggle = document.getElementById('sidebarToggle');
        const mobileToggle = document.getElementById('mobileMenuToggle');
        const overlay = document.getElementById('sidebarOverlay');
        
        // request notif permission
        if ('Notification' in window && Notification.permission === 'default') Notification.requestPermission();
        
        // realtime init
        if (typeof Echo !== 'undefined') initEcho();
        else startPolling();
        
        // desktop toggle
        if (sidebarToggle) {
            sidebarToggle.addEventListener('click', function() {
                if (window.innerWidth >= 992) {
                    sidebar.classList.toggle('collapsed');
                    mainContent.classList.toggle('expanded');
                    const icon = this.querySelector('i');
                    if (sidebar.classList.contains('collapsed')) {
                        icon.classList.remove('fa-chevron-left');
                        icon.classList.add('fa-chevron-right');
                        const submenu = document.getElementById('pendaftaranSubmenu');
                        if (submenu && submenu.classList.contains('show')) {
                            submenu.classList.remove('show');
                            const parentLink = document.querySelector('[onclick="toggleSubmenu(event)"]');
                            if (parentLink) parentLink.setAttribute('aria-expanded', 'false');
                        }
                    } else {
                        icon.classList.remove('fa-chevron-right');
                        icon.classList.add('fa-chevron-left');
                    }
                }
            });
        }
        
        // mobile menu handling
        function openMobileSidebar() {
            sidebar.classList.add('mobile-open');
            overlay.classList.add('active');
            document.body.classList.add('sidebar-open');
            overlay.style.display = 'block';
            mobileToggle.classList.add('active');
        }
        function closeMobileSidebar() {
            sidebar.classList.remove('mobile-open');
            overlay.classList.remove('active');
            document.body.classList.remove('sidebar-open');
            mobileToggle.classList.remove('active');
            setTimeout(() => { if (!sidebar.classList.contains('mobile-open')) overlay.style.display = 'none'; }, 200);
        }
        mobileToggle.addEventListener('click', function() {
            if (sidebar.classList.contains('mobile-open')) closeMobileSidebar();
            else openMobileSidebar();
        });
        overlay.addEventListener('click', closeMobileSidebar);
        document.addEventListener('keydown', (e) => { if (e.key === 'Escape' && sidebar.classList.contains('mobile-open')) closeMobileSidebar(); });
        
        // close on nav click (mobile)
        document.querySelectorAll('.nav-link:not([onclick])').forEach(link => {
            link.addEventListener('click', () => { if (window.innerWidth < 992) setTimeout(closeMobileSidebar, 100); });
        });
        
        // visibility
        document.addEventListener('visibilitychange', function() {
            if (!document.hidden) {
                if (!isEchoConnected) fetchPendingCounts();
                else fetchPendingCounts();
            }
        });
        window.addEventListener('online', () => { if (!isEchoConnected && !badgeUpdateInterval) startPolling(); fetchPendingCounts(); });
        window.addEventListener('offline', () => stopPolling());
        
        // resize handler
        let resizeTimer;
        window.addEventListener('resize', function() {
            clearTimeout(resizeTimer);
            resizeTimer = setTimeout(() => {
                if (window.innerWidth >= 992) closeMobileSidebar();
                if (window.innerWidth >= 992 && !sidebar.classList.contains('collapsed')) {
                    mainContent.style.marginLeft = '';
                }
            }, 100);
        });
        
        window.addEventListener('beforeunload', () => stopPolling());
    });
</script>

@stack('scripts')
</body>
</html>