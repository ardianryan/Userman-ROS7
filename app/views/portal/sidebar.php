<!-- Sidebar Overlay -->
<div id="sidebar-overlay"></div>

<!-- Portal Sidebar -->
<nav id="sidebar">
    <?php
    $s = $data['settings'] ?? [];
    $appName = $s['app_name'] ?? 'MikroTik UserMan';
    $appLogo = $s['app_logo'] ?? 'assets/img/mangoteklogo.png';
    ?>
    <div class="sidebar-brand">
        <?php if(!empty($appLogo)): ?>
            <img src="<?= BASEURL . '/' . $appLogo; ?>" alt="Logo" style="height:32px; width:auto; object-fit:contain; flex-shrink:0;">
        <?php else: ?>
            <div class="brand-icon"><i class="fas fa-network-wired"></i></div>
        <?php endif; ?>
        <div>
            <div class="brand-text"><?= htmlspecialchars($appName); ?></div>
            <div class="brand-sub">User Portal</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-group-title">My Account</div>
        <a href="<?= BASEURL; ?>/portal/dashboard" class="nav-item <?= ($data['activePage'] ?? '') == 'portal_dashboard' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-gauge-high"></i></span> Dashboard
        </a>
        <a href="<?= BASEURL; ?>/portal/change_password" class="nav-item <?= ($data['activePage'] ?? '') == 'portal_change_password' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-key"></i></span> Change Password
        </a>
    </div>

</nav>

<div id="content-wrapper">
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger-btn"><i class="fas fa-bars"></i></button>
            <h1 class="page-title"><?= htmlspecialchars($data['pageTitle'] ?? 'Dashboard'); ?></h1>
        </div>
        <div class="topbar-right">
            <div class="topbar-time" id="live-clock">--:--:--</div>
            
            <!-- User Dropdown Menu -->
            <div class="dropdown">
                <div class="topbar-user" data-bs-toggle="dropdown" aria-expanded="false" style="padding:4px; gap:0; border-radius:50%; border:none; background:transparent;">
                    <div class="user-avatar" style="width:36px; height:36px; cursor:pointer;"><?= strtoupper(substr($_SESSION['portal_user'] ?? 'U', 0, 1)); ?></div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end glass-dropdown">
                    <li style="padding: 8px 16px; margin-bottom: 4px;">
                        <div style="font-size:13px; font-weight:700; color:var(--text-primary);"><?= htmlspecialchars($_SESSION['portal_user'] ?? 'User'); ?></div>
                        <div style="font-size:10px; color:var(--primary-light); text-transform:uppercase; letter-spacing:0.5px;"><i class="fas fa-server" style="font-size:9px;"></i> <?= htmlspecialchars($_SESSION['portal_router_name'] ?? ''); ?></div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= BASEURL; ?>/portal/change_password"><i class="fas fa-key"></i> Change Password</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASEURL; ?>/portal/logout"><i class="fas fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </div>
            
        </div>
    </div>
    <div class="main-content">
