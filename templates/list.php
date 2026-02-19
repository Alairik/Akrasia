<?php
/* ── Blog / Kategorie / Tag listing ─────────────────────────── */

// Titulek stránky
if (isset($category)) {
    $pageTitle       = 'Kategorie: ' . $category['name'];
    $metaDescription = 'Články v kategorii ' . $category['name'] . ' – Akrasia blog o ADHD.';
    $listingTitle    = 'Kategorie: ' . $category['name'];
    $breadcrumbLabel = $category['name'];
} elseif (isset($tag)) {
    $pageTitle       = 'Tag: ' . $tag['name'];
    $metaDescription = 'Články se štítkem ' . $tag['name'] . ' – Akrasia blog o ADHD.';
    $listingTitle    = 'Štítek: ' . $tag['name'];
    $breadcrumbLabel = $tag['name'];
} else {
    $pageTitle       = 'Blog';
    $metaDescription = 'Blog Akrasie – články o ADHD, tipech pro každodenní život a zkušenostech komunity.';
    $listingTitle    = 'Blog';
    $breadcrumbLabel = 'Blog';
}
?>

<section class="page-hero">
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <?php if (isset($category) || isset($tag)): ?>
                <a href="<?= SITE_URL ?>/blog">Blog</a>
                <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <?php endif; ?>
            <span><?= h($breadcrumbLabel) ?></span>
        </nav>
        <h1><?= h($listingTitle) ?></h1>
        <?php if (!isset($category) && !isset($tag)): ?>
            <p>Čtěte o ADHD, tipy pro každodenní život a zkušenosti naší komunity.</p>
        <?php endif; ?>
    </div>
</section>

<section class="section section--sm">
    <div class="container">

        <?php if (!empty($allCategories) && !isset($category) && !isset($tag)): ?>
        <!-- Filtry kategorií -->
        <div style="display:flex;flex-wrap:wrap;gap:var(--space-2);margin-bottom:var(--space-8);" role="navigation" aria-label="Kategorie blogu">
            <a href="<?= SITE_URL ?>/blog" class="tag-pill<?= (!isset($category) && !isset($tag)) ? ' active' : '' ?>">Vše</a>
            <?php foreach ($allCategories as $cat): ?>
                <a href="<?= SITE_URL ?>/kategorie/<?= h($cat['slug']) ?>" class="tag-pill">
                    <?= h($cat['name']) ?>
                    <span style="opacity:.65;font-size:.8em;margin-left:.25em;"><?= (int)$cat['article_count'] ?></span>
                </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if (empty($articles)): ?>
            <div style="text-align:center;padding:var(--space-16) 0;">
                <p style="font-size:var(--text-xl);margin-bottom:var(--space-3);">📝</p>
                <p style="color:var(--text-muted);">Zatím zde nejsou žádné články.</p>
                <a href="<?= SITE_URL ?>/blog" class="btn btn-secondary" style="margin-top:var(--space-4);">Zpět na blog</a>
            </div>
        <?php else: ?>

        <!-- Grid článků -->
        <div class="blog-grid">
            <?php foreach ($articles as $a): ?>
            <article class="blog-card fade-up">
                <?php if (!empty($a['featured_image'])): ?>
                <a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>" class="blog-card-image" tabindex="-1" aria-hidden="true">
                    <img src="<?= UPLOADS_URL . '/' . h($a['featured_image']) ?>"
                         alt="<?= h($a['title']) ?>" loading="lazy">
                </a>
                <?php endif; ?>
                <div class="blog-card-body">
                    <?php if ($a['category_name']): ?>
                    <a href="<?= SITE_URL ?>/kategorie/<?= h($a['category_slug'] ?? '') ?>" class="blog-card-category"><?= h($a['category_name']) ?></a>
                    <?php endif; ?>
                    <h2 class="blog-card-title">
                        <a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>"><?= h($a['title']) ?></a>
                    </h2>
                    <div class="blog-card-meta">
                        <span><?= format_date($a['created_at']) ?></span>
                        <span aria-hidden="true">·</span>
                        <span><?= h($a['author_name']) ?></span>
                    </div>
                    <?php if ($a['excerpt'] || $a['content']): ?>
                    <p class="blog-card-excerpt"><?= h($a['excerpt'] ?: excerpt($a['content'], 150)) ?></p>
                    <?php endif; ?>
                    <a href="<?= SITE_URL ?>/clanek/<?= h($a['slug']) ?>" class="blog-card-link" aria-label="Číst článek: <?= h($a['title']) ?>">
                        Číst článek →
                    </a>
                </div>
            </article>
            <?php endforeach; ?>
        </div>

        <!-- Paginace -->
        <?php if ($pag['total_pages'] > 1): ?>
        <?php
        if (isset($category)) {
            $pagBase = SITE_URL . '/kategorie/' . h($category['slug']) . '?page=';
        } elseif (isset($tag)) {
            $pagBase = SITE_URL . '/tag/' . h($tag['slug']) . '?page=';
        } else {
            $pagBase = SITE_URL . '/blog/stranka/';
        }
        ?>
        <nav class="pagination" aria-label="Stránkování">
            <?php if ($pag['current_page'] > 1): ?>
                <a href="<?= $pagBase ?><?= $pag['current_page'] - 1 ?>" class="pagination-prev" aria-label="Předchozí stránka">← Předchozí</a>
            <?php endif; ?>

            <?php
            $cur   = $pag['current_page'];
            $total = $pag['total_pages'];
            $pages = array_unique(array_filter(array_merge(
                [1, 2],
                range(max(1, $cur - 1), min($total, $cur + 1)),
                [$total - 1, $total]
            )));
            sort($pages);
            $prev = 0;
            foreach ($pages as $i):
                if ($prev && $i - $prev > 1): ?><span class="pagination-ellipsis" aria-hidden="true">…</span><?php endif;
                if ($i === $cur): ?>
                    <span class="active" aria-current="page"><?= $i ?></span>
                <?php else: ?>
                    <a href="<?= $pagBase ?><?= $i ?>"><?= $i ?></a>
                <?php endif;
                $prev = $i;
            endforeach; ?>

            <?php if ($pag['current_page'] < $pag['total_pages']): ?>
                <a href="<?= $pagBase ?><?= $pag['current_page'] + 1 ?>" class="pagination-next" aria-label="Další stránka">Další →</a>
            <?php endif; ?>
        </nav>
        <?php endif; ?>

        <?php endif; ?>

        <!-- Sidebar tagy -->
        <?php if (!empty($allTags)): ?>
        <div style="margin-top:var(--space-12);padding-top:var(--space-8);border-top:1px solid rgba(78,86,153,.15);">
            <h3 style="font-family:var(--font-body);font-size:var(--text-sm);font-weight:700;letter-spacing:.08em;text-transform:uppercase;color:var(--text-muted);margin-bottom:var(--space-4);">Tagy</h3>
            <div style="display:flex;flex-wrap:wrap;gap:var(--space-2);">
                <?php foreach ($allTags as $t): ?>
                <a href="<?= SITE_URL ?>/tag/<?= h($t['slug']) ?>" class="tag-pill"><?= h($t['name']) ?></a>
                <?php endforeach; ?>
            </div>
        </div>
        <?php endif; ?>

    </div>
</section>
