<?php
// Homepage – Akrasia
// Načtení posledních článků pro blog sekci
$latestArticles = articles_list(3, 0, 'published');
?>

<!-- ========== 1. HERO ========== -->
<section class="hero" aria-label="Úvodní sekce">
    <div class="container">
        <span class="hero-tagline">Nezisková organizace</span>

        <h1>Prostor, který<br>ADHD rozumí.</h1>

        <p class="hero-subtitle">
            Pomáháme lidem s ADHD najít podporu, porozumění a cestu vpřed.
            Propojujeme vás s ověřenými terapeuty a komunitou, která chápe.
        </p>

        <div class="hero-btns">
            <a href="<?= SITE_URL ?>/hledam-podporu" class="btn btn-primary">
                Hledám podporu
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
            <a href="<?= SITE_URL ?>/kdo-jsme" class="btn btn-secondary">Kdo jsme</a>
        </div>
    </div>
</section>


<!-- ========== 2. STATISTIKY ========== -->
<section class="section section--alt" aria-label="Fakta o ADHD">
    <div class="container">
        <div class="section-header">
            <h2>ADHD v číslech</h2>
            <p>Realita, která nás motivuje jednat a podporovat.</p>
        </div>
        <div class="stats-grid">
            <div class="stat-card fade-up">
                <div class="stat-number">5–7&nbsp;%</div>
                <div class="stat-label">dospělých má ADHD</div>
            </div>
            <div class="stat-card fade-up">
                <div class="stat-number">80&nbsp;%</div>
                <div class="stat-label">případů zůstává nediagnostikováno</div>
            </div>
            <div class="stat-card fade-up">
                <div class="stat-number">3×</div>
                <div class="stat-label">větší riziko deprese a úzkosti</div>
            </div>
            <div class="stat-card fade-up">
                <div class="stat-number">✓</div>
                <div class="stat-label">ověření terapeuti v našem adresáři</div>
            </div>
        </div>
    </div>
</section>


<!-- ========== 3. NAŠE HODNOTY ========== -->
<section class="section" aria-label="Naše hodnoty">
    <div class="container">
        <div class="section-header">
            <h2>Naše hodnoty</h2>
            <p>Věříme, že každý člověk si zaslouží prostor, kde bude pochopen a podpořen.</p>
        </div>
        <div class="cards-grid">
            <div class="card fade-up">
                <div class="card-icon card-icon--old-rose" aria-hidden="true">🤝</div>
                <h3>Podpora a péče</h3>
                <p>Vytváříme bezpečné prostředí, kde se každý může cítit přijat a pochopen bez odsuzování.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--navy" aria-hidden="true">🎓</div>
                <h3>Odbornost</h3>
                <p>Spolupracujeme výhradně s ověřenými odborníky a zakládáme si na vědecky podložených informacích.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--petrol" aria-hidden="true">🌱</div>
                <h3>Růst</h3>
                <p>Věříme v potenciál každého člověka s ADHD. Pomáháme přetvářet výzvy v příležitosti.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--mint" aria-hidden="true">♿</div>
                <h3>Přístupnost</h3>
                <p>Dbáme na to, aby naše služby a informace byly dostupné pro všechny, bez ohledu na situaci.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--pear" aria-hidden="true">💬</div>
                <h3>Komunita</h3>
                <p>Budujeme síť lidí, kteří si navzájem rozumí a mohou sdílet zkušenosti i rady.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--lilac" aria-hidden="true">🔍</div>
                <h3>Transparentnost</h3>
                <p>Jednáme otevřeně a poctivě – vůči lidem, které podporujeme, i vůči partnerům a dárcům.</p>
            </div>
        </div>
    </div>
</section>


