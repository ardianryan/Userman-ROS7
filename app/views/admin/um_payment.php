<?php require_once __DIR__ . '/um_tabs.php'; ?>
<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>
<div class="card"><div class="card-body" style="padding:0;">
    <div class="table-container" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead><tr><th>#</th><th>User</th><th>Trans. ID</th><th>Method</th><th>Amount</th><th>Currency</th><th>Time</th></tr></thead>
            <tbody>
                <?php if(!empty($data['rows'])): $i=1; foreach($data['rows'] as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i++; ?></td>
                    <td><strong><?= htmlspecialchars($row['customer']??($row['user']??'')); ?></strong></td>
                    <td><span class="badge badge-admin"><?= htmlspecialchars($row['profile']??''); ?></span></td>
                    <td style="font-family:'Courier New',monospace;"><?= htmlspecialchars($row['trans-id']??$row['.id']??''); ?></td>
                    <td><?= htmlspecialchars($row['method']??'–'); ?></td>
                    <td style="color:var(--primary-light);font-weight:600;"><?= htmlspecialchars($row['trans-amount']??($row['price']??'–')); ?></td>
                    <td><?= htmlspecialchars($row['currency']??'–'); ?></td>
                    <td style="font-size:11px;"><?= htmlspecialchars($row['time']??'–'); ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="7" style="text-align:center;padding:40px;color:var(--text-muted);">No payment records found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div></div>
<?php endif; ?>
