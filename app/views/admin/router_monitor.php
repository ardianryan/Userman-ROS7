<div class="section-header">
    <div>
        <div class="section-title">Router Monitor</div>
        <div class="section-subtitle">
            <i class="fas fa-server" style="color:var(--primary-light);font-size:11px;margin-right:4px;"></i>
            <strong style="color:var(--primary-light);"><?= htmlspecialchars($data['router']['name']); ?></strong>
            · <?= htmlspecialchars($data['router']['ip_address']); ?>
        </div>
    </div>
    <div style="display:flex;gap:8px;align-items:center;">
        <span id="live-badge" style="display:inline-flex;align-items:center;gap:5px;font-size:11px;padding:4px 10px;background:rgba(40,167,69,0.15);border:1px solid rgba(40,167,69,0.3);color:#4caf8a;border-radius:20px;">
            <span id="live-dot" style="width:7px;height:7px;border-radius:50%;background:#4caf8a;animation:pulse-dot 1.5s infinite;"></span>
            LIVE · <span id="last-update">--:--:--</span>
        </span>
        <a href="<?= BASEURL; ?>/admin/routers" class="btn-secondary btn-sm"><i class="fas fa-arrow-left"></i> Back</a>
    </div>
</div>

<?php if(isset($data['api_error'])): ?>
    <div class="alert alert-danger"><i class="fas fa-triangle-exclamation"></i> <?= htmlspecialchars($data['api_error']); ?></div>
<?php else: ?>

<?php
$res = $data['resource'] ?? [];
$board   = htmlspecialchars($res['board-name']   ?? '–');
$version = htmlspecialchars($res['version']       ?? '–');
$arch    = htmlspecialchars($res['architecture-name'] ?? '–');
$platform= htmlspecialchars($res['platform']      ?? '–');
?>

<!-- ── Stat Cards ──────────────────────────────── -->
<div class="row" style="margin-bottom:20px;">
    <div class="col-md-3 col-6" style="margin-bottom:16px;">
        <div class="stat-card" id="card-cpu">
            <div class="stat-icon"><i class="fas fa-microchip"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="cpu-val">–%</div>
                <div class="stat-label">CPU Load</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6" style="margin-bottom:16px;">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-memory"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="ram-val">–%</div>
                <div class="stat-label" id="ram-label">RAM</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6" style="margin-bottom:16px;">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-hard-drive"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="disk-val">–%</div>
                <div class="stat-label" id="disk-label">Disk</div>
            </div>
        </div>
    </div>
    <div class="col-md-3 col-6" style="margin-bottom:16px;">
        <div class="stat-card">
            <div class="stat-icon"><i class="fas fa-clock-rotate-left"></i></div>
            <div class="stat-info">
                <div class="stat-value" id="uptime-val" style="font-size:14px;">–</div>
                <div class="stat-label">Uptime</div>
            </div>
        </div>
    </div>
</div>

<!-- ── Progress bars ──────────────────────────── -->
<div class="row" style="margin-bottom:20px;">
    <div class="col-md-6" style="margin-bottom:16px;">
        <div class="card">
            <div class="card-header" style="padding-bottom:0;display:flex;justify-content:space-between;align-items:center;">
                <h5><i class="fas fa-microchip" style="color:var(--primary-light);margin-right:6px;"></i>CPU</h5>
                <span id="cpu-pct" style="color:var(--primary-light);font-weight:700;">–%</span>
            </div>
            <div class="card-body">
                <!-- Sparkline canvas -->
                <canvas id="cpu-sparkline" height="40" style="width:100%;margin-bottom:10px;"></canvas>
                <div style="background:rgba(255,255,255,0.05);border-radius:6px;height:10px;overflow:hidden;">
                    <div id="cpu-bar" style="width:0%;height:100%;background:linear-gradient(90deg,var(--primary),var(--primary-light));border-radius:6px;transition:width 0.6s ease;"></div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6" style="margin-bottom:16px;">
        <div class="card">
            <div class="card-header" style="padding-bottom:0;display:flex;justify-content:space-between;align-items:center;">
                <h5><i class="fas fa-memory" style="color:var(--primary-light);margin-right:6px;"></i>RAM</h5>
                <span id="ram-pct" style="color:var(--primary-light);font-weight:700;">–%</span>
            </div>
            <div class="card-body">
                <canvas id="ram-sparkline" height="40" style="width:100%;margin-bottom:10px;"></canvas>
                <div style="background:rgba(255,255,255,0.05);border-radius:6px;height:10px;overflow:hidden;">
                    <div id="ram-bar" style="width:0%;height:100%;background:linear-gradient(90deg,var(--primary),var(--primary-light));border-radius:6px;transition:width 0.6s ease;"></div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Device Info + Interfaces ──────────────── -->
