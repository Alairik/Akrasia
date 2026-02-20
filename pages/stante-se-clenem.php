<section class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <a href="<?= SITE_URL ?>/zapojte-se">Zapojte se</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span>Staňte se členem</span>
        </nav>
        <h1>Staňte se členem</h1>
        <p>Jako člen Akrasie se stáváte součástí komunity, která mění způsob, jak Česko vnímá ADHD.</p>
    </div>
</section>

<section class="section section--alt">
    <div class="container">
        <div class="section-header">
            <h2>Co členství obnáší</h2>
        </div>
        <div class="cards-grid">
            <div class="card">
                <div class="card-icon card-icon--navy" aria-hidden="true">🗳️</div>
                <h3>Spolurozhodování</h3>
                <p>Jako člen máte právo hlasovat na valné hromadě a aktivně se podílet na směřování organizace.</p>
            </div>
            <div class="card">
                <div class="card-icon card-icon--petrol" aria-hidden="true">📬</div>
                <h3>Informace jako první</h3>
                <p>Členský newsletter s nejnovějšími informacemi, akcemi a příležitostmi dříve, než jsou zveřejněny.</p>
            </div>
            <div class="card">
                <div class="card-icon card-icon--old-rose" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M10.05 4.575a1.575 1.575 0 1 0-3.15 0v3m3.15-3v-1.5a1.575 1.575 0 0 1 3.15 0v1.5m-3.15 0 .075 5.925m3.075.75V4.575m0 0a1.575 1.575 0 0 1 3.15 0V15M6.9 7.575a1.575 1.575 0 1 0-3.15 0v8.175a6.75 6.75 0 0 0 6.75 6.75h2.018a5.25 5.25 0 0 0 3.712-1.538l1.732-1.732a5.25 5.25 0 0 0 1.538-3.712l.003-2.024a.668.668 0 0 1 .198-.471 1.575 1.575 0 1 0-2.228-2.228 3.818 3.818 0 0 0-1.12 2.687M6.9 7.575V12m6.27 4.318A4.49 4.49 0 0 1 16.16 15h.002"/></svg>
                </div>
                <h3>Komunita</h3>
                <p>Přístup do uzavřené komunity členů, kde sdílíme zkušenosti, podporujeme se a spolupracujeme.</p>
            </div>
        </div>
    </div>
</section>

<section class="page-content">
    <div class="container">
        <div class="page-content-body">
            <h2>Přihláška za člena</h2>
            <p>
                Vyplňte přihlášku a my se vám ozveme s dalšími informacemi.
            </p>
            <!-- TODO: doplnit skutečný formulář nebo odkaz na přihlášku -->
            <form style="max-width:500px;margin-top:var(--space-6);" onsubmit="return false;">
                <div class="form-group">
                    <label for="member-name">Jméno a příjmení</label>
                    <input type="text" id="member-name" name="name" placeholder="Jana Nováková" autocomplete="name">
                </div>
                <div class="form-group">
                    <label for="member-email">E-mail</label>
                    <input type="email" id="member-email" name="email" placeholder="jana@example.cz" autocomplete="email">
                </div>
                <div class="form-group">
                    <label for="member-message">Proč se chcete stát členem? (nepovinné)</label>
                    <textarea id="member-message" name="message" placeholder="Váš příběh nebo motivace..."></textarea>
                </div>
                <button type="submit" class="btn btn-primary">Odeslat přihlášku</button>
                <p style="font-size:var(--text-sm);color:var(--text-muted);margin-top:var(--space-3);">
                    Odesláním formuláře souhlasíte se zpracováním osobních údajů dle našich
                    <a href="<?= SITE_URL ?>/gdpr">zásad ochrany osobních údajů</a>.
                </p>
            </form>
        </div>
    </div>
</section>
