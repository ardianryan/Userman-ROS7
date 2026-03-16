<?php require_once __DIR__ . '/um_tabs.php'; ?>
<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>
<div class="card"><div class="card-body" style="padding:0;">
    <div class="table-container" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead><tr><th>#</th><th>User</th><th>Attribute</th><th>Value</th><th>Vendor</th></tr></thead>
            <tbody>
                <?php if(!empty($data['rows'])): $i=1; foreach($data['rows'] as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i++; ?></td>
                    <td><strong><?= htmlspecialchars($row['customer']??($row['user']??'')); ?></strong></td>
                    <td style="font-family:'Courier New',monospace;"><?= htmlspecialchars($row['type-id']??($row['name']??'')); ?></td>
                    <td><?= htmlspecialchars($row['value']??'–'); ?></td>
                    <td style="color:var(--text-muted);"><?= htmlspecialchars($row['vendor-id']??'–'); ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="5" style="text-align:center;padding:40px;color:var(--text-muted);">No attributes found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div></div>
<?php endif; ?>
