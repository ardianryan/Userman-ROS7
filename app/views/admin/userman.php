<div class="section-header">
    <div>
        <div class="section-title">User Manager Users</div>
        <div class="section-subtitle">Router: <strong style="color:var(--primary-light)"><?= htmlspecialchars($data['router']['name']); ?></strong> · <?= htmlspecialchars($data['router']['ip_address']); ?></div>
    </div>
    <div style="display:flex;gap:8px;">
        <a href="<?= BASEURL; ?>/admin/profiles/<?= $data['router']['id']; ?>" class="btn-secondary btn-sm">📋 Profiles</a>
        <button type="button" class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal">+ Add User</button>
        <a href="<?= BASEURL; ?>/admin/routers" class="btn-secondary btn-sm">← Back</a>
    </div>
</div>

<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger">🔌 <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>

<?php if(isset($data['success'])): ?>
    <div class="alert alert-success">✅ <?= htmlspecialchars($data['success']); ?></div>
<?php endif; ?>
<?php if(isset($data['op_error'])): ?>
    <div class="alert alert-danger">⚠️ <?= htmlspecialchars($data['op_error']); ?></div>
<?php endif; ?>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-container" style="border:none;border-radius:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Profile</th>
                        <th>Status</th>
                        <th>Shared Users</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['um_users'])): $i = 1; foreach($data['um_users'] as $u): ?>
                    <tr>
                        <td style="color:var(--text-muted);"><?= $i++; ?></td>
                        <td><strong><?= htmlspecialchars($u['name'] ?? 'N/A'); ?></strong></td>
                        <td style="font-family:'Courier New',monospace;color:var(--text-muted);"><?= htmlspecialchars($u['password'] ?? ''); ?></td>
                        <td><span class="badge badge-admin"><?= htmlspecialchars($u['profile'] ?? 'default'); ?></span></td>
                        <td>
                            <?php if(isset($u['disabled']) && $u['disabled'] == 'true'): ?>
                                <span class="badge badge-danger">Disabled</span>
                            <?php else: ?>
                                <span class="badge badge-success">Active</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($u['shared-users'] ?? '1'); ?></td>
                        <td style="display:flex;gap:6px;">
                            <button class="btn-secondary btn-sm"
                                onclick="openEditModal('<?= htmlspecialchars($u['.id']); ?>','<?= htmlspecialchars($u['name'] ?? ''); ?>','<?= htmlspecialchars($u['password'] ?? ''); ?>','<?= htmlspecialchars($u['profile'] ?? 'default'); ?>','<?= htmlspecialchars($u['shared-users'] ?? '1'); ?>','<?= ($u['disabled'] ?? 'false'); ?>')">
                                ✏️ Edit
                            </button>
                            <a href="<?= BASEURL; ?>/admin/delete_userman_user/<?= $data['router']['id']; ?>/<?= urlencode($u['.id']); ?>"
                               class="btn-danger btn-sm"
                               onclick="return confirm('Delete user: <?= htmlspecialchars($u['name'] ?? ''); ?>?')">🗑</a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" style="text-align:center;padding:48px;color:var(--text-muted);">No users found. Add one to get started.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add User Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Add User Manager User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= BASEURL; ?>/admin/add_userman_user" method="POST">
        <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" name="username" required placeholder="e.g. john.doe">
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="text" class="form-control" name="password" required placeholder="Plain text password">
            </div>
            <div class="mb-3">
                <label class="form-label">Profile</label>
                <select name="profile" class="form-control">
                    <option value="default">default</option>
                    <?php foreach($data['profiles'] ?? [] as $p): ?>
                        <option value="<?= htmlspecialchars($p['name'] ?? ''); ?>"><?= htmlspecialchars($p['name'] ?? ''); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Shared Users</label>
                <input type="number" class="form-control" name="shared_users" value="1" min="1">
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-primary">Add User</button>
        </div>
      </form>
    </div>
  </div>
</div>

<!-- Edit User Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header">
        <h5 class="modal-title">Edit User Manager User</h5>
        <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
      </div>
      <form action="<?= BASEURL; ?>/admin/edit_userman_user" method="POST">
        <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
        <input type="hidden" name="user_id" id="edit_user_id">
        <div class="modal-body">
            <div class="mb-3">
                <label class="form-label">Username</label>
                <input type="text" class="form-control" id="edit_username" name="username" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Password</label>
                <input type="text" class="form-control" id="edit_password" name="password" required>
            </div>
            <div class="mb-3">
                <label class="form-label">Profile</label>
                <select name="profile" class="form-control" id="edit_profile">
                    <option value="default">default</option>
                    <?php foreach($data['profiles'] ?? [] as $p): ?>
                        <option value="<?= htmlspecialchars($p['name'] ?? ''); ?>"><?= htmlspecialchars($p['name'] ?? ''); ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3">
                <label class="form-label">Shared Users</label>
                <input type="number" class="form-control" id="edit_shared_users" name="shared_users" min="1">
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="disabled" class="form-control" id="edit_disabled">
                    <option value="false">Active</option>
                    <option value="true">Disabled</option>
                </select>
            </div>
        </div>
        <div class="modal-footer">
            <button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button>
            <button type="submit" class="btn-primary">Save Changes</button>
        </div>
      </form>
    </div>
  </div>
</div>

<?php endif; ?>

<script>
function openEditModal(id, name, pass, profile, shared, disabled) {
    document.getElementById('edit_user_id').value = id;
    document.getElementById('edit_username').value = name;
    document.getElementById('edit_password').value = pass;
    document.getElementById('edit_shared_users').value = shared;
    var profileSel = document.getElementById('edit_profile');
    for(var i=0;i<profileSel.options.length;i++){
        if(profileSel.options[i].value === profile) profileSel.selectedIndex=i;
    }
    var disSel = document.getElementById('edit_disabled');
    for(var i=0;i<disSel.options.length;i++){
        if(disSel.options[i].value === disabled) disSel.selectedIndex=i;
    }
    var modal = new bootstrap.Modal(document.getElementById('editUserModal'));
    modal.show();
}
</script>
