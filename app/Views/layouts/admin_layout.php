<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', 'Dashboard') ?> - SIM DINSOS</title>
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" />
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap');
        
        :root {
            --primary-color: #4A90E2;
            --sidebar-bg: #ffffffff;
            --sidebar-text: #A6B0CF;
            --sidebar-active: #FFFFFF;
            --content-bg: #F5F7FA;
            --header-bg: #FFFFFF;
            --card-bg: #FFFFFF;
            --text-primary: #2C3E50;
            --text-secondary: #8492A6;
        }

        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Inter', sans-serif; background-color: var(--content-bg); color: var(--text-primary); }
        ul { list-style-type: none; }
        a { text-decoration: none; }

        .wrapper { display: flex; min-height: 100vh; }
        th,td{
            font-size: 14px;
        }

        /* Sidebar */
        .sidebar {
            width: 260px;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            display: flex;
            flex-direction: column;
            background-color: #ffffff;
            position: sticky;
            top: 0;
            z-index: 1000; 
            height: 100vh;
            color: #ffffffff; /* Warna teks default untuk sidebar */
        }
        .sidebar-profile {
            padding: 30px 20px;
            text-align: center;
            color: white;
            background-image: url('<?= base_url('assets/images/sidebar-bg.svg') ?>');
            background-size: cover; /* Gunakan 'cover' agar gambar menutupi area */
            background-position: center center;
            background-blend-mode: multiply; /* Campurkan warna gradien dengan gambar */
        }
        .sidebar-profile img {
            width: 90px;
            height: 90px;
            border-radius: 50%;
            margin-top: 20px;
            margin-bottom: 30px;
            object-fit: cover;
        }
        .sidebar-profile h3 {
            margin: 0;
            font-weight: 600;
            margin-bottom: 5px; /* Tambahkan ini untuk memberi jarak ke bawah */
        }

        .sidebar-profile p {
            font-size: 14px;
            opacity: 0.8;
            margin: 0; /* Pastikan tidak ada margin default */
        }
        .sidebar-menu {
            list-style-type: none;
            flex-grow: 1;
            padding: 20px;
        }
        .sidebar-menu li a {
            display: flex;
            align-items: center;
            padding: 12px 15px;
            color: #555;
            text-decoration: none;
            margin-bottom: 10px;
            font-weight: 500;
            transition: all 0.2s;
            border-left: 4px solid transparent;
        }
        .sidebar-menu li a:hover {
            color: var(--primary-color);
            background-color: #f0f5fa;
        }
        .sidebar-menu li a.active {
            color: var(--primary-color);
            font-weight: 700;
            border-left: 4px solid var(--primary-color);
        }
        .sidebar-footer { 
            text-align: center; 
            font-size: 12px; 
            color: #999;
            padding: 20px; 
        }
        .footer-logout { display: block; margin-bottom: 15px; padding: 10px; font-weight: 600; text-align: center; color: #555; text-decoration: none; border-radius: 6px; transition: color 0.2s, background-color 0.2s; }
        .footer-logout:hover { color: #dc3545; background-color: #ffe9eaff; }
        /* Main Content Area */
        .main-content {
            flex: 1;
            width: calc(100% - 260px);
            display: flex;
            flex-direction: column;
        }

        .main-header {
            display: flex;
            box-shadow: 2px 0 10px rgba(0,0,0,0.1);
            justify-content: space-between;
            align-items: center;
            padding: 16px 24px;
            background-color: var(--header-bg);
            border-bottom: 1px solid var(--border-color);
            position: sticky;
            top: 0;
            z-index: 999;
        }
        .header-logo { display: flex; align-items: center; gap: 12px; }
        .header-logo img { height: 32px; }
        .header-title h1 { font-size: 18px; font-weight: 600; margin: 0; }
        .header-title p { font-size: 14px; color: var(--text-secondary); margin: 0; }
        
        .header-user { display: flex; align-items: center; gap: 16px; }
        .user-info { text-align: right; }
        .user-info .role { font-size: 14px; font-weight: 500; }
        .user-info .wilayah { font-size: 12px; color: var(--text-secondary); }
        .header-user img { width: 40px; height: 40px; border-radius: 50%; object-fit: cover; }
        
        
        /* Content Area */
        .content-area { padding: 30px; overflow-y: auto; flex-grow: 1; }

        /* === RESPONSIVE STYLES === */
        .hamburger-menu { 
            display: none; /* Sembunyikan di desktop */
            background: none; 
            border: none; 
            font-size: 24px; 
            cursor: pointer;
            color: var(--text-secondary);
            margin-right: 15px;
        }
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.4);
            z-index: 999;
        }
        .header-logo .header-title {
            display: block; /* Pastikan selalu terlihat di desktop */
        }
        a.header-logo-link {
            text-decoration: none;
            color: inherit; /* Mengambil warna dari parent */
        }

        .back-button {
            padding: 12px 20px;
            background-color: #ffffffff;
            color: #5a6268;
            border: solid 1px #5a6268;
            border-radius: 8px;
            font-size: 14px;
            font-weight: 600;
            cursor: pointer;
            text-decoration: none;
            margin-right: 10px;
        }
        .back-button:hover {
            background-color: #5a6268;
            color: #fff;
        }

        .menu-button {
            display: flex; /* Membuat ikon dan teks sejajar */
            align-items: center; /* Menengahkan ikon dan teks secara vertikal */
            justify-content: center;
            gap: 10px; /* Jarak antara ikon dan teks */
            
            padding: 12px 15px; /* Ruang di dalam tombol */
            border-radius: 8px; /* Sudut yang sedikit melengkung */
            
            text-decoration: none; /* Menghilangkan garis bawah */
            font-weight: 600; /* Sedikit tebal */
            color: #334155; /* Warna teks abu-abu tua yang profesional */

            transition: all 0.2s ease-in-out; /* Animasi halus saat hover */
        }

        /* Efek saat kursor diarahkan ke tombol */
        .menu-button:hover {
            background-color: #e2e8f0; /* Warna latar sedikit abu-abu */
            color: #0056b3; /* Warna teks menjadi biru tua */
        }

        /* (Opsional) Mengatur ukuran ikon jika perlu */
        .menu-button i {
            font-size: 1.1rem;
        }

        /* Aturan untuk layar kecil */
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                transform: translateX(-100%);
                transition: transform 0.3s ease-in-out;
            }
            .sidebar.open {
                transform: translateX(0);
            }

            .main-header {
                display: flex;
                justify-content: space-between;
            }
            .main-area {
                width: 100%;
            }
            .hamburger-menu {
                display: block; /* Tampilkan di mobile */
            }
            .overlay.active {
                display: block;
            }
            /* === TAMBAHKAN ATURAN BARU DI SINI === */
            .main-area {
                width: 100%; /* Lebar area konten menjadi penuh */
            }
            .main-header {
                padding: 15px; /* Kurangi padding header */
            }
            .header-logo .header-title {
                display: none; /* Sembunyikan tulisan di header agar tidak sempit */
            }
            .user-info .wilayah {
                display: none; /* Sembunyikan tulisan wilayah di header */
            }

            /* .header-user{
                display: flex;
                flex-direction: column-reverse;
                gap: 0px;
            } */
            
            img[alt="Avatar"]{
                display: none;
            }
            /* === BATAS ATURAN BARU === */
        }

    
        
        <?= $this->renderSection('page_styles') ?>
    </style>
