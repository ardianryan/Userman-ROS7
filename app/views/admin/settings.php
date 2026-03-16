<div class="section-header">
    <div>
        <div class="section-title">App Settings</div>
        <div class="section-subtitle">Customize the application name, logo and portal text</div>
    </div>
</div>

<?php if(isset($data['success'])): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($data['success']); ?></div>
<?php endif; ?>

<div class="row">
    <div class="col-md-8 col-12">
        <div class="card">
            <div class="card-header"><h5>Application Settings</h5></div>
            <div class="card-body">
                <form action="<?= BASEURL; ?>/admin/settings" method="POST" enctype="multipart/form-data">
                    <div class="mb-3">
                        <label class="form-label">Application Name</label>
                        <input type="text" name="app_name" class="form-control" value="<?= htmlspecialchars($data['settings']['app_name'] ?? ''); ?>" required>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Application Tagline</label>
                        <input type="text" name="app_tagline" class="form-control" value="<?= htmlspecialchars($data['settings']['app_tagline'] ?? ''); ?>">
                    </div>
                    <div style="height:1px;background:linear-gradient(90deg,var(--primary),transparent);opacity:.3;margin:20px 0;"></div>
                    <div class="mb-3">
                        <label class="form-label">Portal Page Title</label>
                        <input type="text" name="portal_title" class="form-control" value="<?= htmlspecialchars($data['settings']['portal_title'] ?? ''); ?>">
                        <div class="form-text">Title shown on the user portal login page.</div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Portal Page Description</label>
                        <input type="text" name="portal_description" class="form-control" value="<?= htmlspecialchars($data['settings']['portal_description'] ?? ''); ?>">
                    </div>
                    <div style="height:1px;background:linear-gradient(90deg,var(--primary),transparent);opacity:.3;margin:20px 0;"></div>
                    <div class="mb-4">
                        <label class="form-label">Application Logo</label>
                        <?php $logoPath = $data['settings']['app_logo'] ?? 'assets/img/mangoteklogo.png'; ?>
                        <?php if(!empty($logoPath)): ?>
                            <div style="margin-bottom:12px;">
                                <img src="<?= BASEURL . '/' . $logoPath; ?>" alt="Current Logo" style="max-height:60px;max-width:200px;object-fit:contain;background:rgba(255,255,255,0.05);padding:8px;border-radius:8px;border:1px solid var(--glass-border);">
                                <div class="form-text" style="margin-top:6px;">Current logo · Upload a new file to replace</div>
                            </div>
                        <?php endif; ?>
                        <input type="file" name="app_logo" class="form-control" accept="image/*">
                    </div>
                    <button type="submit" class="btn-primary" style="padding:12px 32px;">Save Settings</button>
                </form>
            </div>
        </div>
    </div>

    <div class="col-md-4 col-12">
        <div class="card">
            <div class="card-header"><h5>Preview</h5></div>
            <div class="card-body" style="text-align:center;">
                <?php if(!empty($logoPath)): ?>
                    <img src="<?= BASEURL . '/' . $logoPath; ?>" alt="Logo Preview" style="max-height:80px;max-width:200px;object-fit:contain;margin-bottom:16px;">
                <?php endif; ?>
                <div style="font-family:'Orbitron',sans-serif;font-size:14px;font-weight:700;color:var(--text-primary);">
                    <?= htmlspecialchars($data['settings']['app_name'] ?? ''); ?>
                </div>
                <div style="font-size:11px;color:var(--text-muted);margin-top:6px;">
                    <?= htmlspecialchars($data['settings']['app_tagline'] ?? ''); ?>
                </div>
            </div>
        </div>

        <div class="card" style="margin-top:16px;">
            <div class="card-header"><h5>Quick Links</h5></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:8px;">
                <a href="<?= BASEURL; ?>/portal" class="btn-secondary" style="text-decoration:none;text-align:center;"><i class="fas fa-link" style="margin-right:6px;"></i>View User Portal</a>
                <a href="<?= BASEURL; ?>/admin" class="btn-secondary" style="text-decoration:none;text-align:center;"><i class="fas fa-gauge-high" style="margin-right:6px;"></i>Admin Dashboard</a>
            </div>
        </div>

        <!-- ── Reference Code ── -->
        <div class="card" style="margin-top:16px;">
            <div class="card-header"><h5><i class="fas fa-key" style="color:var(--primary-light);margin-right:8px;"></i>Reference Code</h5></div>
            <div class="card-body">
                <p style="font-size:12px;color:var(--text-muted);margin-bottom:12px;">
                    Share this code so users can self-register on the portal. After registration they are auto-assigned to the <strong>guest</strong> profile.
                </p>
                <?php $refCode = $data['settings']['ref_code'] ?? '—'; ?>
                <div style="display:flex;align-items:center;gap:8px;margin-bottom:12px;">
                    <div id="ref-code-display" style="flex:1;background:rgba(255,107,0,0.08);border:1px solid rgba(255,107,0,0.3);border-radius:8px;padding:10px 14px;font-family:'Courier New',monospace;font-size:16px;font-weight:700;color:var(--primary-light);letter-spacing:3px;text-align:center;">
                        <?= htmlspecialchars($refCode); ?>
                    </div>
                    <button onclick="copyRefCode()" class="btn-secondary btn-sm" title="Copy code" style="flex-shrink:0;">
                        <i class="fas fa-copy"></i>
                    </button>
                </div>
                <form action="<?= BASEURL; ?>/admin/regenerate_ref_code" method="POST">
                    <button type="submit" class="btn-primary btn-sm" style="width:100%;">
                        <i class="fas fa-rotate-right"></i> Regenerate Code
                    </button>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function copyRefCode() {
    const code = document.getElementById('ref-code-display').textContent.trim();
    navigator.clipboard.writeText(code).then(() => {
        const btn = event.currentTarget;
        btn.innerHTML = '<i class="fas fa-check"></i>';
        setTimeout(() => btn.innerHTML = '<i class="fas fa-copy"></i>', 1500);
    });
}
</script>
