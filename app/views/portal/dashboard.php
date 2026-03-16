<div class="section-header">
    <div>
        <div class="section-title">My Dashboard</div>
        <div class="section-subtitle">Welcome back, <?= htmlspecialchars($_SESSION['portal_user'] ?? 'User'); ?></div>
    </div>
</div>

<div class="row" style="margin-bottom:24px;">
    <div class="col-md-4 col-12">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-user"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:18px;"><?= htmlspecialchars($_SESSION['portal_user'] ?? ''); ?></div>
                <div class="stat-label">Your Username</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-network-wired"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:18px;"><?= htmlspecialchars($_SESSION['portal_router_name'] ?? ''); ?></div>
                <div class="stat-label">Connected Network</div>
            </div>
        </div>
    </div>
    <div class="col-md-4 col-12">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-shield-halved"></i></div>
            <div class="stat-info">
                <div class="stat-value" style="font-size:18px;">Dot1x</div>
                <div class="stat-label">Auth Method</div>
            </div>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-body" style="text-align:center; padding:48px;">
        <div style="width:72px;height:72px;background:linear-gradient(135deg,var(--primary),var(--primary-light));border-radius:50%;display:flex;align-items:center;justify-content:center;margin:0 auto 20px;">
            <i class="fas fa-lock" style="font-size:28px;color:#fff;"></i>
        </div>
        <h3 style="font-family:'Orbitron',sans-serif; font-size:15px; color:var(--text-primary); margin-bottom:10px;">Update Your Password</h3>
        <p style="color:var(--text-muted); margin-bottom:24px; max-width:400px; margin-left:auto; margin-right:auto;">
            You can securely change your network authentication password at any time. After updating, reconnect your device with the new credentials.
        </p>
        <a href="<?= BASEURL; ?>/portal/change_password" class="btn-primary" style="display:inline-flex; align-items:center; gap:8px; text-decoration:none;">
            <i class="fas fa-key"></i> Change Password
        </a>
    </div>
</div>
