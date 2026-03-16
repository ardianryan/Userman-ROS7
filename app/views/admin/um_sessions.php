<?php require_once __DIR__ . '/um_tabs.php'; ?>
<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:12px;">
    <span style="color:var(--text-muted);font-size:12px;"><?= count($data['rows']); ?> active sessions</span>
    <a href="<?= BASEURL; ?>/admin/um_sessions/<?= $data['router']['id']; ?>" class="btn-secondary btn-sm"><i class="fas fa-rotate-right"></i> Refresh</a>
</div>
<div class="card"><div class="card-body" style="padding:0;">
    <div class="table-container" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead><tr><th>#</th><th>User</th><th>Nas IP</th><th>Called ID</th><th>Started</th><th>Status</th><th>Download</th><th>Upload</th></tr></thead>
            <tbody>
                <?php if(!empty($data['rows'])): $i=1; foreach($data['rows'] as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i++; ?></td>
                    <td><strong><?= htmlspecialchars($row['user']??''); ?></strong></td>
                    <td style="font-family:'Courier New',monospace;"><?= htmlspecialchars($row['nas-ip-address']??''); ?></td>
                    <td><?= htmlspecialchars($row['called-station-id']??'–'); ?></td>
                    <td style="font-size:11px;"><?= htmlspecialchars($row['acct-start-time']??'–'); ?></td>
                    <td>
                        <?php $status = $row['status']??''; ?>
                        <span class="badge <?= ($status==='start'||$status==='alive') ? 'badge-success' : 'badge-danger'; ?>"><?= htmlspecialchars($status?:'–'); ?></span>
                    </td>
                    <td style="color:var(--primary-light);"><?= htmlspecialchars($row['download']??'–'); ?></td>
                    <td><?= htmlspecialchars($row['upload']??'–'); ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="8" style="text-align:center;padding:40px;color:var(--text-muted);">No active sessions.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div></div>
<?php endif; ?>
