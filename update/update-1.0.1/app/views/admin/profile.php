<div class="section-header">
    <div>
        <div class="section-title">My Profile</div>
        <div class="section-subtitle">Update your admin credentials</div>
    </div>
</div>

<?php if(isset($data['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($data['success']); ?></div><?php endif; ?>
<?php if(isset($data['error'])): ?><div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['error']); ?></div><?php endif; ?>

<div class="row">
    <!-- Profile Card -->
    <div class="col-md-4" style="margin-bottom:20px;">
        <div class="card" style="text-align:center;">
            <div class="card-body" style="padding:32px 20px;">
                <div style="width:80px;height:80px;background:linear-gradient(135deg,var(--primary),var(--primary-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:32px;font-weight:700;color:#fff;margin:0 auto 16px;">
                    <?= strtoupper(substr($_SESSION['user_name']??'A',0,1)); ?>
                </div>
                <div style="font-size:18px;font-weight:700;color:var(--text-primary);margin-bottom:4px;">
                    <?= htmlspecialchars($_SESSION['user_name']??'Admin'); ?>
                </div>
                <div style="font-size:12px;color:var(--text-muted);margin-bottom:16px;">
                    <span class="badge badge-admin"><i class="fas fa-shield-halved" style="font-size:10px;margin-right:4px;"></i><?= htmlspecialchars($_SESSION['user_role']??'admin'); ?></span>
                </div>
                <div style="font-size:11px;color:var(--text-muted);">ID #<?= htmlspecialchars($_SESSION['user_id']??'–'); ?></div>
            </div>
        </div>
    </div>

    <!-- Change Password Form -->
    <div class="col-md-8" style="margin-bottom:20px;">
        <div class="card">
            <div class="card-header">
                <h5><i class="fas fa-key" style="color:var(--primary-light);margin-right:8px;"></i>Change Password</h5>
            </div>
            <div class="card-body">
                <form action="<?= BASEURL; ?>/admin/update_profile" method="POST">
                    <input type="hidden" name="csrf_token" value="<?= Security::csrf_token(); ?>">
                    <div class="mb-3">
                        <label class="form-label">Current Password</label>
                        <input type="password" class="form-control" name="current_password" required placeholder="Enter current password">
                    </div>
                    <div class="mb-3">
                        <label class="form-label">New Password</label>
                        <input type="password" class="form-control" name="new_password" id="new_password" required placeholder="Min. 6 characters">
                    </div>
                    <div class="mb-4">
                        <label class="form-label">Confirm New Password</label>
                        <input type="password" class="form-control" name="confirm_password" required placeholder="Repeat new password">
                    </div>
                    <button type="submit" class="btn-primary">
                        <i class="fas fa-floppy-disk" style="margin-right:6px;"></i>Update Password
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>
