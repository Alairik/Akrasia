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
                <div class="card-icon card-icon--old-rose" aria-hidden="true">🤝</div>
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
