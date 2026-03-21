<div class="section-header">
    <div>
        <div class="section-title">Change Password</div>
        <div class="section-subtitle">Update your dot1x network authentication credentials</div>
    </div>
</div>

<div class="row">
    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header"><h5>Update Password</h5></div>
            <div class="card-body">
                <?php if(isset($data['success'])): ?>
                    <div class="alert alert-success">✅ <?= htmlspecialchars($data['success']); ?></div>
                <?php endif; ?>
                <?php if(isset($data['error'])): ?>
                    <div class="alert alert-danger">⚠️ <?= htmlspecialchars($data['error']); ?></div>
                <?php endif; ?>

                <form action="<?= BASEURL; ?>/portal/change_password" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= Security::csrf_token(); ?>">
                    <div class="mb-3">
                        <label class="form-label">Username</label>
                        <input type="text" class="form-control" value="<?= htmlspecialchars($_SESSION['portal_user'] ?? ''); ?>" disabled>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" name="current_password" class="form-control" required placeholder="Enter current password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" name="new_password" class="form-control" required minlength="4" placeholder="Minimum 4 characters">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" name="confirm_password" class="form-control" required placeholder="Repeat new password">
                    </div>
                    <button type="submit" class="btn-primary" style="width:100%; padding:12px;">Update Password</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-6 col-12">
        <div class="card">
            <div class="card-header"><h5>Important Notes</h5></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:20px;">
                <div style="display:flex;align-items:flex-start;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,107,0,0.1);display:flex;align-items:center;justify-content:center;color:var(--primary-light);font-size:18px;flex-shrink:0;">
                        <i class="fas fa-shield-halved"></i>
                    </div>
                    <div>
                        <div style="font-weight:600;color:var(--text-primary);margin-bottom:4px;">Password Security</div>
                        <div style="color:var(--text-muted);font-size:12px;">Use a strong password. Avoid using personal info. Minimum 4 characters required.</div>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,107,0,0.1);display:flex;align-items:center;justify-content:center;color:var(--primary-light);font-size:18px;flex-shrink:0;">
                        <i class="fas fa-wifi"></i>
                    </div>
                    <div>
                        <div style="font-weight:600;color:var(--text-primary);margin-bottom:4px;">After Changing</div>
                        <div style="color:var(--text-muted);font-size:12px;">Reconnect your devices to the Wi-Fi network using your new password.</div>
                    </div>
                </div>
                <div style="display:flex;align-items:flex-start;gap:12px;">
                    <div style="width:40px;height:40px;border-radius:10px;background:rgba(255,107,0,0.1);display:flex;align-items:center;justify-content:center;color:var(--primary-light);font-size:18px;flex-shrink:0;">
                        <i class="fas fa-network-wired"></i>
                    </div>
                    <div>
                        <div style="font-weight:600;color:var(--text-primary);margin-bottom:4px;">Connected Network</div>
                        <div style="color:var(--text-muted);font-size:12px;">Your credentials are managed on: <strong style="color:var(--primary-light)"><?= htmlspecialchars($_SESSION['portal_router_name'] ?? ''); ?></strong></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
