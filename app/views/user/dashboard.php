<div class="section-header">
    <div>
        <div class="section-title">Dashboard</div>
        <div class="section-subtitle">Welcome back, <?= htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></div>
    </div>
</div>

<div class="row" style="margin-bottom: 24px;">
    <div class="col-md-6 col-12">
        <div class="stat-card">
            <div class="stat-icon">👤</div>
            <div class="stat-info">
                <div class="stat-value"><?= htmlspecialchars($_SESSION['user_name'] ?? 'User'); ?></div>
                <div class="stat-label">Your Username</div>
            </div>
        </div>
    </div>
    <div class="col-md-6 col-12">
        <div class="stat-card">
            <div class="stat-icon">🔑</div>
            <div class="stat-info">
                <div class="stat-value">Dot1x</div>
                <div class="stat-label">Auth Method</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body" style="text-align:center; padding: 48px;">
        <div style="font-size: 48px; margin-bottom: 16px;">🔒</div>
        <h3 style="font-family: 'Orbitron', sans-serif; font-size: 16px; color: var(--text-primary); margin-bottom: 10px;">Manage Your Auth Password</h3>
        <p style="color: var(--text-muted); margin-bottom: 24px;">You can change your dot1x authentication password directly on the MikroTik router. Use the link below to update it.</p>
        <a href="<?= BASEURL; ?>/user/change_password" class="btn-primary" style="display:inline-flex; align-items:center; gap: 8px; text-decoration:none;">🔑 Change Password</a>
    </div>
</div>
