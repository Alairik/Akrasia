<section class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span>Hledám podporu</span>
        </nav>
        <h1>Hledám podporu</h1>
        <p>Máte ADHD nebo podezření na diagnózu? Pomůžeme vám najít správnou cestu.</p>
    </div>
</section>

<!-- Hlavní CTA – adresář terapeutů -->
<section class="section section--old-rose">
    <div class="container" style="text-align:center;">
        <h2 style="font-family:var(--font-display);font-size:var(--text-4xl);color:var(--navy);margin-bottom:var(--space-4);">Najděte svého terapeuta</h2>
        <p style="font-size:var(--text-xl);color:var(--text-muted);max-width:600px;margin:0 auto var(--space-8);">
            Náš adresář obsahuje ověřené terapeuty a odborníky specializované na ADHD po celé České republice.
            Filtrujte podle kraje, města nebo specializace.
        </p>
        <a href="<?= SITE_URL ?>/terapeuti" class="btn btn-primary" style="font-size:var(--text-lg);padding:var(--space-4) var(--space-10);">
            Otevřít adresář terapeutů
            <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="page-content-body">
            <h2>Kde začít?</h2>
            <p>
                Pokud máte podezření na ADHD nebo jste právě dostali diagnózu, může být těžké vědět,
                kam se obrátit. Zde jsou základní kroky, které vám pomohou zorientovat se.
            </p>

            <h3>1. Získejte diagnózu</h3>
            <p>
                Pokud ještě nemáte diagnózu, prvním krokem je návštěva praktického lékaře nebo psychiatra.
                Požádejte o doporučení na specializované vyšetření ADHD.
                <!-- TODO: doplnit konkrétní rady nebo odkaz na zdroje -->
            </p>

            <h3>2. Najděte odbornou pomoc</h3>
            <p>
                Terapie, koučink nebo psychiatrická péče – každý potřebuje něco jiného.
                V našem <a href="<?= SITE_URL ?>/terapeuti">adresáři terapeutů</a> najdete ověřené odborníky,
                kteří mají zkušenosti s ADHD a jsou připraveni vám pomoci.
            </p>

            <h3>3. Najděte komunitu</h3>
            <p>
                Sdílení zkušeností s lidmi, kteří vás chápou, může být nesmírně léčivé.
                Přečtěte si <a href="<?= SITE_URL ?>/vase-pribehy">příběhy ostatních</a>
                nebo se zapojte do naší komunity.
            </p>
        </div>

        <div class="cards-grid" style="margin-top:var(--space-10);">
            <a href="<?= SITE_URL ?>/terapeuti" class="card" style="text-decoration:none;">
                <div class="card-icon card-icon--petrol" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                </div>
                <h3>Adresář terapeutů</h3>
                <p>Ověření odborníci specializovaní na ADHD ve vašem okolí.</p>
                <span class="read-more" style="margin-top:var(--space-4);">Najít terapeuta →</span>
            </a>
            <a href="<?= SITE_URL ?>/vase-pribehy" class="card" style="text-decoration:none;">
                <div class="card-icon card-icon--old-rose" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M7.5 8.25h9m-9 3H12m-9.75 1.51c0 1.6 1.123 2.994 2.707 3.227 1.129.166 2.27.293 3.423.379.35.026.67.21.865.501L12 21l2.755-4.133a1.14 1.14 0 0 1 .865-.501 48.172 48.172 0 0 0 3.423-.379c1.584-.233 2.707-1.626 2.707-3.228V6.741c0-1.602-1.123-2.995-2.707-3.228A48.394 48.394 0 0 0 12 3c-2.392 0-4.744.175-7.043.513C3.373 3.746 2.25 5.14 2.25 6.741v6.018z"/></svg>
                </div>
                <h3>Vaše příběhy</h3>
                <p>Přečtěte si, jak ostatní zvládají ADHD v každodenním životě.</p>
                <span class="read-more" style="margin-top:var(--space-4);">Číst příběhy →</span>
            </a>
            <a href="<?= SITE_URL ?>/blog" class="card" style="text-decoration:none;">
                <div class="card-icon card-icon--mint" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <h3>Blog</h3>
                <p>Informace, tipy a inspirace pro život s ADHD.</p>
                <span class="read-more" style="margin-top:var(--space-4);">Číst blog →</span>
            </a>
        </div>
    </div>
</section>
