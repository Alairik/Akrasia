<?php
$pageTitle       = 'Stránka nenalezena';
$metaDescription = 'Hledaná stránka neexistuje nebo byla přesunuta.';
?>

<section class="section" style="padding:var(--space-24) 0 var(--space-32);">
    <div class="container">
        <div class="not-found">
            <div class="not-found-number" aria-hidden="true">404</div>
            <h1>Stránka nenalezena</h1>
            <p>Omlouváme se, ale stránka, kterou hledáte, neexistuje nebo byla přesunuta.</p>
            <div style="display:flex;gap:var(--space-3);justify-content:center;flex-wrap:wrap;">
                <a href="<?= SITE_URL ?>/" class="btn btn-primary">← Domovská stránka</a>
                <a href="<?= SITE_URL ?>/blog" class="btn btn-secondary">Blog</a>
                <a href="<?= SITE_URL ?>/hledam-podporu" class="btn btn-secondary">Hledám podporu</a>
            </div>
        </div>
    </div>
</section>
