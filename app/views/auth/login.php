<?php
$settings = $data['settings'] ?? [];
$appName  = $settings['app_name'] ?? 'MikroTik UserMan';
$appTagline = $settings['app_tagline'] ?? 'RouterOS 7 · Admin Panel';
$appLogo  = $settings['app_logo'] ?? 'assets/img/mangoteklogo.png';
?>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <?php if(!empty($appLogo)): ?>
                <img src="<?= BASEURL . '/' . $appLogo; ?>" alt="<?= htmlspecialchars($appName); ?>" style="max-height:64px; max-width:180px; object-fit:contain; margin-bottom:14px;">
            <?php endif; ?>
            <h2><?= htmlspecialchars(strtoupper($appName)); ?></h2>
            <p><i class="fas fa-shield-halved" style="color:var(--primary-light);margin-right:4px;font-size:10px;"></i><?= htmlspecialchars($appTagline); ?></p>
        </div>

        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['error']); ?></div>
        <?php endif; ?>

        <form action="<?= BASEURL; ?>/auth/login" method="POST">
            <div class="mb-3">
                <label class="form-label">Admin Username</label>
                <input type="text" name="username" class="form-control" placeholder="Enter admin username" required autocomplete="username">
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Enter password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn-primary auth-submit-btn">
                <i class="fas fa-right-to-bracket" style="margin-right:8px;"></i>Login
            </button>
        </form>

        <div style="text-align:center; margin-top:20px; font-size:11px; color:var(--text-muted);">
            Not an admin? <a href="<?= BASEURL; ?>/portal" style="color:var(--primary-light);">User Portal <i class="fas fa-arrow-right" style="font-size:9px;"></i></a>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../templates/footer.php'; ?>
