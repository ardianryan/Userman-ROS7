<div class="section-header">
    <div>
        <div class="section-title">Manage Routers</div>
        <div class="section-subtitle">Add, edit and monitor your MikroTik routers</div>
    </div>
    <button type="button" class="btn-primary" data-bs-toggle="modal" data-bs-target="#addRouterModal">
        <i class="fas fa-plus"></i> Add Router
    </button>
</div>

<?php if(isset($data['success'])): ?><div class="alert alert-success"><i class="fas fa-check-circle"></i> <?= htmlspecialchars($data['success']); ?></div><?php endif; ?>
<?php if(isset($data['error'])): ?><div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['error']); ?></div><?php endif; ?>

<div class="card">
    <div class="card-body" style="padding:0;">
        <div class="table-container" style="border:none;border-radius:0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Router Name</th>
                        <th>IP Address</th>
                        <th>API User</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['routers'])): $i = 1; foreach($data['routers'] as $r): ?>
                    <tr>
                        <td style="color:var(--text-muted);"><?= $i++; ?></td>
                        <td><strong><?= htmlspecialchars($r['name']); ?></strong></td>
                        <td style="font-family:'Courier New',monospace;color:var(--primary-light);"><?= htmlspecialchars($r['ip_address']); ?></td>
                        <td><?= htmlspecialchars($r['api_user']); ?></td>
                        <td style="font-size:12px;"><?= date('d M Y, H:i', strtotime($r['created_at'])); ?></td>
                        <td style="display:flex;gap:6px;align-items:center;">
                            <a href="<?= BASEURL; ?>/admin/router_monitor/<?= $r['id']; ?>" class="btn-secondary btn-sm" title="Monitor Resources">
                                <i class="fas fa-gauge-high"></i> Monitor
                            </a>
                            <button class="btn-secondary btn-sm" title="Edit Router"
                                onclick="openEditRouter('<?= $r['id']; ?>','<?= htmlspecialchars(addslashes($r['name'])); ?>','<?= htmlspecialchars($r['ip_address']); ?>','<?= htmlspecialchars($r['api_user']); ?>')">
                                <i class="fas fa-pen"></i> Edit
                            </button>
                            <a href="<?= BASEURL; ?>/admin/delete_router/<?= $r['id']; ?>"
                               class="btn-danger btn-sm" title="Delete"
                               onclick="return confirm('Delete router: <?= htmlspecialchars($r['name']); ?>?')">
                                <i class="fas fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; else: ?>
                    <tr><td colspan="6" style="text-align:center;padding:48px;color:var(--text-muted);">No routers added yet. Click <strong>+ Add Router</strong> to get started.</td></tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- Add Router Modal -->
<div class="modal fade" id="addRouterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Add MikroTik Router</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?= BASEURL; ?>/admin/add_router" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Security::csrf_token(); ?>">
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Router Name</label><input type="text" class="form-control" name="name" required placeholder="e.g. Main Office Router"></div>
            <div class="mb-3"><label class="form-label">IP Address</label><input type="text" class="form-control" name="ip_address" required placeholder="e.g. 192.168.1.1"></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">API Username</label><input type="text" class="form-control" name="api_user" required placeholder="admin"></div>
                <div class="col-md-6 mb-3"><label class="form-label">API Password</label><input type="password" class="form-control" name="api_pass" required></div>
            </div>
            <div class="mb-3"><label class="form-label">RADIUS Secret</label><input type="password" class="form-control" name="secret" required></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Save Router</button></div>
      </form>
    </div>
  </div>
</div>

<!-- Edit Router Modal -->
<div class="modal fade" id="editRouterModal" tabindex="-1" aria-hidden="true">
  <div class="modal-dialog modal-dialog-centered">
    <div class="modal-content">
      <div class="modal-header"><h5 class="modal-title">Edit Router</h5><button type="button" class="btn-close" data-bs-dismiss="modal"></button></div>
      <form action="<?= BASEURL; ?>/admin/edit_router" method="POST">
        <input type="hidden" name="csrf_token" value="<?= Security::csrf_token(); ?>">
        <input type="hidden" name="id" id="edit_router_id">
        <div class="modal-body">
            <div class="mb-3"><label class="form-label">Router Name</label><input type="text" class="form-control" name="name" id="edit_router_name" required></div>
            <div class="mb-3"><label class="form-label">IP Address</label><input type="text" class="form-control" name="ip_address" id="edit_router_ip" required></div>
            <div class="row">
                <div class="col-md-6 mb-3"><label class="form-label">API Username</label><input type="text" class="form-control" name="api_user" id="edit_router_user" required></div>
                <div class="col-md-6 mb-3"><label class="form-label">API Password <span style="color:var(--text-muted);font-size:11px;">(leave blank to keep)</span></label><input type="password" class="form-control" name="api_pass" placeholder="Leave blank to keep"></div>
            </div>
            <div class="mb-3"><label class="form-label">RADIUS Secret <span style="color:var(--text-muted);font-size:11px;">(leave blank to keep)</span></label><input type="password" class="form-control" name="secret" placeholder="Leave blank to keep"></div>
        </div>
        <div class="modal-footer"><button type="button" class="btn-secondary" data-bs-dismiss="modal">Cancel</button><button type="submit" class="btn-primary">Save Changes</button></div>
      </form>
    </div>
  </div>
</div>

<script>
function openEditRouter(id, name, ip, user) {
    document.getElementById('edit_router_id').value   = id;
    document.getElementById('edit_router_name').value = name;
    document.getElementById('edit_router_ip').value   = ip;
    document.getElementById('edit_router_user').value = user;
    new bootstrap.Modal(document.getElementById('editRouterModal')).show();
}
</script>
