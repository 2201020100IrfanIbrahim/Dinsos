<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $this->renderSection('title', 'Dashboard') ?> - SIM DINSOS</title>
    <style>
        /* Reset & Body */
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; }

        /* Main Wrapper */
        .wrapper { display: flex; min-height: 100vh; position: relative; }

        /* Sidebar */
        .sidebar {
            width: 260px;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 2px 0 5px rgba(0,0,0,0.05);
            display: flex;
            flex-direction: column;
            transition: transform 0.3s ease-in-out;
            z-index: 1000;
            position: sticky;
            top: 0;
            height: 100vh;
        }
        .sidebar-header { text-align: center; margin-bottom: 30px; position: relative; }
        .sidebar-logo { font-size: 24px; font-weight: 700; color: #333; }
        .sidebar-menu { list-style-type: none; flex-grow: 1; }
        .sidebar-menu li a { display: block; padding: 12px 15px; color: #555; text-decoration: none; border-radius: 6px; margin-bottom: 5px; transition: background-color 0.2s, color 0.2s; }
        .sidebar-menu li a:hover, .sidebar-menu li a.active { background-color: #007bff; color: #fff; }
        .sidebar-footer { text-align: center; font-size: 12px; color: #999; }
        .footer-logout { display: block; margin-bottom: 15px; padding: 10px; font-weight: 600; text-align: center; color: #555; text-decoration: none; border-radius: 6px; transition: color 0.2s, background-color 0.2s; }
        .footer-logout:hover { color: #dc3545; background-color: #f8f9fa; }
        
        /* Main Content Area */
        .main-content {
            flex: 1;
            padding: 20px 30px;
            overflow-y: auto;
            width: 100%;
        }
        .main-header { display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; }
        .breadcrumb { font-size: 14px; color: #6c757d; }
        .breadcrumb a { color: #007bff; text-decoration: none; }
        .user-menu { display: flex; gap: 15px; }
        
        /* === RESPONSIVE STYLES === */
        .hamburger-menu, .close-sidebar {
            display: none; /* Sembunyikan di desktop */
            background: none;
            border: none;
            font-size: 24px;
            cursor: pointer;
        }
        .close-sidebar { position: absolute; top: 15px; right: 15px; }
        
        .overlay {
            display: none;
            position: fixed;
            top: 0; left: 0;
            width: 100%; height: 100%;
            background-color: rgba(0,0,0,0.5);
            z-index: 999;
        }

        /* Terapkan style ini jika layar di bawah 992px */
        @media (max-width: 992px) {
            .sidebar {
                position: fixed;
                height: 100%;
                transform: translateX(-100%); /* Sembunyikan sidebar ke kiri */
            }
            .sidebar.open {
                transform: translateX(0); /* Tampilkan sidebar */
            }
            .hamburger-menu, .close-sidebar {
                display: block; /* Tampilkan tombol di mobile */
            }
            .header-nav { display: flex; align-items: center; gap: 15px; }
            .overlay.active {
                display: block; /* Tampilkan overlay */
            }
        }
        
        <?= $this->renderSection('page_styles') ?>
    </style>
</head>
<body>
    <div class="wrapper">
        <aside class="sidebar" id="sidebar">
            <div class="sidebar-header">
                <span class="sidebar-logo">SIM-DINSOS</span>
                <button class="close-sidebar" id="closeSidebar">&times;</button>
            </div>
            <ul class="sidebar-menu">
                <li><a href="<?= site_url('admin/bankel') ?>" class="active">SIM-BANKEL</a></li>
                <li><a href="<?= site_url('admin/monevkuep') ?>">SIM-MONEVKUEP</a></li>
                <li><a href="<?= site_url('admin/difabelkepri') ?>">SIM-DIFABELKEPRI</a></li>
            </ul>
            <div class="sidebar-footer">
                <a href="<?= site_url('logout') ?>" class="footer-logout">⏻ Logout</a>
                <p>&copy; <?= date('Y') ?> Dinsos Kepri</p>
            </div>
        </aside>

        <div class="overlay" id="overlay"></div>

        <main class="main-content">
            <header class="main-header">
                 <div class="header-nav">
                    <button class="hamburger-menu" id="hamburgerMenu">&#9776;</button>
                    <nav class="breadcrumb">
                        <?php if (isset($breadcrumbs)): ?>
                            <?php foreach ($breadcrumbs as $index => $crumb): ?>
                                <?php if ($index < count($breadcrumbs) - 1): ?>
                                    <a href="<?= site_url($crumb['url']) ?>"><?= esc($crumb['title']) ?></a> > 
                                <?php else: ?>
                                    <span><?= esc($crumb['title']) ?></span>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </nav>
                 </div>
            </header>

            <?= $this->renderSection('content') ?>
        </main>
    </div>

    <script>
        // SCRIPT UNTUK RESPONSIVE SIDEBAR
        const sidebar = document.getElementById('sidebar');
        const hamburgerMenu = document.getElementById('hamburgerMenu');
        const closeSidebar = document.getElementById('closeSidebar');
        const overlay = document.getElementById('overlay');

        function openSidebar() {
            sidebar.classList.add('open');
            overlay.classList.add('active');
        }

        function hideSidebar() {
            sidebar.classList.remove('open');
            overlay.classList.remove('active');
        }

        hamburgerMenu.addEventListener('click', openSidebar);
        closeSidebar.addEventListener('click', hideSidebar);
        overlay.addEventListener('click', hideSidebar);

    </script>
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-plugin-datalabels@2.0.0"></script> ```
</body>
</html>