<div class="row">
    <div class="col-md-4" style="margin-bottom:16px;">
        <div class="card" style="height:100%;">
            <div class="card-header"><h5><i class="fas fa-info-circle" style="color:var(--primary-light);margin-right:8px;"></i>Device Info</h5></div>
            <div class="card-body" style="display:flex;flex-direction:column;gap:14px;">
                <?php
                $infos = [
                    ['icon'=>'fa-tag',        'label'=>'Board',    'val'=>$board],
                    ['icon'=>'fa-code-branch','label'=>'ROS Ver',  'val'=>$version],
                    ['icon'=>'fa-microchip',  'label'=>'Arch',     'val'=>$arch],
                    ['icon'=>'fa-hard-drive', 'label'=>'Platform', 'val'=>$platform],
                ];
                foreach($infos as $inf): ?>
                <div style="display:flex;align-items:center;gap:10px;">
                    <span style="width:28px;height:28px;background:rgba(255,107,0,0.12);border-radius:6px;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                        <i class="fas <?= $inf['icon']; ?>" style="font-size:11px;color:var(--primary-light);"></i>
                    </span>
                    <div>
                        <div style="font-size:10px;color:var(--text-muted);"><?= $inf['label']; ?></div>
                        <div style="font-size:13px;font-weight:600;"><?= $inf['val']; ?></div>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        </div>
    </div>

    <div class="col-md-8" style="margin-bottom:16px;">
        <div class="card">
            <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
                <h5><i class="fas fa-network-wired" style="color:var(--primary-light);margin-right:8px;"></i>Interfaces</h5>
                <span style="font-size:11px;color:var(--text-muted);">auto-refresh</span>
            </div>
            <div class="card-body" style="padding:0;">
                <div class="table-container" style="border:none;border-radius:0;">
                    <table class="data-table">
                        <thead><tr><th>Name</th><th>Type</th><th>MAC</th><th>Status</th><th>TX</th><th>RX</th></tr></thead>
                        <tbody id="iface-tbody">
                            <?php if(!empty($data['interfaces'])): foreach($data['interfaces'] as $iface):
                                $running = ($iface['running']??'') === 'true'; ?>
                            <tr>
                                <td><strong><?= htmlspecialchars($iface['name']??''); ?></strong></td>
                                <td style="color:var(--text-muted);font-size:11px;"><?= htmlspecialchars($iface['type']??'–'); ?></td>
                                <td style="font-family:'Courier New',monospace;font-size:11px;color:var(--text-muted);"><?= htmlspecialchars($iface['mac-address']??'–'); ?></td>
                                <td><span class="badge <?= $running?'badge-success':'badge-danger'; ?>"><?= $running?'UP':'DOWN'; ?></span></td>
                                <td style="font-size:12px;color:var(--primary-light);" class="iface-tx"><?= htmlspecialchars($iface['tx-byte']??'–'); ?></td>
                                <td style="font-size:12px;" class="iface-rx"><?= htmlspecialchars($iface['rx-byte']??'–'); ?></td>
                            </tr>
                            <?php endforeach; else: ?>
                            <tr><td colspan="6" style="text-align:center;padding:32px;color:var(--text-muted);">No interface data.</td></tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- ── Active Connections ────────────────────── -->
