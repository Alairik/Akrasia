<?php
// Terapeuti – potřebuje extra JS soubor
$extraScript = 'terapeuti.js';
$metaDescription = 'Adresář ověřených terapeutů specializovaných na ADHD. Filtrujte podle kraje, města nebo typu podpory.';
?>

<section class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <a href="<?= SITE_URL ?>/hledam-podporu">Hledám podporu</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span>Adresář terapeutů</span>
        </nav>
        <h1>Adresář terapeutů</h1>
        <p>Ověření odborníci specializovaní na ADHD. Filtrujte podle kraje, města nebo typu podpory.</p>
    </div>
</section>

<section class="section section--sm">
    <div class="container">

        <!-- Filtry -->
        <div class="terapeuti-filters" role="search" aria-label="Filtry terapeutů">
            <div class="filter-group">
                <label for="filter-typ">Typ podpory</label>
                <select id="filter-typ" aria-label="Filtrovat podle typu podpory">
                    <option value="">Všechny typy</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-kraj">Kraj</label>
                <select id="filter-kraj" aria-label="Filtrovat podle kraje">
                    <option value="">Všechny kraje</option>
                </select>
            </div>
            <div class="filter-group">
                <label for="filter-mesto">Město</label>
                <select id="filter-mesto" aria-label="Filtrovat podle města">
                    <option value="">Všechna města</option>
                </select>
            </div>
            <button class="filter-reset" id="filter-reset" type="button" aria-label="Zrušit filtry">
                Zrušit filtry
            </button>
        </div>

        <!-- Počet výsledků -->
        <p class="terapeuti-count" id="terapeuti-count" aria-live="polite"></p>

        <!-- Grid terapeutů -->
        <div id="terapeuti-grid" class="terapeuti-grid" aria-label="Seznam terapeutů">
            <!-- Načítá se přes JavaScript -->
            <div class="terapeuti-loading" id="terapeuti-loading">
                <div class="spinner" aria-hidden="true"></div>
                <p>Načítám seznam terapeutů…</p>
            </div>
        </div>

        <!-- Prázdný stav (skrytý) -->
        <div class="terapeuti-empty" id="terapeuti-empty" hidden aria-live="polite">
            <p style="font-size:var(--text-xl);margin-bottom:var(--space-3);">🔍</p>
            <p>Žádný terapeut neodpovídá zvoleným filtrům.</p>
            <button class="btn btn-secondary btn-sm" onclick="document.getElementById('filter-reset').click()" style="margin-top:var(--space-4);">
                Zrušit filtry
            </button>
        </div>

    </div>
</section>

<!-- Konfigurace – CSV URL se předá do JS -->
<script>
    window.TERAPEUTI_CSV_URL = <?= json_encode(TERAPEUTI_CSV_URL) ?>;
    window.SITE_URL = <?= json_encode(SITE_URL) ?>;
</script>

<section class="section--sm" style="background:var(--vanilla);padding:var(--space-12) 0;">
    <div class="container" style="text-align:center;">
        <h2 style="font-family:var(--font-display);color:var(--navy);margin-bottom:var(--space-4);">Jste terapeut?</h2>
        <p style="color:var(--text-muted);max-width:500px;margin:0 auto var(--space-6);">
            Chcete být zařazeni do našeho adresáře? Pracujeme s ověřenými odborníky se specializací na ADHD.
        </p>
        <!-- TODO: doplnit kontaktní odkaz nebo formulář pro terapeuty -->
    </div>
</section>
