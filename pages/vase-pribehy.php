<section class="page-hero" style="position:relative;overflow:hidden">
<?= deco_html([6,1]) ?>
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <a href="<?= SITE_URL ?>/hledam-podporu">Hledám podporu</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span>Vaše příběhy</span>
        </nav>
        <h1>Vaše příběhy</h1>
        <p>Skutečné příběhy lidí, kteří žijí s ADHD a našli svou cestu.</p>
    </div>
</section>

<section class="section" style="position:relative;overflow:hidden">
<?= deco_html([3,0]) ?>
    <div class="container">
        <div class="testimonials-grid">
            <article class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    Díky Akrasii jsem konečně pochopila, že moje ADHD není slabost, ale součást toho, kdo jsem.
                    Terapeutka z adresáře mi doslova změnila život. Po prvním sezení jsem se cítila, jako bych
                    konečně dostala návod k obsluze svého mozku.
                </p>
                <div class="testimonial-author">Markéta, 34 let</div>
                <div class="testimonial-role">diagnostikována v dospělosti</div>
            </article>

            <article class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    Celý život jsem si říkal, že jsem prostě líný a nesoustředěný. Diagnóza v 38 letech
                    byla šok i úleva zároveň. Díky terapii a komunitě Akrasie jsem se naučil pracovat
                    se svým mozkem, ne proti němu.
                </p>
                <div class="testimonial-author">Tomáš, 41 let</div>
                <div class="testimonial-role">IT specialista</div>
            </article>

            <article class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    Jako rodič dítěte s ADHD jsem nevěděla, jak mu nejlépe pomoci. Informace od Akrasie
                    mi pomohly pochopit, co syn prožívá, a jak ho podporovat doma i ve škole.
                </p>
                <div class="testimonial-author">Petra, maminka</div>
                <div class="testimonial-role">syn diagnostikován ve věku 8 let</div>
            </article>

            <article class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    ADHD mi vždycky přinášelo problémy ve škole. Teď na vysoké jsem díky správné
                    podpoře konečně zažila, jaké to je mít výsledky odpovídající mému skutečnému potenciálu.
                </p>
                <div class="testimonial-author">Eliška, 22 let</div>
                <div class="testimonial-role">studentka</div>
            </article>

            <article class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    Myslel jsem, že ADHD mají jen hyperaktivní děti. Zjistit, že i já – klidný a introvertní –
                    mám tuto diagnózu, mi pomohlo přestat se obviňovat za věci, které jsem nemohl ovlivnit.
                </p>
                <div class="testimonial-author">Martin, 29 let</div>
                <div class="testimonial-role">grafický designer</div>
            </article>

            <article class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    Díky adresáři terapeutů jsem našla odbornici, která má sama ADHD a opravdu rozumí
                    tomu, co prožívám. Takový zážitek, být skutečně pochopena, nemá cenu.
                </p>
                <div class="testimonial-author">Lucie, 31 let</div>
                <div class="testimonial-role">učitelka</div>
            </article>
        </div>

        <div style="text-align:center;margin-top:var(--space-16);padding:var(--space-10);background:var(--vanilla);border-radius:var(--radius-lg);">
            <h2 style="font-family:var(--font-display);color:var(--navy);margin-bottom:var(--space-4);">Sdílejte svůj příběh</h2>
            <p style="color:var(--text-muted);max-width:500px;margin:0 auto var(--space-6);">
                Váš příběh může pomoci ostatním. Pokud se chcete podělit o svoji zkušenost s ADHD, napište nám.
            </p>
            <?php
            $cf_id = 'pribeh';
            $cf_subject = 'Sdílení příběhu';
            $cf_btn = 'Odeslat příběh';
            $cf_msg_label = 'Váš příběh';
            $cf_msg_placeholder = 'Popište svoji zkušenost s ADHD. Váš příběh může pomoci ostatním.';
            require __DIR__ . '/../templates/contact-form.php';
            ?>
        </div>
    </div>
</section>