<div class="card" style="margin-top:4px;">
    <div class="card-header" style="display:flex;align-items:center;justify-content:space-between;">
        <h5><i class="fas fa-plug" style="color:var(--primary-light);margin-right:8px;"></i>Active IP Connections</h5>
        <span class="badge badge-admin" id="conn-count">0</span>
    </div>
    <div class="card-body" style="padding:0;">
        <div class="table-container" style="border:none;border-radius:0;max-height:300px;overflow-y:auto;">
            <table class="data-table">
                <thead><tr><th>Protocol</th><th>Src Address</th><th>Dst Address</th><th>State</th></tr></thead>
                <tbody id="conn-tbody">
                    <tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted);">Loading…</td></tr>
                </tbody>
            </table>
        </div>
    </div>
</div>

<?php endif; ?>

<style>
@keyframes pulse-dot { 0%,100%{opacity:1} 50%{opacity:.3} }
</style>

<script>
(function() {
    const POLL_INTERVAL = 3000; // ms
    const JSON_URL = '<?= BASEURL; ?>/admin/router_monitor_json/<?= $data['router']['id']; ?>';

    // Sparkline history
    const CPU_HISTORY = [];
    const RAM_HISTORY = [];
    const MAX_POINTS  = 30;

    function lerp(arr) { return arr.length ? arr.reduce((a,b)=>a+b,0)/arr.length : 0; }

    function drawSparkline(canvasId, history, color) {
        const canvas = document.getElementById(canvasId);
        if (!canvas) return;
        const ctx    = canvas.getContext('2d');
        const W      = canvas.offsetWidth;
        const H      = canvas.height;
        canvas.width = W;
        ctx.clearRect(0, 0, W, H);
        if (history.length < 2) return;

        const step = W / (MAX_POINTS - 1);
        const grad = ctx.createLinearGradient(0, 0, 0, H);
        grad.addColorStop(0, color + '88');
        grad.addColorStop(1, color + '00');

        ctx.beginPath();
        history.forEach((v, i) => {
            const x = i * step;
            const y = H - (v / 100) * H;
            i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
        });
        // fill
        ctx.lineTo((history.length - 1) * step, H);
        ctx.lineTo(0, H);
        ctx.closePath();
        ctx.fillStyle = grad;
        ctx.fill();

        // line
        ctx.beginPath();
        history.forEach((v, i) => {
            const x = i * step;
            const y = H - (v / 100) * H;
            i === 0 ? ctx.moveTo(x, y) : ctx.lineTo(x, y);
        });
        ctx.strokeStyle = color;
        ctx.lineWidth   = 2;
        ctx.stroke();
    }

    function fmtBytes(b) {
        b = parseInt(b) || 0;
        if (b > 1073741824) return (b/1073741824).toFixed(2) + ' GB';
        if (b > 1048576)    return (b/1048576).toFixed(1)    + ' MB';
        if (b > 1024)       return (b/1024).toFixed(0)       + ' KB';
        return b + ' B';
    }

    function setBar(barId, pct, warnAt = 80) {
        const bar = document.getElementById(barId);
        if (!bar) return;
        bar.style.width = Math.min(pct, 100) + '%';
        bar.style.background = pct > warnAt
            ? 'linear-gradient(90deg,#ff6b6b,#dc3545)'
            : 'linear-gradient(90deg,var(--primary),var(--primary-light))';
    }

    function updateUI(d) {
        const res = d.resource || {};
        const cpu = parseInt(res['cpu-load']) || 0;
        const freeM  = parseInt(res['free-memory'])   || 0;
        const totM   = parseInt(res['total-memory'])  || 1;
        const freeD  = parseInt(res['free-hdd-space'])|| 0;
        const totD   = parseInt(res['total-hdd-space'])||1;

        const ramPct  = Math.round((totM - freeM) / totM * 100);
        const diskPct = Math.round((totD - freeD) / totD * 100);

        // Push history
        CPU_HISTORY.push(cpu);
        RAM_HISTORY.push(ramPct);
        if (CPU_HISTORY.length > MAX_POINTS) CPU_HISTORY.shift();
        if (RAM_HISTORY.length > MAX_POINTS) RAM_HISTORY.shift();

        // Cards
        const setText = (id, txt) => { const el=document.getElementById(id); if(el) el.textContent=txt; };
        setText('cpu-val',    cpu + '%');
        setText('ram-val',    ramPct + '%');
        setText('disk-val',   diskPct + '%');
        setText('uptime-val', res['uptime'] || '–');
        setText('cpu-pct',    cpu + '%');
        setText('ram-pct',    ramPct + '%');
        setText('ram-label',  'RAM · ' + Math.round((totM-freeM)/1048576) + '/' + Math.round(totM/1048576) + ' MB');
        setText('disk-label', 'Disk · ' + Math.round((totD-freeD)/1048576) + '/' + Math.round(totD/1048576) + ' MB');
        setText('last-update',d.ts || '--:--:--');

        // CPU card color
        const cpuCard = document.getElementById('card-cpu');
        if (cpuCard) cpuCard.querySelector('.stat-icon').style.color = cpu > 80 ? '#ff6b6b' : '';

        setBar('cpu-bar', cpu);
        setBar('ram-bar', ramPct);

        drawSparkline('cpu-sparkline', CPU_HISTORY, '#ff6b00');
        drawSparkline('ram-sparkline', RAM_HISTORY, '#ff9a00');

        // Interfaces
        const ifaces = d.interfaces || [];
        const tbody  = document.getElementById('iface-tbody');
        if (tbody && ifaces.length) {
            tbody.innerHTML = ifaces.map(iface => {
                const up = iface['running'] === 'true';
                return `<tr>
                    <td><strong>${iface['name']||''}</strong></td>
                    <td style="color:var(--text-muted);font-size:11px;">${iface['type']||'–'}</td>
                    <td style="font-family:'Courier New',monospace;font-size:11px;color:var(--text-muted);">${iface['mac-address']||'–'}</td>
                    <td><span class="badge ${up?'badge-success':'badge-danger'}">${up?'UP':'DOWN'}</span></td>
                    <td style="font-size:12px;color:var(--primary-light);">${fmtBytes(iface['tx-byte'])}</td>
                    <td style="font-size:12px;">${fmtBytes(iface['rx-byte'])}</td>
                </tr>`;
            }).join('');
        }

        // Connections
        const conns   = d.connections || [];
        const ctbody  = document.getElementById('conn-tbody');
        const ccnt    = document.getElementById('conn-count');
        if (ccnt) ccnt.textContent = conns.length;
        if (ctbody) {
            ctbody.innerHTML = conns.length
                ? conns.map(c => `<tr>
                    <td><span class="badge badge-admin" style="font-size:10px;">${c['protocol']||''}</span></td>
                    <td style="font-family:'Courier New',monospace;font-size:11px;">${c['src-address']||''}</td>
                    <td style="font-family:'Courier New',monospace;font-size:11px;">${c['dst-address']||''}</td>
                    <td style="font-size:11px;color:var(--text-muted);">${c['state']||''}</td>
                  </tr>`).join('')
                : '<tr><td colspan="4" style="text-align:center;padding:24px;color:var(--text-muted);">No active connections.</td></tr>';
        }
    }

    function poll() {
        fetch(JSON_URL, { credentials: 'same-origin' })
            .then(r => r.json())
            .then(d => {
                if (d.error) {
                    document.getElementById('live-dot').style.background = '#dc3545';
                    return;
                }
                document.getElementById('live-dot').style.background = '#4caf8a';
                updateUI(d);
            })
            .catch(() => {
                document.getElementById('live-dot').style.background = '#dc3545';
            });
    }

    // Initial poll immediately on load
    poll();
    setInterval(poll, POLL_INTERVAL);
})();
</script>
