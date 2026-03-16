<!-- Sidebar Overlay -->
<div id="sidebar-overlay"></div>

<!-- Sidebar -->
<nav id="sidebar">
    <?php
    $s = $data['settings'] ?? [];
    $appName = $s['app_name'] ?? 'MikroTik UserMan';
    $appLogo = $s['app_logo'] ?? 'assets/img/mangoteklogo.png';

    $routers = $data['routers'] ?? [];
    if (empty($routers)) {
        require_once __DIR__ . '/../../models/RouterModel.php';
        $rm = new RouterModel();
        $routers = $rm->getAllRouters();
    }
    $activeRouterForUM = $data['router']['id'] ?? null;
    ?>
    <div class="sidebar-brand">
        <?php if(!empty($appLogo)): ?>
            <img src="<?= BASEURL . '/' . $appLogo; ?>" alt="Logo" style="height:32px; width:auto; object-fit:contain; flex-shrink:0;">
        <?php else: ?>
            <div class="brand-icon"><i class="fas fa-network-wired"></i></div>
        <?php endif; ?>
        <div>
            <div class="brand-text"><?= htmlspecialchars($appName); ?></div>
            <div class="brand-sub">Admin Panel</div>
        </div>
    </div>

    <div class="sidebar-nav">
        <div class="nav-group-title">Administration</div>
        <a href="<?= BASEURL; ?>/admin" class="nav-item <?= ($data['activePage'] ?? '') == 'admin_dashboard' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-chart-pie"></i></span> Dashboard
        </a>
        <a href="<?= BASEURL; ?>/admin/routers" class="nav-item <?= ($data['activePage'] ?? '') == 'admin_routers' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-server"></i></span> Manage Routers
        </a>

        <?php if(!empty($routers)): ?>
        <div class="nav-group-title" style="margin-top:8px;">User Manager</div>

        <?php
        $umSections = [
            'um_routers'             => ['label' => 'Routers',             'icon' => 'fa-tower-broadcast'],
            'um_users'               => ['label' => 'Users',               'icon' => 'fa-users'],
            'um_user_groups'         => ['label' => 'User Groups',         'icon' => 'fa-layer-group'],
            'um_sessions'            => ['label' => 'Sessions',            'icon' => 'fa-clock-rotate-left'],
            'um_profiles'            => ['label' => 'Profiles',            'icon' => 'fa-id-card'],
            'um_user_profiles'       => ['label' => 'User Profiles',       'icon' => 'fa-user-check'],
            'um_limitations'         => ['label' => 'Limitations',         'icon' => 'fa-gauge'],
            'um_profile_limitations' => ['label' => 'Profile Limits',      'icon' => 'fa-link'],
            'um_attributes'          => ['label' => 'Attributes',          'icon' => 'fa-list-check'],
            'um_payment'             => ['label' => 'Payment',             'icon' => 'fa-credit-card'],
        ];
        ?>

        <?php foreach($routers as $r):
            $isActiveRouter = $activeRouterForUM == $r['id'];
        ?>
        <div class="sidebar-router-group">
            <div class="sidebar-router-header" onclick="toggleUMMenu(<?= $r['id']; ?>)">
                <span><i class="fas fa-server" style="font-size:11px;"></i> <?= htmlspecialchars($r['name']); ?></span>
                <i class="fas fa-chevron-down sidebar-router-chevron" id="chevron-<?= $r['id']; ?>" style="font-size:10px;transition:transform 0.2s;<?= $isActiveRouter ? 'transform:rotate(180deg);' : ''; ?>"></i>
            </div>
            <div class="sidebar-router-sections" id="um-menu-<?= $r['id']; ?>" style="<?= $isActiveRouter ? '' : 'display:none;'; ?>">
                <?php foreach($umSections as $action => $info): ?>
                <a href="<?= BASEURL; ?>/admin/<?= $action; ?>/<?= $r['id']; ?>"
                   class="nav-item nav-item-sub <?= (($data['activePage'] ?? '') == 'admin_userman' && ($data['umTab'] ?? '') == str_replace('um_', '', $action) && $isActiveRouter) ? 'active' : '' ?>"
                   style="padding-left:28px;">
                    <span class="nav-icon"><i class="fas <?= $info['icon']; ?>" style="font-size:11px;"></i></span>
                    <?= $info['label']; ?>
                </a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endforeach; ?>
        <?php endif; ?>

        <div class="nav-group-title" style="margin-top:8px;">System</div>
        <a href="<?= BASEURL; ?>/admin/admin_users" class="nav-item <?= ($data['activePage'] ?? '') == 'admin_users' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-user-shield"></i></span> Admin Users
        </a>
        <a href="<?= BASEURL; ?>/admin/settings" class="nav-item <?= ($data['activePage'] ?? '') == 'admin_settings' ? 'active' : '' ?>">
            <span class="nav-icon"><i class="fas fa-sliders"></i></span> App Settings
        </a>
        <a href="<?= BASEURL; ?>/portal" class="nav-item" target="_blank">
            <span class="nav-icon"><i class="fas fa-arrow-up-right-from-square" style="font-size:11px;"></i></span> User Portal ↗
        </a>
    </div>

</nav>

<!-- Content Wrapper -->
<div id="content-wrapper">
    <div class="topbar">
        <div class="topbar-left">
            <button class="hamburger" id="hamburger-btn"><i class="fas fa-bars"></i></button>
            <h1 class="page-title"><?= htmlspecialchars($data['pageTitle'] ?? ($data['title'] ?? 'Dashboard')); ?></h1>
        </div>
        <div class="topbar-right">
            <div class="topbar-time" id="live-clock">--:--:--</div>
            
            <!-- User Dropdown Menu -->
            <div class="dropdown">
                <div class="topbar-user" data-bs-toggle="dropdown" aria-expanded="false" style="padding:4px; gap:0; border-radius:50%; border:none; background:transparent;">
                    <div class="user-avatar" style="width:36px; height:36px; cursor:pointer;"><?= strtoupper(substr($_SESSION['user_name'] ?? 'A', 0, 1)); ?></div>
                </div>
                <ul class="dropdown-menu dropdown-menu-end glass-dropdown">
                    <li style="padding: 8px 16px; margin-bottom: 4px;">
                        <div style="font-size:13px; font-weight:700; color:var(--text-primary);"><?= htmlspecialchars($_SESSION['user_name'] ?? 'Admin'); ?></div>
                        <div style="font-size:10px; color:var(--primary-light); text-transform:uppercase; letter-spacing:0.5px;"><?= htmlspecialchars($_SESSION['user_role'] ?? 'admin'); ?></div>
                    </li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item" href="<?= BASEURL; ?>/admin/profile"><i class="fas fa-circle-user"></i> My Profile</a></li>
                    <li><hr class="dropdown-divider"></li>
                    <li><a class="dropdown-item text-danger" href="<?= BASEURL; ?>/auth/logout"><i class="fas fa-right-from-bracket"></i> Logout</a></li>
                </ul>
            </div>
            
        </div>
    </div>
    <div class="main-content">

<script>
function toggleUMMenu(routerId) {
    var menu = document.getElementById('um-menu-' + routerId);
    var chevron = document.getElementById('chevron-' + routerId);
    if (menu.style.display === 'none') {
        menu.style.display = 'block';
        chevron.style.transform = 'rotate(180deg)';
    } else {
        menu.style.display = 'none';
        chevron.style.transform = 'rotate(0deg)';
    }
}
</script>
