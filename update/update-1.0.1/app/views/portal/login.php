<?php
$settings = $data['settings'] ?? [];
$appName  = $settings['app_name'] ?? 'MikroTik UserMan';
$appLogo  = $settings['app_logo'] ?? 'assets/img/mangoteklogo.png';
$portalTitle = $settings['portal_title'] ?? 'User Portal';
$portalDesc  = $settings['portal_description'] ?? 'Login to manage your network account';

$routers = $data['routers'] ?? [];
$singleRouter = count($routers) === 1;
?>
<div class="auth-page">
    <div class="auth-card">
        <div class="auth-logo">
            <?php if(!empty($appLogo)): ?>
                <img src="<?= BASEURL . '/' . $appLogo; ?>" alt="<?= htmlspecialchars($appName); ?>" style="max-height:64px; max-width:180px; object-fit:contain; margin-bottom:14px;">
            <?php endif; ?>
            <h2><?= htmlspecialchars(strtoupper($portalTitle)); ?></h2>
            <p><?= htmlspecialchars($portalDesc); ?></p>
        </div>

        <?php if(isset($data['error'])): ?>
            <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['error']); ?></div>
        <?php endif; ?>

        <form action="<?= BASEURL; ?>/portal/login" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Security::csrf_token(); ?>">
            <div class="mb-3">
                <label class="form-label">Select Router / Network</label>
                <?php if($singleRouter): ?>
                    <!-- Only 1 router: auto-select, show as read-only text -->
                    <input type="hidden" name="router_id" value="<?= $routers[0]['id']; ?>">
                    <input type="text" class="form-control" value="<?= htmlspecialchars($routers[0]['name']); ?>" disabled style="opacity:.85;">
                <?php else: ?>
                    <select name="router_id" class="form-control" required>
                        <option value="">-- Select your network --</option>
                        <?php foreach($routers as $r): ?>
                            <option value="<?= $r['id']; ?>" <?= (($_POST['router_id'] ?? '') == $r['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($r['name']); ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                <?php endif; ?>
            </div>
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" name="username" class="form-control" placeholder="Your username" value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>" required autocomplete="username">
            </div>
            <div class="mb-4">
                <label class="form-label">Password</label>
                <input type="password" name="password" class="form-control" placeholder="Your password" required autocomplete="current-password">
            </div>
            <button type="submit" class="btn-primary auth-submit-btn">
                <i class="fas fa-right-to-bracket" style="margin-right:8px;"></i>Login
            </button>
        </form>

        <div style="text-align:center; margin-top:20px; font-size:11px; color:var(--text-muted); display:flex; justify-content:space-between; flex-wrap:wrap; gap:8px;">
            <span>No account? <a href="<?= BASEURL; ?>/portal/register" style="color:var(--primary-light);"><i class="fas fa-user-plus" style="font-size:9px;"></i> Register</a></span>
            <span>Admin? <a href="<?= BASEURL; ?>/auth" style="color:var(--primary-light);">Admin Panel <i class="fas fa-arrow-right" style="font-size:9px;"></i></a></span>
        </div>
    </div>
</div>
<?php require_once __DIR__ . '/../../views/templates/footer.php'; ?>
