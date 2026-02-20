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
                <div class="stat-number" style="display:flex;align-items:center;justify-content:center">
                    <svg width="48" height="48" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round" stroke-linejoin="round" aria-hidden="true" style="color:var(--petrol)"><path d="m4.5 12.75 6 6 9-13.5"/></svg>
                </div>
                <div class="stat-label">ověření terapeuti v našem adresáři</div>
            </div>
        </div>
    </div>
</section>


<!-- ========== 3. NAŠE HODNOTY ========== -->
<section class="section section--logo-bg" aria-label="Naše hodnoty" style="position:relative;overflow:hidden">
    <img src="<?= SITE_URL ?>/assets/brand/prvek_vertical.svg" class="prvek-v prvek-v--left" aria-hidden="true" alt="">
    <div class="container">
        <div class="section-header">
            <h2>Naše <span class="heading-highlight">hodnoty</span></h2>
            <p>Věříme, že každý člověk si zaslouží prostor, kde bude pochopen a podpořen.</p>
        </div>
        <div class="values-grid">
            <div class="value-item fade-up">
                <div class="value-icon value-icon--old-rose" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M21 8.25c0-2.485-2.099-4.5-4.688-4.5-1.935 0-3.597 1.126-4.312 2.733-.715-1.607-2.377-2.733-4.313-2.733C5.1 3.75 3 5.765 3 8.25c0 7.22 9 12 9 12s9-4.78 9-12z"/></svg>
                </div>
                <div class="value-body">
                    <h3>Podpora a péče</h3>
                    <p>Vytváříme bezpečné prostředí, kde se každý může cítit přijat a pochopen bez odsuzování.</p>
                </div>
            </div>
            <div class="value-item fade-up">
                <div class="value-icon value-icon--navy" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                </div>
                <div class="value-body">
                    <h3>Odbornost</h3>
                    <p>Spolupracujeme výhradně s ověřenými odborníky a zakládáme si na vědecky podložených informacích.</p>
                </div>
            </div>
            <div class="value-item fade-up">
                <div class="value-icon value-icon--petrol" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2.25 18 9 11.25l4.306 4.306a11.95 11.95 0 0 1 5.814-5.518l2.74-1.22m0 0-5.94-2.281m5.94 2.28-2.28 5.941"/></svg>
                </div>
                <div class="value-body">
                    <h3>Růst</h3>
                    <p>Věříme v potenciál každého člověka s ADHD. Pomáháme přetvářet výzvy v příležitosti.</p>
                </div>
            </div>
            <div class="value-item fade-up">
                <div class="value-icon value-icon--mint" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M17.982 18.725A7.488 7.488 0 0 0 12 15.75a7.488 7.488 0 0 0-5.982 2.975m11.963 0a9 9 0 1 0-11.963 0m11.963 0A8.966 8.966 0 0 1 12 21a8.966 8.966 0 0 1-5.982-2.275M15 9.75a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/></svg>
                </div>
                <div class="value-body">
                    <h3>Přístupnost</h3>
                    <p>Dbáme na to, aby naše služby a informace byly dostupné pro všechny, bez ohledu na situaci.</p>
                </div>
            </div>
            <div class="value-item fade-up">
                <div class="value-icon value-icon--pear" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M20.25 8.511c.884.284 1.5 1.128 1.5 2.097v4.286c0 1.136-.847 2.1-1.98 2.193-.34.027-.68.052-1.02.072v3.091l-3-3c-1.354 0-2.694-.055-4.02-.163a2.115 2.115 0 0 1-.825-.242m9.345-8.334a2.126 2.126 0 0 0-.476-.095 48.64 48.64 0 0 0-8.048 0c-1.131.094-1.976 1.057-1.976 2.192v4.286c0 .837.46 1.58 1.155 1.951m9.345-8.334V6.637c0-1.621-1.152-3.026-2.76-3.235A48.455 48.455 0 0 0 11.25 3c-2.115 0-4.198.137-6.24.402-1.608.209-2.76 1.614-2.76 3.235v6.226c0 1.621 1.152 3.026 2.76 3.235.577.075 1.157.14 1.74.194V21l4.155-4.155"/></svg>
                </div>
                <div class="value-body">
                    <h3>Komunita</h3>
                    <p>Budujeme síť lidí, kteří si navzájem rozumí a mohou sdílet zkušenosti i rady.</p>
                </div>
            </div>
            <div class="value-item fade-up">
                <div class="value-icon value-icon--lilac" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M2.036 12.322a1.012 1.012 0 0 1 0-.639C3.423 7.51 7.36 4.5 12 4.5c4.638 0 8.573 3.007 9.963 7.178.07.207.07.431 0 .639C20.577 16.49 16.64 19.5 12 19.5c-4.638 0-8.573-3.007-9.963-7.178z"/><path d="M15 12a3 3 0 1 1-6 0 3 3 0 0 1 6 0z"/></svg>
                </div>
                <div class="value-body">
                    <h3>Transparentnost</h3>
                    <p>Jednáme otevřeně a poctivě – vůči lidem, které podporujeme, i vůči partnerům a dárcům.</p>
                </div>
            </div>
        </div>
    </div>