<!-- ========== 4. NAŠE AKTIVITY ========== -->
<section class="section section--old-rose" aria-label="Naše aktivity">
    <div class="container">
        <div class="section-header">
            <h2>Co děláme</h2>
            <p>Pracujeme na tom, aby ADHD přestalo být překážkou a stalo se součástí pestrého světa.</p>
        </div>
        <div class="cards-grid--4 cards-grid">
            <div class="card fade-up">
                <div class="card-icon card-icon--navy" aria-hidden="true">📚</div>
                <h3>Vzdělávání</h3>
                <p>Šíříme povědomí o ADHD – přednášky, workshopy, osvětové materiály pro veřejnost.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--petrol" aria-hidden="true">🩺</div>
                <h3>Adresář terapeutů</h3>
                <p>Propojujeme lidi s ověřenými terapeuty a odborníky specializovanými na ADHD.</p>
                <a href="<?= SITE_URL ?>/terapeuti" class="read-more" style="margin-top:var(--space-4)">
                    Najít terapeuta →
                </a>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--pear" aria-hidden="true">🏢</div>
                <h3>Firmy a školy</h3>
                <p>Pomáháme zaměstnavatelům a školám vytvářet inkluzivní prostředí pro lidi s ADHD.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--old-rose" aria-hidden="true">✍️</div>
                <h3>Blog a komunita</h3>
                <p>Píšeme, sdílíme příběhy a budujeme komunitu lidí, kteří si navzájem rozumí.</p>
                <a href="<?= SITE_URL ?>/blog" class="read-more" style="margin-top:var(--space-4)">
                    Číst blog →
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ========== 5. ROZCESTNÍK ========== -->
<section class="section" aria-label="Kdo jste?">
    <div class="container">
        <div class="section-header">
            <h2>Kdo jste?</h2>
            <p>Najděte cestu, která je určena právě vám.</p>
        </div>
        <div class="junction-grid">
            <a href="<?= SITE_URL ?>/hledam-podporu" class="junction-card junction-card--support fade-up">
                <div class="junction-icon" aria-hidden="true">🧠</div>
                <h3>Hledám podporu</h3>
                <p>Máte ADHD nebo podezření na diagnózu? Pomůžeme vám najít správnou cestu k odborné péči.</p>
                <span class="junction-link">Zjistit více →</span>
            </a>
            <a href="<?= SITE_URL ?>/pro-firmy" class="junction-card junction-card--company fade-up">
                <div class="junction-icon" aria-hidden="true">🏢</div>
                <h3>Jsem firma</h3>
                <p>Chcete vytvořit inkluzivní pracovní prostředí? Pomůžeme vám pochopit potřeby zaměstnanců s ADHD.</p>
                <span class="junction-link">Pro zaměstnavatele →</span>
            </a>
            <a href="<?= SITE_URL ?>/pro-skoly" class="junction-card junction-card--school fade-up">
                <div class="junction-icon" aria-hidden="true">🏫</div>
                <h3>Jsem škola</h3>
                <p>Máte ve třídě žáky s ADHD? Nabízíme vzdělávání a podporu pro pedagogické pracovníky.</p>
                <span class="junction-link">Pro pedagogy →</span>
            </a>
            <a href="<?= SITE_URL ?>/zapojte-se" class="junction-card junction-card--involve fade-up">
                <div class="junction-icon" aria-hidden="true">🌟</div>
                <h3>Chci se zapojit</h3>
                <p>Sdílíte naši vizi? Staňte se dobrovolníkem, členem nebo absolvujte stáž u nás.</p>
                <span class="junction-link">Zapojte se →</span>
            </a>
        </div>
    </div>
</section>


