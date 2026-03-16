<?php
// Count stats
$totalRouters = count($data['routers']);
?>

<div class="section-header">
    <div>
        <div class="section-title">Dashboard</div>
        <div class="section-subtitle">Overview of your MikroTik infrastructure</div>
    </div>
</div>

<!-- Stat Cards -->
<div class="row" style="margin-bottom: 24px;">
    <div class="col-md-3 col-12">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-network-wired"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= $totalRouters; ?></div>
                <div class="stat-label">Managed Routers</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-circle-check"></i></div>
            <div class="stat-info">
                <div class="stat-value"><?= $totalRouters; ?></div>
                <div class="stat-label">Routers Configured</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-users"></i></div>
            <div class="stat-info">
                <div class="stat-value">–</div>
                <div class="stat-label">RADIUS Users</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-12">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-server"></i></div>
            <div class="stat-info">
                <div class="stat-value">ROS 7</div>
                <div class="stat-label">RouterOS Version</div>
            </div>
        </div>
    </div>
</div>

<!-- Routers Table -->
<div class="card">
    <div class="card-header" style="display:flex; justify-content:space-between; align-items:center; padding: 20px 24px 16px;">
        <h5>Router Overview</h5>
        <a href="<?= BASEURL; ?>/admin/routers" class="btn-secondary btn-sm">View All →</a>
    </div>
    <div class="card-body" style="padding-top: 0;">
        <div class="table-container">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>IP Address</th>
                        <th>Added On</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data['routers'] as $router): ?>
                    <tr>
                        <td><strong><?= htmlspecialchars($router['name']); ?></strong></td>
                        <td><?= htmlspecialchars($router['ip_address']); ?></td>
                        <td><?= date('d M Y', strtotime($router['created_at'])); ?></td>
                        <td>
                            <a href="<?= BASEURL; ?>/admin/view_users/<?= $router['id']; ?>" class="btn-info btn-sm">Monitor Users</a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                    <?php if(empty($data['routers'])): ?>
                    <tr>
                        <td colspan="4" style="text-align:center; padding: 32px; color: var(--text-muted);">
                            No routers added yet. <a href="<?= BASEURL; ?>/admin/routers">Add one now →</a>
                        </td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
