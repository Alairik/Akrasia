/**
 * Akrasia – Adresář terapeutů
 * Načítá data z Google Sheets CSV, renderuje karty, filtruje.
 */
(function () {
    'use strict';

    const CSV_URL   = window.TERAPEUTI_CSV_URL || '';
    const SITE_URL  = window.SITE_URL || '';

    const elGrid    = document.getElementById('terapeuti-grid');
    const elLoading = document.getElementById('terapeuti-loading');
    const elEmpty   = document.getElementById('terapeuti-empty');
    const elCount   = document.getElementById('terapeuti-count');
    const selTyp    = document.getElementById('filter-typ');
    const selKraj   = document.getElementById('filter-kraj');
    const selMesto  = document.getElementById('filter-mesto');
    const btnReset  = document.getElementById('filter-reset');

    let allTerapeuti = [];

    // ── Parsování CSV ────────────────────────────────────────────
    function parseCSV(text) {
        const lines = text.trim().split('\n');
        if (lines.length < 2) return [];

        // Záhlaví: očekáváme sloupce (case-insensitive, trim)
        const headers = lines[0].split(',').map(h => h.trim().replace(/^"|"$/g, '').toLowerCase());

        return lines.slice(1).map(line => {
            const values = splitCSVLine(line);
            const obj = {};
            headers.forEach((h, i) => {
                obj[h] = (values[i] || '').trim().replace(/^"|"$/g, '');
            });
            return obj;
        }).filter(r => r['jméno'] || r['jmeno'] || r['name'] || r['jméno a příjmení']);
    }

    // Jednoduchý CSV parser respektující uvozovky
    function splitCSVLine(line) {
        const result = [];
        let current = '';
        let inQuotes = false;
        for (let i = 0; i < line.length; i++) {
            const ch = line[i];
            if (ch === '"') {
                if (inQuotes && line[i + 1] === '"') { current += '"'; i++; }
                else { inQuotes = !inQuotes; }
            } else if (ch === ',' && !inQuotes) {
                result.push(current); current = '';
            } else {
                current += ch;
            }
        }
        result.push(current);
        return result;
    }

    // ── Normalizace dat terapeuta ─────────────────────────────────
    function normalizeTerapeut(raw) {
        // Podpora různých názvů sloupců
        const get = (...keys) => {
            for (const k of keys) {
                if (raw[k] !== undefined && raw[k] !== '') return raw[k];
            }
            return '';
        };

        return {
            jmeno:  get('jméno a příjmení', 'jméno', 'jmeno', 'name', 'full name'),
            titul:  get('titul', 'title', 'pozice', 'position', 'specializace'),
            typ:    get('typ podpory', 'typ', 'type', 'obor'),
            kraj:   get('kraj', 'region'),
            mesto:  get('město', 'mesto', 'city', 'obec'),
            email:  get('e-mail', 'email', 'kontakt'),
            web:    get('web', 'website', 'url'),
            tel:    get('telefon', 'tel', 'phone'),
            popis:  get('popis', 'bio', 'description', 'o mně'),
            foto:   get('foto', 'photo', 'image', 'fotografie'),
        };
    }

    // ── Render jedné karty ────────────────────────────────────────
    function renderCard(t) {
        const avatar = t.foto
            ? `<img src="${escHtml(t.foto)}" alt="Foto – ${escHtml(t.jmeno)}" loading="lazy">`
            : `<span aria-hidden="true">👤</span>`;

        const kontakt = [];
        if (t.email) kontakt.push(`<a href="mailto:${escHtml(t.email)}" class="btn btn-primary btn-sm" style="width:100%;justify-content:center;">Napsat e-mail</a>`);
        if (t.web)   kontakt.push(`<a href="${escHtml(t.web)}" target="_blank" rel="noopener" class="btn btn-secondary btn-sm" style="width:100%;justify-content:center;margin-top:var(--space-2);">Web terapeuta</a>`);

        const typy = t.typ ? t.typ.split(/[;,]/).map(s => s.trim()).filter(Boolean) : [];
        const tagHtml = typy.map(s => `<span class="terapeutka-tag">${escHtml(s)}</span>`).join('');

        return `
        <article class="terapeutka-card fade-up">
            <div class="terapeutka-header">
                <div class="terapeutka-avatar">${avatar}</div>
                <div>
                    <div class="terapeutka-name">${escHtml(t.jmeno)}</div>
                    ${t.titul ? `<div class="terapeutka-title">${escHtml(t.titul)}</div>` : ''}
                </div>
            </div>
            <div class="terapeutka-body">
                ${t.mesto || t.kraj ? `
                <div class="terapeutka-info">
                    <span class="terapeutka-info-label">Místo:</span>
                    <span>${[t.mesto, t.kraj].filter(Boolean).map(escHtml).join(', ')}</span>
                </div>` : ''}
                ${t.popis ? `
                <div class="terapeutka-info" style="flex-direction:column;gap:var(--space-1);">
                    <span class="terapeutka-info-label">O mně:</span>
                    <span>${escHtml(t.popis.substring(0, 200))}${t.popis.length > 200 ? '…' : ''}</span>
                </div>` : ''}
                ${tagHtml ? `<div class="terapeutka-tags">${tagHtml}</div>` : ''}
            </div>
            ${kontakt.length ? `<div class="terapeutka-footer">${kontakt.join('')}</div>` : ''}
        </article>`;
    }

    function escHtml(str) {
        return String(str)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;');
    }

    // ── Naplnění filtrů ───────────────────────────────────────────
    function populateFilters(terapeuti) {
        const typy   = [...new Set(terapeuti.flatMap(t => t.typ.split(/[;,]/).map(s => s.trim()).filter(Boolean)))].sort();
        const kraje  = [...new Set(terapeuti.map(t => t.kraj).filter(Boolean))].sort();
        const mesta  = [...new Set(terapeuti.map(t => t.mesto).filter(Boolean))].sort();

        appendOptions(selTyp,  typy);
        appendOptions(selKraj, kraje);
        appendOptions(selMesto, mesta);
    }

    function appendOptions(sel, values) {
        values.forEach(v => {
            const opt = document.createElement('option');
            opt.value = v;
            opt.textContent = v;
            sel.appendChild(opt);
        });
    }

    // ── Filtrování a render ───────────────────────────────────────
    function applyFilters() {
        const fTyp   = selTyp.value.toLowerCase();
        const fKraj  = selKraj.value.toLowerCase();
        const fMesto = selMesto.value.toLowerCase();

        const filtered = allTerapeuti.filter(t => {
            const matchTyp   = !fTyp   || t.typ.toLowerCase().includes(fTyp);
            const matchKraj  = !fKraj  || t.kraj.toLowerCase() === fKraj;
            const matchMesto = !fMesto || t.mesto.toLowerCase() === fMesto;
            return matchTyp && matchKraj && matchMesto;
        });

        renderGrid(filtered);
    }

    function renderGrid(terapeuti) {
        // Odstraní předchozí karty (ale ne loading/empty divy)
        Array.from(elGrid.querySelectorAll('.terapeutka-card')).forEach(el => el.remove());

        if (terapeuti.length === 0) {
            elEmpty.hidden = false;
            elCount.textContent = '';
        } else {
            elEmpty.hidden = true;
            const plural = terapeuti.length === 1 ? 'terapeut' : (terapeuti.length < 5 ? 'terapeuti' : 'terapeutů');
            elCount.textContent = `Nalezeno: ${terapeuti.length} ${plural}`;

            const fragment = document.createDocumentFragment();
            const wrapper  = document.createElement('div');
            wrapper.style.display = 'contents';
            wrapper.innerHTML = terapeuti.map(renderCard).join('');
            fragment.appendChild(wrapper);
            elGrid.appendChild(fragment);

            // Spuštění animací
            requestAnimationFrame(() => {
                elGrid.querySelectorAll('.fade-up').forEach(el => {
                    el.classList.add('visible');
                });
            });
        }
    }

    // ── Načtení dat ───────────────────────────────────────────────
    async function loadTerapeuti() {
        if (!CSV_URL) {
            elLoading.innerHTML = '<p>Adresář terapeutů bude brzy dostupný.</p>';
            return;
        }

        try {
            const resp = await fetch(CSV_URL, { cache: 'no-cache' });
            if (!resp.ok) throw new Error(`HTTP ${resp.status}`);

            const text = await resp.text();
            const raw  = parseCSV(text);
            allTerapeuti = raw.map(normalizeTerapeut);

            elLoading.hidden = true;
            populateFilters(allTerapeuti);
            renderGrid(allTerapeuti);

        } catch (err) {
            console.error('Nelze načíst adresář terapeutů:', err);
            elLoading.innerHTML = `
                <p>Adresář se nepodařilo načíst. Zkuste to prosím za chvíli.</p>
                <button class="btn btn-secondary btn-sm" onclick="location.reload()" style="margin-top:1rem;">Zkusit znovu</button>
            `;
        }
    }

    // ── Události filtrů ───────────────────────────────────────────
    selTyp.addEventListener('change',  applyFilters);
    selKraj.addEventListener('change', applyFilters);
    selMesto.addEventListener('change', applyFilters);

    btnReset.addEventListener('click', () => {
        selTyp.value  = '';
        selKraj.value = '';
        selMesto.value = '';
        applyFilters();
    });

    // ── Start ─────────────────────────────────────────────────────
    loadTerapeuti();

})();