<!-- ========== 6. VAŠE PŘÍBĚHY ========== -->
<section class="section section--mint" aria-label="Příběhy našich lidí">
    <div class="container">
        <div class="section-header">
            <h2>Vaše příběhy</h2>
            <p>Skutečné zážitky lidí, kteří našli svou cestu s Akrasií.</p>
        </div>
        <div class="testimonials-grid">
            <div class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    Díky Akrasii jsem konečně pochopila, že moje ADHD není slabost, ale součást toho, kdo jsem. Terapeutka z adresáře mi doslova změnila život.
                </p>
                <div class="testimonial-author">Markéta, 34 let</div>
                <div class="testimonial-role">diagnostikována v dospělosti</div>
            </div>
            <div class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    Jako pedagog jsem nevěděl, jak pomoci žákům s ADHD. Workshop Akrasie mi otevřel oči a dal mi konkrétní nástroje, které skutečně fungují.
                </p>
                <div class="testimonial-author">Pavel, učitel</div>
                <div class="testimonial-role">ZŠ Praha</div>
            </div>
            <div class="testimonial-card fade-up">
                <div class="testimonial-quote" aria-hidden="true">"</div>
                <p class="testimonial-text">
                    Naše firma spolupracuje s Akrasií na vytváření inkluzivního prostředí. Výsledky jsou vidět – naši zaměstnanci jsou spokojenější a produktivnější.
                </p>
                <div class="testimonial-author">Jana, HR manažerka</div>
                <div class="testimonial-role">technologická společnost</div>
            </div>
        </div>
        <div style="text-align:center; margin-top:var(--space-10)">
            <a href="<?= SITE_URL ?>/vase-pribehy" class="btn btn-secondary">Přečíst více příběhů</a>
        </div>
    </div>
</section>


<!-- ========== 7. SPOLUPRACUJEME ========== -->
<section class="section section--sm" aria-label="Naši partneři">
    <div class="container">
        <div class="section-header" style="margin-bottom:var(--space-8)">
            <h2>Spolupracujeme</h2>
            <p>Partneři, kteří sdílejí naši vizi inkluzivní společnosti.</p>
        </div>
        <div class="partners-row">
            <div class="partner-item">Tamly</div>
            <div class="partner-item">UTB Zlín</div>
            <div class="partner-item">Thermo Fisher</div>
        </div>
    </div>
</section>


<!-- ========== 8. DARUJTE ========== -->
<section class="donate-section" aria-label="Podpořte Akrasii">
    <div class="container">
        <h2>Podpořte nás</h2>
        <p>
            Vaše podpora nám umožňuje pomáhat lidem s ADHD, vzdělávat veřejnost
            a budovat komunitu, kde každý najde pochopení a pomoc.
        </p>
        <a href="<?= SITE_URL ?>/darujte" class="btn btn-primary">
            Darujte
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M20.84 4.61a5.5 5.5 0 0 0-7.78 0L12 5.67l-1.06-1.06a5.5 5.5 0 0 0-7.78 7.78l1.06 1.06L12 21.23l7.78-7.78 1.06-1.06a5.5 5.5 0 0 0 0-7.78z"/></svg>
        </a>
    </div>
</section>

<?php if (!empty($latestArticles)): ?>
<!-- ========== BONUS: Nejnovější z blogu ========== -->
<section class="section section--alt" aria-label="Novinky z blogu">
    <div class="container">
        <div class="section-header">
            <h2>Z blogu</h2>
            <p>Nejnovější články o ADHD, terapii a každodenním životě.</p>
        </div>
        <div class="blog-grid">
            <?php foreach ($latestArticles as $art): ?>
            <article class="blog-card fade-up">
                <?php if ($art['featured_image']): ?>
                <div class="blog-card-img-wrap">
                    <img src="<?= UPLOADS_URL . '/' . h($art['featured_image']) ?>"
                         alt="<?= h($art['title']) ?>"
                         loading="lazy">
                </div>
                <?php endif; ?>
                <div class="blog-card-body">
                    <?php if ($art['category_name']): ?>
                    <span class="blog-card-category"><?= h($art['category_name']) ?></span>
                    <?php endif; ?>
                    <h3 class="blog-card-title">
                        <a href="<?= SITE_URL ?>/clanek/<?= h($art['slug']) ?>"><?= h($art['title']) ?></a>
                    </h3>
                    <?php if ($art['excerpt']): ?>
                    <p class="blog-card-excerpt"><?= h($art['excerpt']) ?></p>
                    <?php endif; ?>
                    <div class="blog-card-meta">
                        <span><?= format_date($art['created_at']) ?></span>
                    </div>
                </div>
            </article>
            <?php endforeach; ?>
        </div>
        <div style="text-align:center; margin-top:var(--space-10)">
            <a href="<?= SITE_URL ?>/blog" class="btn btn-secondary">Všechny články</a>
        </div>
    </div>
</section>
<?php endif; ?>
