<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register · <?= htmlspecialchars($data['settings']['app_name'] ?? 'UserMan'); ?></title>
    <?php $appLogo = $data['settings']['app_logo'] ?? 'assets/img/mangoteklogo.png'; ?>
    <link rel="icon" type="image/png" href="<?= BASEURL . '/' . $appLogo; ?>">
    <link rel="shortcut icon" type="image/png" href="<?= BASEURL . '/' . $appLogo; ?>">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/style.css">
    <link rel="stylesheet" href="<?= BASEURL; ?>/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;600;700;900&family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        :root {
            --primary: #ff6b00; --primary-light: #ff9a00;
            --bg-dark: #0a0a0f; --bg-card: rgba(255,255,255,0.04);
            --glass-border: rgba(255,255,255,0.08); --text-primary: #f0ede8;
            --text-muted: #888; --radius: 12px;
        }
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { background: var(--bg-dark); color: var(--text-primary); font-family: 'Inter', sans-serif; min-height: 100vh; display: flex; align-items: flex-start; justify-content: center; overflow-x: hidden; overflow-y: auto; }
        .bg-orbs { position: fixed; inset: 0; pointer-events: none; z-index: 0; }
        .orb { position: absolute; border-radius: 50%; filter: blur(80px); opacity: 0.12; }
        .orb1 { width: 500px; height: 500px; background: var(--primary); top: -150px; right: -150px; animation: floatOrb 12s ease-in-out infinite; }
        .orb2 { width: 400px; height: 400px; background: #7c3aed; bottom: -100px; left: -100px; animation: floatOrb 15s ease-in-out infinite reverse; }
        @keyframes floatOrb { 0%,100%{transform:translate(0,0)} 50%{transform:translate(30px,30px)} }
        .register-wrap { position: relative; z-index: 1; width: 100%; max-width: 460px; padding: 40px 24px; margin: auto; }
        .register-card { background: rgba(255,255,255,0.04); backdrop-filter: blur(20px); -webkit-backdrop-filter: blur(20px); border: 1px solid var(--glass-border); border-radius: 20px; padding: 40px 36px; }
        .brand { text-align: center; margin-bottom: 28px; }
        .brand img { height: 44px; margin-bottom: 12px; }
        .brand-name { font-family: 'Orbitron', sans-serif; font-size: 18px; font-weight: 700; color: var(--text-primary); }
        .brand-sub { font-size: 12px; color: var(--text-muted); margin-top: 4px; }
        h2 { font-family: 'Orbitron', sans-serif; font-size: 14px; font-weight: 600; letter-spacing: 1.5px; text-transform: uppercase; color: var(--primary-light); text-align: center; margin-bottom: 24px; }
        .form-group { margin-bottom: 16px; }
        label { display: block; font-size: 12px; font-weight: 500; color: var(--text-muted); margin-bottom: 6px; text-transform: uppercase; letter-spacing: 0.5px; }
        .input-wrap { position: relative; }
        .input-wrap i { position: absolute; left: 14px; top: 50%; transform: translateY(-50%); color: var(--text-muted); font-size: 13px; }
        input, select { width: 100%; background: rgba(255,255,255,0.05); border: 1px solid rgba(255,255,255,0.1); border-radius: 10px; padding: 12px 14px 12px 40px; color: var(--text-primary); font-size: 14px; outline: none; transition: border-color 0.2s; font-family: 'Inter', sans-serif; }
        input:focus, select:focus { border-color: var(--primary-light); background: rgba(255,107,0,0.06); }
        .btn-submit { width: 100%; padding: 14px; background: linear-gradient(135deg, var(--primary), var(--primary-light)); color: #fff; border: none; border-radius: 10px; font-size: 14px; font-weight: 600; cursor: pointer; margin-top: 8px; transition: opacity 0.2s, transform 0.2s; font-family: 'Inter', sans-serif; }
        .btn-submit:hover { opacity: 0.9; transform: translateY(-1px); }
        .alert { padding: 12px 16px; border-radius: 10px; font-size: 13px; margin-bottom: 16px; }
        .alert-danger { background: rgba(220,53,69,0.15); border: 1px solid rgba(220,53,69,0.3); color: #ff8a8a; }
        .alert-success { background: rgba(40,167,69,0.15); border: 1px solid rgba(40,167,69,0.3); color: #4caf8a; }
        .footer-links { text-align: center; margin-top: 20px; font-size: 12px; color: var(--text-muted); }
        .footer-links a { color: var(--primary-light); text-decoration: none; }
        .footer-links a:hover { text-decoration: underline; }
        .divider { display: flex; align-items: center; gap: 12px; margin: 20px 0; color: var(--text-muted); font-size: 11px; }
        .divider::before, .divider::after { content: ''; flex: 1; height: 1px; background: rgba(255,255,255,0.08); }
    </style>
</head>
<body>
<div class="bg-orbs"><div class="orb orb1"></div><div class="orb orb2"></div></div>
<div class="register-wrap">
    <div class="register-card">
        <div class="brand">
            <?php $logo = $data['settings']['app_logo'] ?? 'assets/img/mangoteklogo.png'; ?>
            <img src="<?= BASEURL . '/' . $logo; ?>" alt="Logo">
            <div class="brand-name"><?= htmlspecialchars($data['settings']['app_name'] ?? 'UserMan'); ?></div>
            <div class="brand-sub">User Self-Registration</div>
        </div>

        <h2><i class="fas fa-user-plus" style="margin-right:8px;"></i>Create Account</h2>

        <?php if(isset($data['error'])): ?>
        <div class="alert alert-danger"><i class="fas fa-circle-exclamation" style="margin-right:6px;"></i><?= htmlspecialchars($data['error']); ?></div>
        <?php endif; ?>
        <?php if(isset($data['success'])): ?>
        <div class="alert alert-success"><i class="fas fa-check-circle" style="margin-right:6px;"></i><?= htmlspecialchars($data['success']); ?></div>
        
        <div style="margin-top:20px; text-align:center; padding: 20px; background: rgba(255,107,0,0.05); border: 1px solid rgba(255,107,0,0.2); border-radius: var(--radius-md);">
            <div style="font-size: 13px; color: var(--text-secondary); margin-bottom: 12px;">Your Wi-Fi account is now active. Please use your credentials to log in to our Hotspot network.</div>
            <a href="http://homewifi.ppti.me" class="btn-submit" style="display:inline-flex; align-items:center; justify-content:center; text-decoration:none; width:auto; padding:0 24px; margin:0;"><i class="fas fa-wifi" style="margin-right:8px;"></i>Click here to Login to Hotspot</a>
            <div style="font-size: 10px; color: var(--text-muted); margin-top: 10px; font-family: monospace;">http://homewifi.ppti.me</div>
        </div>
        <?php endif; ?>

        <?php if(!isset($data['success'])): ?>
        <form action="<?= BASEURL; ?>/portal/do_register" method="POST">
            <input type="hidden" name="csrf_token" value="<?= Security::csrf_token(); ?>">
            <div class="form-group">
                <label>Network / Router</label>
                <div class="input-wrap">
                    <i class="fas fa-server"></i>
                    <?php if(count($data['routers']) === 1): ?>
                        <input type="hidden" name="router_id" value="<?= $data['routers'][0]['id']; ?>">
                        <input type="text" value="<?= htmlspecialchars($data['routers'][0]['name']); ?>" disabled style="opacity:.7;cursor:not-allowed;">
                    <?php else: ?>
                    <select name="router_id" required>
                        <option value="">-- Select network --</option>
                        <?php foreach($data['routers'] as $r): ?>
                        <option value="<?= $r['id']; ?>" <?= (($_POST['router_id'] ?? '') == $r['id']) ? 'selected' : '' ?>><?= htmlspecialchars($r['name']); ?></option>
                        <?php endforeach; ?>
                    </select>
                    <?php endif; ?>
                </div>
            </div>
            <div class="form-group">
                <label>Username</label>
                <div class="input-wrap">
                    <i class="fas fa-user"></i>
                    <input type="text" name="username" required placeholder="Choose a username" value="<?= htmlspecialchars($_POST['username'] ?? ''); ?>">
                </div>
            </div>
            <div class="form-group">
                <label>Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="password" required placeholder="Min. 4 characters">
                </div>
            </div>
            <div class="form-group">
                <label>Confirm Password</label>
                <div class="input-wrap">
                    <i class="fas fa-lock"></i>
                    <input type="password" name="confirm_password" required placeholder="Repeat password">
                </div>
            </div>
            <div class="form-group">
                <label><i class="fas fa-key" style="margin-right:4px;color:var(--primary-light);"></i>Reference Code</label>
                <div class="input-wrap">
                    <i class="fas fa-hashtag"></i>
                    <input type="text" name="ref_code" required placeholder="Enter reference code from admin" style="letter-spacing:2px;text-transform:uppercase;" value="<?= htmlspecialchars($_POST['ref_code'] ?? ''); ?>">
                </div>
            </div>
            <button type="submit" class="btn-submit"><i class="fas fa-user-plus" style="margin-right:8px;"></i>Register Now</button>
        </form>
        <?php endif; ?>

        <div class="footer-links">
            Already have an account? <a href="<?= BASEURL; ?>/portal">Sign in</a>
        </div>
    </div>
</div>
</body>
</html>
