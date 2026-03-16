<?php require_once __DIR__ . '/um_tabs.php'; ?>
<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>
<?php if(isset($data['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($data['success']); ?></div><?php endif; ?>
<div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
    <button class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addLimitModal"><i class="fas fa-plus"></i> Add Limitation</button>
</div>
<div class="card"><div class="card-body" style="padding:0;">
    <div class="table-container" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead><tr><th>#</th><th>Name</th><th>Rate Limit</th><th>DL Limit</th><th>UL Limit</th><th>Session Time</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if(!empty($data['rows'])): $i=1; foreach($data['rows'] as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i++; ?></td>
                    <td><strong><?= htmlspecialchars($row['name']??''); ?></strong></td>
                    <td style="font-family:'Courier New',monospace;color:var(--primary-light);"><?= htmlspecialchars($row['rate-limit']??'–'); ?></td>
                    <td><?= htmlspecialchars($row['download-limit']??'–'); ?></td>
                    <td><?= htmlspecialchars($row['upload-limit']??'–'); ?></td>
                    <td><?= htmlspecialchars($row['session-timeout']??'–'); ?></td>
                    <td>
                        <a href="<?= BASEURL; ?>/admin/um_delete/<?= $data['router']['id']; ?>/limitation/<?= urlencode($row['.id']??''); ?>/limitations" class="btn-danger btn-sm" onclick="return confirm('Delete limitation?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">No limitations defined.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div></div>

<div class="modal fade" id="addLimitModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Limitation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="<?= BASEURL; ?>/admin/um_add_limitation" method="POST">
      <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Name</label><input type="text" class="form-control" name="name" required placeholder="e.g. 10Mbps Limit"></div>
        <div class="mb-3"><label class="form-label">Rate Limit (down/up)</label><input type="text" class="form-control" name="rate_limit" placeholder="e.g. 10M/5M"></div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">Download Limit</label><input type="text" class="form-control" name="download_limit" placeholder="e.g. 1G"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Upload Limit</label><input type="text" class="form-control" name="upload_limit" placeholder="e.g. 500M"></div>
        </div>
        <div class="mb-3"><label class="form-label">Session Timeout</label><input type="text" class="form-control" name="session_timeout" placeholder="e.g. 1h"></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Create</button></div>
    </form>
  </div></div>
</div>
<?php endif; ?>
