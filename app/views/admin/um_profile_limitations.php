<?php require_once __DIR__ . '/um_tabs.php'; ?>
<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>
<?php if(isset($data['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($data['success']); ?></div><?php endif; ?>
<div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
    <button class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addPLModal"><i class="fas fa-plus"></i> Add Profile Limitation</button>
</div>
<div class="card"><div class="card-body" style="padding:0;">
    <div class="table-container" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead><tr><th>#</th><th>Profile</th><th>Limitation</th><th>From Time</th><th>Till Time</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if(!empty($data['rows'])): $i=1; foreach($data['rows'] as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i++; ?></td>
                    <td><span class="badge badge-admin"><?= htmlspecialchars($row['profile']??''); ?></span></td>
                    <td><strong><?= htmlspecialchars($row['limitation']??''); ?></strong></td>
                    <td><?= htmlspecialchars($row['from-time']??'–'); ?></td>
                    <td><?= htmlspecialchars($row['till-time']??'–'); ?></td>
                    <td>
                        <a href="<?= BASEURL; ?>/admin/um_delete/<?= $data['router']['id']; ?>/profile-limitation/<?= urlencode($row['.id']??''); ?>/profile_limitations" class="btn-danger btn-sm" onclick="return confirm('Remove profile limitation?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">No profile limitations defined.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div></div>

<div class="modal fade" id="addPLModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Profile Limitation</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="<?= BASEURL; ?>/admin/um_add_profile_limitation" method="POST">
      <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Profile</label>
            <select name="profile" class="form-control" required>
                <option value="">-- Select profile --</option>
                <?php foreach($data['profiles']??[] as $p): ?><option value="<?= htmlspecialchars($p['name']??''); ?>"><?= htmlspecialchars($p['name']??''); ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Limitation</label>
            <select name="limitation" class="form-control" required>
                <option value="">-- Select limitation --</option>
                <?php foreach($data['limitations']??[] as $l): ?><option value="<?= htmlspecialchars($l['name']??''); ?>"><?= htmlspecialchars($l['name']??''); ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="row">
            <div class="col-md-6 mb-3"><label class="form-label">From Time</label><input type="time" class="form-control" name="from_time" value="00:00:00"></div>
            <div class="col-md-6 mb-3"><label class="form-label">Till Time</label><input type="time" class="form-control" name="till_time" value="23:59:59"></div>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Create</button></div>
    </form>
  </div></div>
</div>
<?php endif; ?>
