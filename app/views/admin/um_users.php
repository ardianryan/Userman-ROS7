<?php require_once __DIR__ . '/um_tabs.php'; ?>

<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>

<?php if(isset($data['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($data['success']); ?></div><?php endif; ?>
<?php if(isset($data['op_error'])): ?><div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['op_error']); ?></div><?php endif; ?>

<?php if (isset($_SESSION['flash_new_user'])): 
    $f = $_SESSION['flash_new_user'];
    $s = $data['settings'] ?? [];
    $appName = htmlspecialchars($s['app_name'] ?? 'MikroTik UserMan');
    $routerName = htmlspecialchars($f['router']);
    $uName = htmlspecialchars($f['username']);
    $uPass = htmlspecialchars($f['password']);
    
    $waText = "✅ *Pembuatan Akun Sukses*\n\n"
            . "🏢 *{$appName}*\n"
            . "🌐 *Network:* {$routerName}\n\n"
            . "👤 *Username:* {$uName}\n"
            . "🔑 *Password:* {$uPass}\n\n"
            . "Silakan gunakan kredensial di atas untuk login ke jaringan Wi-Fi kami. Terima kasih!";
            
    unset($_SESSION['flash_new_user']);
?>
<div class="card" style="margin-bottom:20px; border-left:4px solid #00C882;">
    <div class="card-body" style="padding:16px;">
        <div style="display:flex; justify-content:space-between; align-items:flex-start;">
            <div>
                <h6 style="color:#00C882; margin-bottom:12px; font-weight:700;"><i class="fas fa-check-circle"></i> User Successfully Created</h6>
                <div style="background:rgba(0,0,0,0.2); padding:12px; border-radius:8px; font-family:monospace; color:var(--text-secondary); white-space:pre-wrap; font-size:12px;" id="waMessageText"><?= $waText; ?></div>
            </div>
            <button class="btn-primary btn-sm" onclick="copyWaText()" style="background:#25D366; color:#fff; border:none; box-shadow:0 4px 15px rgba(37,211,102,0.3); flex-shrink:0; margin-left:16px;">
                <i class="fab fa-whatsapp"></i> Copy for WA
            </button>
        </div>
    </div>
</div>
<script>
function copyWaText() {
    const text = document.getElementById('waMessageText').innerText;
    navigator.clipboard.writeText(text).then(() => {
        alert('Format pesan WhatsApp berhasil disalin ke clipboard!');
    }).catch(err => {
        console.error('Failed to copy: ', err);
    });
}
</script>
<?php endif; ?>

<div style="display:flex;justify-content:flex-end;margin-bottom:12px;">
    <button class="btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addUserModal"><i class="fas fa-plus"></i> Add User</button>
</div>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-container" style="border:none;border-radius:0;">
            <table class="data-table">
                <thead><tr><th>#</th><th>Username</th><th>Password</th><th>Profile</th><th>Status</th><th>Shared</th><th>Actions</th></tr></thead>
                <tbody>
                    <?php if(!empty($data['um_users'])): $i=1; foreach($data['um_users'] as $u): ?>
                    <tr>
                        <td style="color:var(--text-muted);"><?= $i++; ?></td>
                        <td><strong><?= htmlspecialchars($u['name'] ?? ''); ?></strong></td>
                        <td style="font-family:'Courier New',monospace;color:var(--text-muted);max-width:120px;overflow:hidden;text-overflow:ellipsis;"><?= htmlspecialchars($u['password'] ?? ''); ?></td>
                        <td><span class="badge badge-admin"><?= htmlspecialchars($u['profile'] ?? 'default'); ?></span></td>
                        <td><?php if(($u['disabled']??'false')==='true'): ?><span class="badge badge-danger">Disabled</span><?php else: ?><span class="badge badge-success">Active</span><?php endif; ?></td>
                        <td><?= htmlspecialchars($u['shared-users'] ?? '1'); ?></td>
                        <td style="display:flex;gap:6px;">
                            <button class="btn-secondary btn-sm" onclick="openEditModal('<?= htmlspecialchars($u['.id']??''); ?>','<?= htmlspecialchars($u['name']??''); ?>','<?= htmlspecialchars($u['password']??''); ?>','<?= htmlspecialchars($u['profile']??'default'); ?>','<?= htmlspecialchars($u['shared-users']??'1'); ?>','<?= ($u['disabled']??'false'); ?>')"><i class="fas fa-pen"></i></button>
                            <a href="<?= BASEURL; ?>/admin/delete_userman_user/<?= $data['router']['id']; ?>/<?= urlencode($u['.id']??''); ?>" class="btn-danger btn-sm" onclick="return confirm('Delete user: <?= htmlspecialchars($u['name']??''); ?>?')"><i class="fas fa-trash"></i></a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">No users found.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Modal -->
<div class="modal fade" id="addUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add User Manager User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?= BASEURL; ?>/admin/add_userman_user" method="POST">
        <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Username</label><input type="text" class="form-control" name="username" required placeholder="e.g. john.doe"></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="text" class="form-control" name="password" required></div>
            <div class="mb-3"><label class="form-label">Profile</label>
                <select name="profile" class="form-control">
                    <option value="default">default</option>
                    <?php foreach($data['profiles']??[] as $p): ?><option value="<?= htmlspecialchars($p['name']??''); ?>"><?= htmlspecialchars($p['name']??''); ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Shared Users</label><input type="number" class="form-control" name="shared_users" value="1" min="1"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Add User</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Modal -->
<div class="modal fade" id="editUserModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit User</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?= BASEURL; ?>/admin/edit_userman_user" method="POST">
        <input type="hidden" name="router_id" value="<?= $data['router']['id']; ?>">
        <input type="hidden" name="user_id" id="edit_user_id">
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Username</label><input type="text" class="form-control" id="edit_username" name="username" required></div>
            <div class="mb-3"><label class="form-label">Password</label><input type="text" class="form-control" id="edit_password" name="password" required></div>
            <div class="mb-3"><label class="form-label">Profile</label>
                <select name="profile" class="form-control" id="edit_profile">
                    <option value="default">default</option>
                    <?php foreach($data['profiles']??[] as $p): ?><option value="<?= htmlspecialchars($p['name']??''); ?>"><?= htmlspecialchars($p['name']??''); ?></option><?php endforeach; ?>
                </select>
            </div>
            <div class="mb-3"><label class="form-label">Shared Users</label><input type="number" class="form-control" id="edit_shared_users" name="shared_users" min="1"></div>
            <div class="mb-3"><label class="form-label">Status</label><select name="disabled" class="form-control" id="edit_disabled"><option value="false">Active</option><option value="true">Disabled</option></select></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Save</button></div>
      </form>
    </div>
  </div>
</div>

<?php endif; ?>
<script>
function openEditModal(id,name,pass,profile,shared,disabled){
    document.getElementById('edit_user_id').value=id;
    document.getElementById('edit_username').value=name;
    document.getElementById('edit_password').value=pass;
    document.getElementById('edit_shared_users').value=shared;
    var ps=document.getElementById('edit_profile');
    for(var i=0;i<ps.options.length;i++)if(ps.options[i].value===profile)ps.selectedIndex=i;
    var ds=document.getElementById('edit_disabled');
    for(var i=0;i<ds.options.length;i++)if(ds.options[i].value===disabled)ds.selectedIndex=i;
    new bootstrap.Modal(document.getElementById('editUserModal')).show();
}
</script>
