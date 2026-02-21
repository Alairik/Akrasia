<?php
/**
 * Template pro CMS stránky z databáze
 * $cmsPage – řádek z tabulky pages
 */
?>

<section class="page-hero" style="position:relative;overflow:hidden">
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span><?= h($cmsPage['title']) ?></span>
        </nav>
        <h1><?= h($cmsPage['title']) ?></h1>
        <?php if ($cmsPage['excerpt']): ?>
            <p><?= h($cmsPage['excerpt']) ?></p>
        <?php endif; ?>
    </div>
</section>

<section class="page-content" style="position:relative;overflow:hidden">
    <div class="container">
        <div class="page-content-body">
            <?= $cmsPage['content'] ?>
        </div>
    </div>
</section>
