<div class="section-header">
    <div>
        <div class="section-title">RADIUS Users</div>
        <div class="section-subtitle">Router: <strong style="color:var(--primary-light)"><?= htmlspecialchars($data['router']['name']); ?></strong> · <?= htmlspecialchars($data['router']['ip_address']); ?></div>
    </div>
    <a href="<?= BASEURL; ?>/admin/routers" class="btn-secondary btn-sm">← Back to Routers</a>
</div>

<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger">🔌 <?= $data['api_error']; ?></div>
<?php else: ?>
<div class="card">
    <div class="card-body" style="padding: 0;">
        <div class="table-container" style="border: none; border-radius: 0;">
            <table class="data-table">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Username</th>
                        <th>Password</th>
                        <th>Status</th>
                        <th>Shared Users</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(!empty($data['radius_users'])): $i = 1; ?>
                        <?php foreach($data['radius_users'] as $u): ?>
                        <tr>
                            <td style="color: var(--text-muted);"><?= $i++; ?></td>
                            <td><strong><?= htmlspecialchars($u['name'] ?? 'N/A'); ?></strong></td>
                            <td style="font-family: 'Courier New', monospace; color: var(--text-muted);"><?= htmlspecialchars($u['password'] ?? '••••••'); ?></td>
                            <td>
                                <?php if(isset($u['disabled']) && $u['disabled'] == 'true'): ?>
                                    <span class="badge badge-danger">Disabled</span>
                                <?php else: ?>
                                    <span class="badge badge-success">Active</span>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($u['shared-users'] ?? '1'); ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php else: ?>
                    <tr>
                        <td colspan="5" style="text-align:center; padding: 48px; color: var(--text-muted);">No users found in this router's User Manager.</td>
                    </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
<?php endif; ?>
