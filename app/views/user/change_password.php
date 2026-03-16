<div class="section-header">
    <div>
        <div class="section-title">Change Dot1x Password</div>
        <div class="section-subtitle">Update your RADIUS authentication password on the MikroTik User Manager</div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header"><h5>Password Update Form</h5></div>
            <div class="card-body">
                <?php if(isset($data['success'])): ?>
                    <div class="alert alert-success">✅ <?= $data['success']; ?></div>
                <?php endif; ?>
                <?php if(isset($data['error'])): ?>
                    <div class="alert alert-danger">⚠️ <?= $data['error']; ?></div>
                <?php endif; ?>

                <form action="<?= BASEURL; ?>/user/change_password" method="POST">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['user_name']); ?>" disabled>
                        <div class="form-text">Your username cannot be changed here.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Select Router</label>
                        <select name="router_id" class="form-control" required>
                            <option value="">-- Choose Router --</option>
                            <?php foreach($data['routers'] as $r): ?>
                                <option value="<?= $r['id']; ?>"><?= htmlspecialchars($r['name']); ?> (<?= htmlspecialchars($r['ip_address']); ?>)</option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="mb-4">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required minlength="4" placeholder="Minimum 4 characters">
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%; padding:12px; font-size:14px;">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header"><h5>Information</h5></div>
            <div class="card-body">
                <div style="display:flex; flex-direction:column; gap:16px;">
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <span style="font-size:22px;">🔐</span>
                        <div>
                            <div style="font-weight:600; color:var(--text-primary); margin-bottom:4px;">What is Dot1x?</div>
                            <div style="color:var(--text-muted); font-size:12px;">IEEE 802.1X is a port-based Network Access Control (PNAC) standard. Your password here is used to authenticate to the MikroTik RADIUS User Manager.</div>
                        </div>
                    </div>
                    <div style="display:flex; align-items:flex-start; gap:12px;">
                        <span style="font-size:22px;">⚠️</span>
                        <div>
                            <div style="font-weight:600; color:var(--text-primary); margin-bottom:4px;">Important</div>
                            <div style="color:var(--text-muted); font-size:12px;">After updating your password, you will need to reconnect your device to the network with the new credentials.</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
