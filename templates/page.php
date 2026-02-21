<?php
/**
 * Template pro CMS stránky z databáze
 * Proměnná $cmsPage obsahuje data stránky z tabulky pages
 */
?>

<section class="page-hero page-hero--sm">
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span><?= h($cmsPage['title']) ?></span>
        </nav>
    </div>
</section>

<section class="section section--sm">
    <div class="container">
        <div class="article-full">

            <header class="article-header">
                <h1 class="article-title"><?= h($cmsPage['title']) ?></h1>
                <?php if ($cmsPage['excerpt']): ?>
                <p class="article-excerpt" style="font-size:1.15rem;color:#555;margin-top:.5rem;">
                    <?= h($cmsPage['excerpt']) ?>
                </p>
                <?php endif; ?>
            </header>

            <div class="article-body">
                <div class="article-content">
                    <?= $cmsPage['content'] ?>
                </div>
            </div>

        </div>
    </div>
</section>
