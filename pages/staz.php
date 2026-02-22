<section class="page-hero" style="position:relative;overflow:hidden">
<?= deco_html([3,8]) ?>
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <a href="<?= SITE_URL ?>/zapojte-se">Zapojte se</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span>Stáž</span>
        </nav>
        <h1>Stáž v Akrasii</h1>
        <p>Získejte smysluplné zkušenosti a přispějte k věci, na které záleží.</p>
    </div>
</section>

<section class="page-content" style="position:relative;overflow:hidden">
<?= deco_html([5,11]) ?>
    <div class="container">
        <div class="page-content-body">
            <h2>Co nabízíme stážistům</h2>
            <p>
                Stáž v Akrasii je příležitost pracovat na smysluplných projektech v přátelském a podporujícím prostředí.
                Jsme si vědomi, že někteří naši stážisté sami žijí s ADHD, a přizpůsobujeme tomu podmínky spolupráce.
            </p>
            <ul>
                <li>Flexibilní pracovní podmínky</li>
                <li>Mentoring od zkušených členů týmu</li>
                <li>Reálné projekty s měřitelným dopadem</li>
                <li>Certifikát o absolvování stáže</li>
                <li>Možnost navázat dlouhodobou spolupráci</li>
            </ul>

            <h2>Oblasti stáží</h2>
            <!-- TODO: doplnit aktuální nabídky stáží -->
            <p>
                Stáže nabízíme v oblastech: komunikace a sociální sítě, grafika a design, fundraising,
                projektové řízení, vzdělávání a osvěta. Aktuální nabídky průběžně zveřejňujeme.
            </p>

            <h2>Jak se přihlásit</h2>
            <p>
                Představte se a řekněte nám, čím byste chtěli přispět. Ozveme se vám s dalšími informacemi.
            </p>
            <?php
            $cf_id = 'staz';
            $cf_subject = 'Stáž – přihláška';
            $cf_btn = 'Odeslat přihlášku';
            $cf_msg_label = 'Představte se';
            $cf_msg_placeholder = 'Řekněte nám o sobě, jakou oblast vás zajímá a kdy byste mohli nastoupit.';
            require __DIR__ . '/../templates/contact-form.php';
            ?>
        </div>
    </div>
</section>
