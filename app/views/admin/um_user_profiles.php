<?php require_once __DIR__ . '/um_tabs.php'; ?>
<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>
<?php if(isset($data['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($data['success']); ?></div><?php endif; ?>
<div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
    <button class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUPModal"><i class="fas fa-plus"></i> Assign Profile</button>
</div>
<div class="card"><div class="card-body" style="padding:0;">
    <div class="table-container" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead><tr><th>#</th><th>User</th><th>Profile</th><th>State</th><th>Start Time</th><th>End Time</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if(!empty($data['rows'])): $i=1; foreach($data['rows'] as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i++; ?></td>
                    <td><strong><?= htmlspecialchars($row['user']??''); ?></strong></td>
                    <td><span class="badge badge-admin"><?= htmlspecialchars($row['profile']??''); ?></span></td>
                    <td><?= htmlspecialchars($row['state']??'–'); ?></td>
                    <td style="font-size:11px;"><?= htmlspecialchars($row['start-time']??'–'); ?></td>
                    <td style="font-size:11px;"><?= htmlspecialchars($row['end-time']??'–'); ?></td>
                    <td>
                        <a href="<?= BASEURL; ?>/admin/um_delete/<?= $data['router']['id']; ?>/user-profile/<?= urlencode($row['.id']??''); ?>/user_profiles" class="btn-danger btn-sm" onclick="return confirm('Remove user profile?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">No user profiles assigned.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div></div>

<div class="modal fade" id="addUPModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Assign Profile to User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="<?= BASEURL; ?>/admin/um_assign_user_profile" method="POST">
      <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">User</label>
            <select name="user" class="form-control" required>
                <option value="">-- Select user --</option>
                <?php foreach($data['um_users']??[] as $u): ?><option value="<?= htmlspecialchars($u['name']??''); ?>"><?= htmlspecialchars($u['name']??''); ?></option><?php endforeach; ?>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Profile</label>
            <select name="profile" class="form-control" required>
                <option value="">-- Select profile --</option>
                <?php foreach($data['profiles']??[] as $p): ?><option value="<?= htmlspecialchars($p['name']??''); ?>"><?= htmlspecialchars($p['name']??''); ?></option><?php endforeach; ?>
            </select>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Assign</button></div>
    </form>
  </div></div>
</div>
<?php endif; ?>
