/**
 * Akrasia – Adresář terapeutů
 * Data načítá z PHP proxy (/api/terapeuti.php), která stahuje Google Sheets CSV
 * server-side (bez CORS). Sloupce se párují flexibilně – funguje s jakýmikoli
 * českými nebo anglickými názvy záhlaví.
 */
(function () {
    'use strict';

    const API_URL  = window.TERAPEUTI_API_URL || '/api/terapeuti.php';
    const SITE_URL = window.SITE_URL || '';

    const elGrid    = document.getElementById('terapeuti-grid');
    const elLoading = document.getElementById('terapeuti-loading');
    const elEmpty   = document.getElementById('terapeuti-empty');
    const elCount   = document.getElementById('terapeuti-count');
    const selTyp    = document.getElementById('filter-typ');
    const selKraj   = document.getElementById('filter-kraj');
    const selMesto  = document.getElementById('filter-mesto');
    const btnReset  = document.getElementById('filter-reset');

    let allTerapeuti = [];

    // ── Flexibilní hledání sloupce ───────────────────────────────────────────
    // Porovnání bez diakritiky a bez ohledu na velikost písmen.
    function stripDia(s) {
        return s.normalize('NFD').replace(/\p{Mn}/gu, '').toLowerCase();
    }

    function findCol(columns, ...fragments) {
        const frags = fragments.map(stripDia);
        for (const col of columns) {
            const norm = stripDia(col);
            if (frags.some(f => norm.includes(f))) return col;
        }
        return null;
    }

    // ── Mapování záhlaví tabulky na interní pole ─────────────────────────────
    function buildMapping(columns) {
        return {
            jmeno : findCol(columns, 'jmen', 'prijmen', 'name', 'full'),
            titul : findCol(columns, 'titul', 'title', 'pozice', 'position'),
            typ   : findCol(columns, 'typ', 'type', 'obor', 'specializ', 'podpora'),
            kraj  : findCol(columns, 'kraj', 'region'),
            mesto : findCol(columns, 'mest', 'city', 'obec', 'mist'),
            email : findCol(columns, 'mail', 'email', 'e-mail'),
            web   : findCol(columns, 'web', 'url', 'site', 'stranka'),
            tel   : findCol(columns, 'tel', 'phone', 'mobil'),
            popis : findCol(columns, 'popis', 'bio', 'desc', 'o mn', 'informac'),
            foto  : findCol(columns, 'foto', 'photo', 'image', 'avatar', 'fotografie'),
        };
    }

    // ── Normalizace jednoho řádku ────────────────────────────────────────────
    function normalizeRow(raw, map) {
        const g = key => (map[key] ? (raw[map[key]] || '') : '');
        return {
            jmeno : g('jmeno'),
            titul : g('titul'),
            typ   : g('typ'),
            kraj  : g('kraj'),
            mesto : g('mesto'),
            email : g('email'),
            web   : g('web'),
            tel   : g('tel'),
            popis : g('popis'),
            foto  : g('foto'),
        };
    }

    // ── Render jedné karty ───────────────────────────────────────────────────
    function renderCard(t) {
        const avatar = t.foto
            ? `<img src="${esc(t.foto)}" alt="Foto – ${esc(t.jmeno)}" loading="lazy">`
            : `<svg width="32" height="32" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round" style="color:var(--text-muted)" aria-hidden="true"><path d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/></svg>`;

        const kontakt = [];
        if (t.email) kontakt.push(`<a href="mailto:${esc(t.email)}" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">Napsat e-mail</a>`);
        if (t.web)   kontakt.push(`<a href="${esc(t.web)}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;margin-top:var(--space-2);">Web terapeuta</a>`);

        const typy    = t.typ ? t.typ.split(/[;,]/).map(s => s.trim()).filter(Boolean) : [];
        const tagHtml = typy.map(s => `<span class="terapeutka-tag">${esc(s)}</span>`).join('');

        return `
        <article class="terapeutka-card fade-up">
            <div class="terapeutka-header">
                <div class="terapeutka-avatar">${avatar}</div>
                <div>
                    <div class="terapeutka-name">${esc(t.jmeno)}</div>
                    ${t.titul ? `<div class="terapeutka-title">${esc(t.titul)}</div>` : ''}
                </div>
            </div>
            <div class="terapeutka-body">
                ${t.mesto || t.kraj ? `
                <div class="terapeutka-info">
                    <span class="terapeutka-info-label">Místo:</span>
                    <span>${[t.mesto, t.kraj].filter(Boolean).map(esc).join(', ')}</span>
                </div>` : ''}
                ${t.popis ? `
                <div class="terapeutka-info" style="flex-direction:column;gap:var(--space-1);">
                    <span class="terapeutka-info-label">O mně:</span>
                    <span>${esc(t.popis.substring(0, 200))}${t.popis.length > 200 ? '…' : ''}</span>
                </div>` : ''}
                ${tagHtml ? `<div class="terapeutka-tags">${tagHtml}</div>` : ''}
            </div>
            ${kontakt.length ? `<div class="terapeutka-footer">${kontakt.join('')}</div>` : ''}
        </article>`;
    }

    function esc(str) {
        return String(str)
            .replace(/&/g, '&amp;').replace(/</g, '&lt;')
            .replace(/>/g, '&gt;').replace(/"/g, '&quot;');
    }

    // ── Naplnění filtrů ──────────────────────────────────────────────────────
    function populateFilters(terapeuti) {
        const typy  = [...new Set(terapeuti.flatMap(t => t.typ.split(/[;,]/).map(s => s.trim()).filter(Boolean)))].sort();
        const kraje = [...new Set(terapeuti.map(t => t.kraj).filter(Boolean))].sort();
        const mesta = [...new Set(terapeuti.map(t => t.mesto).filter(Boolean))].sort();

        appendOptions(selTyp,   typy);
        appendOptions(selKraj,  kraje);
        appendOptions(selMesto, mesta);
    }

    function appendOptions(sel, values) {
        values.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v; opt.textContent = v;
            sel.appendChild(opt);
        });
    }

    // ── Filtrování a render ──────────────────────────────────────────────────
    function applyFilters() {
        const fTyp   = selTyp.value.toLowerCase();
        const fKraj  = selKraj.value.toLowerCase();
        const fMesto = selMesto.value.toLowerCase();

        const filtered = allTerapeuti.filter(t => {
            const okTyp   = !fTyp   || t.typ.toLowerCase().includes(fTyp);
            const okKraj  = !fKraj  || t.kraj.toLowerCase() === fKraj;
            const okMesto = !fMesto || t.mesto.toLowerCase() === fMesto;
            return okTyp && okKraj && okMesto;
        });

        renderGrid(filtered);
    }

    function renderGrid(terapeuti) {
        Array.from(elGrid.querySelectorAll('.terapeutka-card')).forEach(el => el.remove());

        if (terapeuti.length === 0) {
            elEmpty.hidden = false;
            elCount.textContent = '';
        } else {
            elEmpty.hidden = true;
            const n   = terapeuti.length;
            const lbl = n === 1 ? 'terapeut' : (n < 5 ? 'terapeuti' : 'terapeutů');
            elCount.textContent = `Nalezeno: ${n} ${lbl}`;

            const wrapper = document.createElement('div');
            wrapper.style.display = 'contents';
            wrapper.innerHTML = terapeuti.map(renderCard).join('');
            elGrid.appendChild(wrapper);

            requestAnimationFrame(() => {
                elGrid.querySelectorAll('.fade-up').forEach(el => el.classList.add('visible'));
            });
        }
    }

    // ── Chybový stav ─────────────────────────────────────────────────────────
    function showError(msg) {
        elLoading.innerHTML = `
            <div style="grid-column:1/-1;text-align:center;padding:var(--space-16);color:var(--text-muted)">
                <p>${esc(msg)}</p>
                <button class="btn btn-secondary btn-sm" onclick="location.reload()" style="margin-top:1rem;">Zkusit znovu</button>
            </div>`;
    }

    // ── Načtení dat z PHP proxy ──────────────────────────────────────────────
    async function loadTerapeuti() {
        try {
            const resp = await fetch(API_URL, { cache: 'no-cache' });
            if (!resp.ok) throw new Error(`HTTP ${resp.status}`);

            const json = await resp.json();

            if (json.error) {
                console.error('[terapeuti]', json.error);
                showError(json.error);
                return;
            }

            const { columns, data } = json;

            // Výpis do konzole pro snadné ladění názvů sloupců
            console.info('[terapeuti] Sloupce v tabulce:', columns);

            const map = buildMapping(columns);
            console.info('[terapeuti] Mapování:', map);

            allTerapeuti = data
                .map(row => normalizeRow(row, map))
                .filter(t => t.jmeno.trim() !== '');

            if (allTerapeuti.length === 0) {
                const hint = columns.length
                    ? `Nepodařilo se najít sloupec se jménem terapeuta. Dostupné sloupce: ${columns.join(', ')}`
                    : 'Tabulka je prázdná nebo nemá záhlaví.';
                console.warn('[terapeuti]', hint);
                showError('Adresář zatím neobsahuje žádné záznamy.');
                return;
            }

            elLoading.style.display = 'none';
            populateFilters(allTerapeuti);
            renderGrid(allTerapeuti);

        } catch (err) {
            console.error('[terapeuti] Chyba:', err);
            showError('Adresář se nepodařilo načíst. Zkuste to prosím za chvíli.');
        }
    }

    // ── Události filtrů ──────────────────────────────────────────────────────
    selTyp.addEventListener('change',  applyFilters);
    selKraj.addEventListener('change', applyFilters);
    selMesto.addEventListener('change', applyFilters);
    btnReset.addEventListener('click', () => {
        selTyp.value = selKraj.value = selMesto.value = '';
        applyFilters();
    });

    // ── Start ────────────────────────────────────────────────────────────────
    loadTerapeuti();

})();