</section>


<!-- ========== 4. NAŠE AKTIVITY ========== -->
<section class="section section--old-rose" aria-label="Naše aktivity">
    <div class="container">
        <div class="section-header">
            <h2>Co <span class="heading-highlight">děláme</span></h2>
            <p>Pracujeme na tom, aby ADHD přestalo být překážkou a stalo se součástí pestrého světa.</p>
        </div>
        <div class="cards-grid--4 cards-grid">
            <div class="card fade-up">
                <div class="card-icon card-icon--navy" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25"/></svg>
                </div>
                <h3>Vzdělávání</h3>
                <p>Šíříme povědomí o ADHD – přednášky, workshopy, osvětové materiály pro veřejnost.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--petrol" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 9v6m3-3H9m12 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0z"/></svg>
                </div>
                <h3>Adresář terapeutů</h3>
                <p>Propojujeme lidi s ověřenými terapeuty a odborníky specializovanými na ADHD.</p>
                <a href="<?= SITE_URL ?>/terapeuti" class="read-more" style="margin-top:var(--space-4)">
                    Najít terapeuta
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--pear" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                </div>
                <h3>Firmy a školy</h3>
                <p>Pomáháme zaměstnavatelům a školám vytvářet inkluzivní prostředí pro lidi s ADHD.</p>
            </div>
            <div class="card fade-up">
                <div class="card-icon card-icon--old-rose" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M16.862 4.487 18.549 2.8a1.875 1.875 0 1 1 2.651 2.651L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931zm0 0L19.5 7.125M18 14v4.75A2.25 2.25 0 0 1 15.75 21H5.25A2.25 2.25 0 0 1 3 18.75V8.25A2.25 2.25 0 0 1 5.25 6H10"/></svg>
                </div>
                <h3>Blog a komunita</h3>
                <p>Píšeme, sdílíme příběhy a budujeme komunitu lidí, kteří si navzájem rozumí.</p>
                <a href="<?= SITE_URL ?>/blog" class="read-more" style="margin-top:var(--space-4)">
                    Číst blog
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
            </div>
        </div>
    </div>
</section>


<!-- ========== 5. ROZCESTNÍK ========== -->
<section class="section section--logo-bg section--logo-bg-left" aria-label="Kdo jste?" style="position:relative;overflow:hidden">
    <div class="container">
        <div class="section-header">
            <h2>Kdo <span class="heading-highlight">jste?</span></h2>
            <p>Najděte cestu, která je určena právě vám.</p>
        </div>
        <img src="<?= SITE_URL ?>/assets/brand/prvek_horizontal.svg" class="prvek-h" aria-hidden="true" alt="">
        <div class="junction-grid">
            <a href="<?= SITE_URL ?>/hledam-podporu" class="junction-card junction-card--support fade-up">
                <div class="junction-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>
                </div>
                <h3>Hledám podporu</h3>
                <p>Máte ADHD nebo podezření na diagnózu? Pomůžeme vám najít správnou cestu k odborné péči.</p>
                <span class="junction-link">Zjistit více
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </span>
            </a>
            <a href="<?= SITE_URL ?>/pro-firmy" class="junction-card junction-card--company fade-up">
                <div class="junction-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>
                </div>
                <h3>Jsem firma</h3>
                <p>Chcete vytvořit inkluzivní pracovní prostředí? Pomůžeme vám pochopit potřeby zaměstnanců s ADHD.</p>
                <span class="junction-link">Pro zaměstnavatele
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </span>
            </a>
            <a href="<?= SITE_URL ?>/pro-skoly" class="junction-card junction-card--school fade-up">
                <div class="junction-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>
                </div>
                <h3>Jsem škola</h3>
                <p>Máte ve třídě žáky s ADHD? Nabízíme vzdělávání a podporu pro pedagogické pracovníky.</p>
                <span class="junction-link">Pro pedagogy
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </span>
            </a>
            <a href="<?= SITE_URL ?>/zapojte-se" class="junction-card junction-card--involve fade-up">
                <div class="junction-icon" aria-hidden="true">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5z"/></svg>
                </div>
                <h3>Chci se zapojit</h3>
                <p>Sdílíte naši vizi? Staňte se dobrovolníkem, členem nebo absolvujte stáž u nás.</p>
                <span class="junction-link">Zapojte se
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </span>
            </a>
        </div>
    </div>
</section>


<!-- ========== 6. VAŠE PŘÍBĚHY ========== -->
<section class="section section--mint" aria-label="Příběhy našich lidí" style="position:relative;overflow:hidden">
    <img src="<?= SITE_URL ?>/assets/brand/prvek_vertical.svg" class="prvek-v prvek-v--left" aria-hidden="true" alt="">
    <div class="container">
        <div class="section-header">
            <h2>Vaše <span class="heading-highlight">příběhy</span></h2>
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
<section class="donate-section section--logo-bg" aria-label="Podpořte Akrasii">
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
