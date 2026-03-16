<?php require_once __DIR__ . '/um_tabs.php'; ?>
<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>
<div class="card"><div class="card-body" style="padding:0;">
    <div class="table-container" style="border:none;border-radius:0;">
        <table class="data-table">
            <thead><tr><th>#</th><th>Name</th><th>IP Address</th><th>Secret</th><th>Auth Port</th><th>Acct Port</th></tr></thead>
            <tbody>
                <?php if(!empty($data['rows'])): $i=1; foreach($data['rows'] as $row): ?>
                <tr>
                    <td style="color:var(--text-muted);"><?= $i++; ?></td>
                    <td><strong><?= htmlspecialchars($row['name']??''); ?></strong></td>
                    <td style="font-family:'Courier New',monospace;color:var(--primary-light);"><?= htmlspecialchars($row['address']??($row['ip-address']??'')); ?></td>
                    <td style="font-family:'Courier New',monospace;color:var(--text-muted);"><?= str_repeat('•', strlen($row['shared-secret']??'')); ?></td>
                    <td><?= htmlspecialchars($row['accounting-port']??'1813'); ?></td>
                    <td><?= htmlspecialchars($row['authentication-port']??'1812'); ?></td>
                </tr>
                <?php endforeach; else: ?>
                <tr><td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">No RADIUS routers configured in User Manager.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div></div>
<?php endif; ?>
