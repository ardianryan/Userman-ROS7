<?php
// UM section tab navigation
$routerId = $data['router']['id'] ?? '';
$routerName = htmlspecialchars($data['router']['name'] ?? '');
$routerIp   = htmlspecialchars($data['router']['ip_address'] ?? '');
$activeTab  = $data['umTab'] ?? 'users';

$tabs = [
    'routers'              => ['label' => 'Routers',             'icon' => 'fa-tower-broadcast'],
    'users'                => ['label' => 'Users',               'icon' => 'fa-users'],
    'user_groups'          => ['label' => 'User Groups',         'icon' => 'fa-layer-group'],
    'sessions'             => ['label' => 'Sessions',            'icon' => 'fa-clock-rotate-left'],
    'profiles'             => ['label' => 'Profiles',            'icon' => 'fa-id-card'],
    'user_profiles'        => ['label' => 'User Profiles',       'icon' => 'fa-user-check'],
    'limitations'          => ['label' => 'Limitations',         'icon' => 'fa-gauge'],
    'profile_limitations'  => ['label' => 'Profile Limitations', 'icon' => 'fa-link'],
    'attributes'           => ['label' => 'Attributes',          'icon' => 'fa-list-check'],
    'payment'              => ['label' => 'Payment',             'icon' => 'fa-credit-card'],
];
?>
<div style="margin-bottom:24px;">
    <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:16px;flex-wrap:wrap;gap:8px;">
        <div>
            <div class="section-title">User Manager</div>
            <div class="section-subtitle"><i class="fas fa-server" style="font-size:10px;color:var(--primary-light);margin-right:4px;"></i><?= $routerName; ?> · <?= $routerIp; ?></div>
        </div>
        <a href="<?= BASEURL; ?>/admin/routers" class="btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Routers</a>
    </div>

    <!-- Tab bar -->
    <div style="display:flex;gap:4px;flex-wrap:wrap;background:rgba(255,107,0,0.05);border:1px solid var(--glass-border);border-radius:var(--radius-md);padding:6px;">
        <?php foreach($tabs as $tab => $info): ?>
            <a href="<?= BASEURL; ?>/admin/um_<?= $tab; ?>/<?= $routerId; ?>"
               class="um-tab <?= $activeTab === $tab ? 'um-tab-active' : '' ?>">
                <i class="fas <?= $info['icon']; ?>"></i>
                <span><?= $info['label']; ?></span>
            </a>
        <?php endforeach; ?>
    </div>
</div>
