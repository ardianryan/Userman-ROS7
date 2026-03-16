document.addEventListener('DOMContentLoaded', () => {

    // ===== LIVE CLOCK =====
    const clockEl = document.getElementById('live-clock');
    if (clockEl) {
        function tick() {
            const now = new Date();
            clockEl.textContent = now.toLocaleTimeString('en-GB', { hour: '2-digit', minute: '2-digit', second: '2-digit' });
        }
        tick();
        setInterval(tick, 1000);
    }

    // ===== SIDEBAR MOBILE TOGGLE =====
    const hamburgerBtn = document.getElementById('hamburger-btn');
    const sidebar = document.getElementById('sidebar');
    const overlay = document.getElementById('sidebar-overlay');

    function openSidebar() {
        sidebar && sidebar.classList.add('open');
        overlay && overlay.classList.add('show');
        document.body.style.overflow = 'hidden';
    }
    function closeSidebar() {
        sidebar && sidebar.classList.remove('open');
        overlay && overlay.classList.remove('show');
        document.body.style.overflow = '';
    }

    hamburgerBtn && hamburgerBtn.addEventListener('click', openSidebar);
    overlay && overlay.addEventListener('click', closeSidebar);

    // Active nav state is handled server-side (PHP activePage + umTab checks in sidebar.php).

    // ===== AUTO TABLE PAGINATION =====
    initAllTablePaginations();
});

/**
 * Applies client-side pagination to every .data-table that has a <tbody> with rows.
 * Tables inside the router monitor are excluded (their data refreshes via JS).
 */
function initAllTablePaginations() {
    document.querySelectorAll('.data-table').forEach(table => {
        // Skip tables managed by realtime JS (router monitor)
        if (table.id === 'iface-table' || table.id === 'conn-table') return;
        const tbody = table.querySelector('tbody');
        if (!tbody) return;
        const allRows = Array.from(tbody.querySelectorAll('tr'));
        if (allRows.length <= 1) return; // nothing to paginate (empty row or single row)

        applyPagination(table, tbody, allRows);
    });
}

function applyPagination(table, tbody, allRows) {
    // ── State ────────────────────────────────────────
    let perPage  = 10;
    let curPage  = 1;

    // ── Wrapper ──────────────────────────────────────
    const wrapper = document.createElement('div');
    wrapper.className = 'table-pagination-wrapper';
    table.parentNode.insertBefore(wrapper, table);
    wrapper.appendChild(table);

    // ── Controls ─────────────────────────────────────
    const ctrl = document.createElement('div');
    ctrl.className = 'table-pagination-ctrl';
    ctrl.innerHTML = `
        <div class="tp-left">
            Show
            <select class="tp-perpage">
                <option value="10"  selected>10</option>
                <option value="25">25</option>
                <option value="50">50</option>
                <option value="100">100</option>
                <option value="0">All</option>
            </select>
            entries
        </div>
        <div class="tp-right">
            <span class="tp-info"></span>
            <div class="tp-pages"></div>
        </div>
    `;
    wrapper.appendChild(ctrl);

    const selPerPage = ctrl.querySelector('.tp-perpage');
    const infoEl     = ctrl.querySelector('.tp-info');
    const pagesEl    = ctrl.querySelector('.tp-pages');

    selPerPage.addEventListener('change', () => {
        perPage = parseInt(selPerPage.value);
        curPage = 1;
        render();
    });

    function totalPages() {
        if (perPage === 0) return 1;
        return Math.max(1, Math.ceil(allRows.length / perPage));
    }

    function render() {
        const total = allRows.length;
        const tp    = totalPages();
        if (curPage > tp) curPage = tp;

        // Show/hide rows
        allRows.forEach((tr, i) => {
            if (perPage === 0) {
                tr.style.display = '';
            } else {
                const from = (curPage - 1) * perPage;
                const to   = from + perPage;
                tr.style.display = (i >= from && i < to) ? '' : 'none';
            }
        });

        // Info text
        if (perPage === 0) {
            infoEl.textContent = `Showing all ${total} entries`;
        } else {
            const from = Math.min((curPage - 1) * perPage + 1, total);
            const to   = Math.min(curPage * perPage, total);
            infoEl.textContent = `${from}–${to} of ${total}`;
        }

        // Page buttons
        pagesEl.innerHTML = '';
        if (perPage === 0 || tp <= 1) return;

        const makeBtn = (label, page, disabled, active) => {
            const btn = document.createElement('button');
            btn.className = 'tp-btn' + (active ? ' tp-btn-active' : '') + (disabled ? ' tp-btn-disabled' : '');
            btn.textContent = label;
            btn.disabled = disabled;
            btn.addEventListener('click', () => { curPage = page; render(); });
            return btn;
        };

        pagesEl.appendChild(makeBtn('‹', curPage - 1, curPage === 1, false));

        // Show limited window of page numbers
        let start = Math.max(1, curPage - 2);
        let end   = Math.min(tp, curPage + 2);
        if (start > 1) { pagesEl.appendChild(makeBtn('1', 1, false, false)); if (start > 2) pagesEl.appendChild(Object.assign(document.createElement('span'), {className:'tp-ellipsis', textContent:'…'})); }
        for (let p = start; p <= end; p++) pagesEl.appendChild(makeBtn(p, p, false, p === curPage));
        if (end < tp) { if (end < tp - 1) pagesEl.appendChild(Object.assign(document.createElement('span'), {className:'tp-ellipsis', textContent:'…'})); pagesEl.appendChild(makeBtn(tp, tp, false, false)); }

        pagesEl.appendChild(makeBtn('›', curPage + 1, curPage === tp, false));
    }

    render();
}
