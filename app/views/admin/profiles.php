<div class="section-header">
    <div>
        <div class="section-title">UM Profiles</div>
        <div class="section-subtitle">Router: <strong style="color:var(--primary-light)"><?= htmlspecialchars($data['router']['name']); ?></strong></div>
    </div>
    <div style="display:flex;gap:8px;">
        <button type="button" class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addProfileModal">+ Add Profile</button>
        <a href="<?= BASEURL; ?>/admin/userman/<?= $data['router']['id']; ?>" class="btn-secondary btn-sm">← Users</a>
    </div>
</div>

<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger">🔌 <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>

<?php if(isset($data['success'])): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($data['success']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-container" style="border:none;border-radius:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Profile Name</th>
                        <th>Speed Limit</th>
                        <th>Session Timeout</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['profiles'])): $i = 1; foreach($data['profiles'] as $p): ?>
                    <tr>
                        <td style="color:var(--text-muted);"><?= $i++; ?></td>
                        <td><strong><?= htmlspecialchars($p['name'] ?? 'N/A'); ?></strong></td>
                        <td style="font-family:'Courier New',monospace;color:var(--primary-light);"><?= htmlspecialchars($p['rate-limit'] ?? '–'); ?></td>
                        <td><?= htmlspecialchars($p['session-timeout'] ?? '–'); ?></td>
                        <td>
                            <a href="<?= BASEURL; ?>/admin/delete_profile/<?= $data['router']['id']; ?>/<?= urlencode($p['.id'] ?? ''); ?>"
                               class="btn-danger btn-sm"
                               onclick="return confirm('Delete profile: <?= htmlspecialchars($p['name'] ?? ''); ?>?')">🗑 Delete</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" style="text-align:center;padding:48px;color:var(--text-muted);">No profiles found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Profile Modal -->
<div class="modal fade" id="addProfileModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add UM Profile</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= BASEURL; ?>/admin/add_profile" method="POST">
        <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Profile Name</label>
                <input type="text" class="form-control" name="name" required placeholder="e.g. 10Mbps">
            </div>
            <div class="mb-3">
                <label class="form-label">Rate Limit (Download/Upload)</label>
                <input type="text" class="form-control" name="rate_limit" placeholder="e.g. 10M/5M">
                <div class="form-text">Leave empty for unlimited. Format: down/up (e.g. 10M/5M)</div>
            </div>
            <div class="mb-3">
                <label class="form-label">Session Timeout</label>
                <input type="text" class="form-control" name="session_timeout" placeholder="e.g. 1h (leave empty for unlimited)">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-primary">Create Profile</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php endif; ?>
