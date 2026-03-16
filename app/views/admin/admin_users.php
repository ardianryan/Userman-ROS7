<?php
// Consume flash messages set by redirect actions
if (isset($_SESSION['flash_success'])) { $data['success'] = $_SESSION['flash_success']; unset($_SESSION['flash_success']); }
if (isset($_SESSION['flash_error']))   { $data['error']   = $_SESSION['flash_error'];   unset($_SESSION['flash_error']);   }
?>
<div class="section-header">
    <div>
        <div class="section-title">Admin Users</div>
        <div class="section-subtitle">Manage admin accounts</div>
    </div>
    <button class="btn-primary" data-bs-toggle="modal" data-bs-target="#addAdminModal">
        <i class="fas fa-plus"></i> Add Admin
    </button>
</div>

<?php if(isset($data['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($data['success']); ?></div><?php endif; ?>
<?php if(isset($data['error'])): ?><div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['error']); ?></div><?php endif; ?>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-container" style="border:none;border-radius:0;">
            <table class="data-table">
                <thead>
                    <tr><th>#</th><th>Username</th><th>Role</th><th>Created</th><th>Actions</th></tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['admin_users'])): $i=1; foreach($data['admin_users'] as $u): ?>
                    <tr>
                        <td style="color:var(--text-muted);"><?= $i++; ?></td>
                        <td>
                            <div style="display:flex;align-items:center;gap:10px;">
                                <div style="width:32px;height:32px;background:linear-gradient(135deg,var(--primary),var(--primary-light));border-radius:50%;display:flex;align-items:center;justify-content:center;font-size:13px;font-weight:700;color:#fff;flex-shrink:0;">
                                    <?= strtoupper(substr($u['username'],0,1)); ?>
                                </div>
                                <strong><?= htmlspecialchars($u['username']); ?></strong>
                                <?php if($u['id'] == ($_SESSION['user_id']??-1)): ?>
                                    <span class="badge badge-admin" style="font-size:9px;">You</span>
                                <?php endif; ?>
                            </div>
                        </td>
                        <td><span class="badge badge-admin"><i class="fas fa-shield-halved" style="font-size:9px;margin-right:3px;"></i><?= htmlspecialchars($u['role']); ?></span></td>
                        <td style="font-size:12px;color:var(--text-muted);"><?= date('d M Y, H:i', strtotime($u['created_at'])); ?></td>
                        <td style="display:flex;gap:6px;">
                            <button class="btn-secondary btn-sm"
                                onclick="openEdit('<?= $u['id']; ?>','<?= htmlspecialchars(addslashes($u['username'])); ?>','<?= $u['role']; ?>')">
                                <i class="fas fa-pen"></i>
                            </button>
                            <button class="btn-secondary btn-sm"
                                onclick="openResetPw('<?= $u['id']; ?>','<?= htmlspecialchars(addslashes($u['username'])); ?>')">
                                <i class="fas fa-key"></i>
                            </button>
                            <?php if($u['id'] != ($_SESSION['user_id']??-1)): ?>
                            <a href="<?= BASEURL; ?>/admin/delete_admin/<?= $u['id']; ?>"
                               class="btn-danger btn-sm"
                               onclick="return confirm('Delete admin: <?= htmlspecialchars($u['username']); ?>?')">
                                <i class="fas fa-trash"></i>
                            </a>
                            <?php else: ?>
                            <button class="btn-danger btn-sm" disabled title="Cannot delete yourself" style="opacity:.4;cursor:not-allowed;"><i class="fas fa-trash"></i></button>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">No admin users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Admin Modal -->
<div class="modal fade" id="addAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Add Admin User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="<?= BASEURL; ?>/admin/create_admin" method="POST">
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Username</label><input type="text" class="form-control" name="username" required placeholder="e.g. superadmin"></div>
        <div class="mb-3"><label class="form-label">Password</label><input type="password" class="form-control" name="password" required placeholder="Min. 6 characters"></div>
        <div class="mb-3"><label class="form-label">Role</label>
            <select name="role" class="form-control">
                <option value="admin">Admin</option>
            </select>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Create</button></div>
    </form>
  </div></div>
</div>

<!-- Edit Admin Modal -->
<div class="modal fade" id="editAdminModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Edit Admin</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="<?= BASEURL; ?>/admin/edit_admin" method="POST">
      <input type="hidden" name="id" id="ea_id">
      <div class="modal-body">
        <div class="mb-3"><label class="form-label">Username</label><input type="text" class="form-control" name="username" id="ea_username" required></div>
        <div class="mb-3"><label class="form-label">Role</label>
            <select name="role" class="form-control" id="ea_role">
                <option value="admin">Admin</option>
            </select>
        </div>
      </div>
      <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Save</button></div>
    </form>
  </div></div>
</div>

<!-- Reset Password Modal -->
<div class="modal fade" id="resetPwModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered"><div class="modal-content">
    <div class="modal-header"><h5 class="modal-title">Reset Password</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
    <form action="<?= BASEURL; ?>/admin/admin_reset_password" method="POST">
      <input type="hidden" name="id" id="rp_id">
      <div class="modal-body">
        <p style="color:var(--text-muted);font-size:13px;">Resetting password for: <strong id="rp_name"></strong></p>
        <div class="mb-3"><label class="form-label">New Password</label><input type="password" class="form-control" name="new_password" required placeholder="Min. 6 characters"></div>
        <div class="mb-3"><label class="form-label">Confirm Password</label><input type="password" class="form-control" name="confirm_password" required></div>
      </div>
      <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary"><i class="fas fa-key"></i> Reset</button></div>
    </form>
  </div></div>
</div>

<script>
function openEdit(id, name, role) {
    document.getElementById('ea_id').value = id;
    document.getElementById('ea_username').value = name;
    var rs = document.getElementById('ea_role');
    for(var i=0;i<rs.options.length;i++) if(rs.options[i].value===role) rs.selectedIndex=i;
    new bootstrap.Modal(document.getElementById('editAdminModal')).show();
}
function openResetPw(id, name) {
    document.getElementById('rp_id').value = id;
    document.getElementById('rp_name').textContent = name;
    new bootstrap.Modal(document.getElementById('resetPwModal')).show();
}
</script>
