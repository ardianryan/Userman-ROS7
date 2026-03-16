<?php require_once __DIR__ . '/um_tabs.php'; ?>
<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>
<?php if(isset($data['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($data['success']); ?></div><?php endif; ?>
<div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
    <button class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addGroupModal"><i class="fas fa-plus"></i> Add Group</button>
</div>
<div class="card"><div class="card-body" style="padding:0;">
    <div class="table-container" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead><tr><th>#</th><th>Group Name</th><th>Inner Auth</th><th>Outer Auth</th><th>Actions</th></tr></thead>
            <tbody>
                <?php if(!empty($data['rows'])): $i=1; foreach($data['rows'] as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i++; ?></td>
                    <td><strong><?= htmlspecialchars($row['name']??''); ?></strong></td>
                    <td><?= htmlspecialchars($row['inner-auth-method']??'–'); ?></td>
                    <td><?= htmlspecialchars($row['outer-auth-method']??'–'); ?></td>
                    <td>
                        <a href="<?= BASEURL; ?>/admin/um_delete/<?= $data['router']['id']; ?>/user-group/<?= urlencode($row['.id']??''); ?>/user_groups" class="btn-danger btn-sm" onclick="return confirm('Delete group?')"><i class="fas fa-trash"></i></a>
                    </td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">No user groups found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div></div>

<div class="modal fade" id="addGroupModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add User Group</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="<?= BASEURL; ?>/admin/um_add_group" method="POST">
      <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Group Name</label><input type="text" class="form-control" name="name" required></div>
        <div class="mb-3"><label class="form-label">Inner Auth Method</label>
            <select name="inner_auth" class="form-control">
                <option value="">any</option>
                <option value="pap">PAP</option><option value="chap">CHAP</option><option value="mschapv1">MSCHAPv1</option><option value="mschapv2">MSCHAPv2</option>
            </select>
        </div>
        <div class="mb-3"><label class="form-label">Outer Auth Method</label>
            <select name="outer_auth" class="form-control">
                <option value="">any</option>
                <option value="eap-tls">EAP-TLS</option><option value="eap-ttls">EAP-TTLS</option><option value="eap-peap">EAP-PEAP</option>
            </select>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Create</button></div>
    </form>
  </div></div>
</div>
<?php endif; ?>