</head>
<body>
    <div class="wrapper">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-profile">
                <a href="<?php if (session()->get('role') == 'superadmin') echo site_url('admin/profile') ?>"><img src="<?= base_url('assets/images/default-avatar.svg') ?>" alt="Foto Profil"></a>
                <h3><?= esc(session()->get('username')) ?></h3>
                <p><?= (session()->get('role') == 'admin') ? 'Admin Wilayah Kerja' : 'Super Admin' ?></p>
            </div>

            <ul class="sidebar-menu">
                <?php $uri = service('uri'); ?>
                <li><a href="<?= site_url('admin/bankel') ?>" class="<?= ($uri->getSegment(2) == 'bankel') ? 'active' : '' ?>">SIM - BANKEL</a></li>
                <li><a href="<?= site_url('admin/monevkuep') ?>" class="<?= ($uri->getSegment(2) == 'monevkuep') ? 'active' : '' ?>">SIM - MONEVKUEP</a></li>
                <li><a href="<?= site_url('admin/difabelkepri') ?>" class="<?= ($uri->getSegment(2) == 'difabelkepri') ? 'active' : '' ?>">SIM - DIFABEL</a></li>
            </ul>
            
            <div class="sidebar-footer">
                <?php if (session()->get('role') === 'superadmin'): ?>
                    <li style="list-style: none;">
                        <a href="<?= site_url('admin/users') ?>" class="menu-button">
                            <i class="fas fa-users-cog"></i> 
                            <span>Manajemen Pengguna</span>
                        </a>
                    </li>
                <?php endif; ?>
                <a href="<?= site_url('logout') ?>" class="footer-logout">⏻ Logout</a>
                <p>&copy; <?= date('Y') ?> Dinsos Kepri</p>
            </div>
        </aside>

        <div class="overlay" id="overlay"></div>

        <main class="main-content">
            <header class="main-header">
                <button class="hamburger-menu" id="hamburgerMenu">&#9776;</button>
                <a href="<?= site_url('dashboard') ?>" class="header-logo-link">
                    <div class="header-logo">
                        <img src="<?= base_url('assets/images/logo-pemprov.png') ?>" alt="Logo Pemprov">
                        <img src="<?= base_url('assets/images/logo-kemensos.png') ?>" alt="Logo Dinsos">
                        <div class="header-title">
                            <h1>SPBS-Di Kepri</h1>
                            <p>Sistem Pengelolaan Bantuan Sosial & Difabel</p>
                        </div>
                    </div>
                </a>
                <div class="header-user">
                    <div class="user-info">
                        <div class="user-info">
                            <div class="role"><?= esc(ucfirst(session()->get('role'))) ?></div>
                            <div class="wilayah">
                                <?= get_nama_kabupaten() ?>
                            </div>
                        </div>
                    </div>
                    <a href="<?= site_url('admin/profile') ?>"><img src="<?= base_url('assets/images/default-avatar.svg') ?>" alt="Foto Profil"></a>
                </div>
            </header>

            <div class="content-area">
                <?= $this->renderSection('content') ?>
            </div>
        </main>
    </div>
    
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script>
    <script>
        // SCRIPT UNTUK RESPONSIVE SIDEBAR
        const sidebar = document.getElementById('sidebar');
        const hamburgerMenu = document.getElementById('hamburgerMenu');
        const overlay = document.getElementById('overlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        }

        function hideSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }

        // Tambahkan event listener
        hamburgerMenu.addEventListener('click', openSidebar);
        overlay.addEventListener('click', hideSidebar);

        // Anda bisa tambahkan tombol close di dalam sidebar jika perlu
         const closeSidebarBtn = document.getElementById('closeSidebarBtn');
         if(closeSidebarBtn) {
             closeSidebarBtn.addEventListener('click', hideSidebar);
        }
    </script>
    <?= $this->renderSection('page_scripts') ?> 
    </body>
</html>