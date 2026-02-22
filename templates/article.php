<?php
$pageTitle       = $article['title'];
$metaDescription = $article['excerpt'] ?: excerpt($article['content'], 160);
?>

<section class="page-hero page-hero--sm">
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <a href="<?= SITE_URL ?>/blog">Blog</a>
            <?php if ($article['category_name']): ?>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <a href="<?= SITE_URL ?>/kategorie/<?= h($article['category_slug'] ?? '') ?>"><?= h($article['category_name']) ?></a>
            <?php endif; ?>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span><?= h($article['title']) ?></span>
        </nav>
    </div>
</section>

<section class="section section--sm">
    <div class="container">
        <div class="article-full">

            <!-- Záhlaví článku -->
            <header class="article-header">
                <?php if ($article['category_name']): ?>
                <a href="<?= SITE_URL ?>/kategorie/<?= h($article['category_slug'] ?? '') ?>" class="blog-card-category" style="display:inline-block;margin-bottom:var(--space-3);"><?= h($article['category_name']) ?></a>
                <?php endif; ?>

                <h1 class="article-title"><?= h($article['title']) ?></h1>

                <div class="article-meta">
                    <time datetime="<?= date('Y-m-d', strtotime($article['created_at'])) ?>">
                        <?= format_date($article['created_at']) ?>
                    </time>
                    <span aria-hidden="true">·</span>
                    <span><?= h($article['author_name']) ?></span>
                </div>
            </header>

            <!-- Úvodní obrázek -->
            <?php if (!empty($article['featured_image'])): ?>
            <div class="article-featured-image">
                <img src="<?= UPLOADS_URL . '/' . h($article['featured_image']) ?>"
                     alt="<?= h($article['title']) ?>"
                     loading="eager">
            </div>
            <?php endif; ?>

            <!-- Obsah článku -->
            <div class="article-body">
                <div class="article-content">
                    <?= $article['content'] ?>
                </div>
            </div>

            <!-- Tagy -->
            <?php if (!empty($articleTags)): ?>
            <div class="article-tags-row">
                <span class="article-tags-label">Tagy:</span>
                <div class="article-tags">
                    <?php foreach ($articleTags as $t): ?>
                    <a href="<?= SITE_URL ?>/tag/<?= h($t['slug']) ?>" class="tag-pill"><?= h($t['name']) ?></a>
                    <?php endforeach; ?>
                </div>
            </div>
            <?php endif; ?>

            <!-- Zpět na blog -->
            <div style="margin-top:var(--space-10);padding-top:var(--space-6);border-top:1px solid rgba(78,86,153,.15);">
                <a href="<?= SITE_URL ?>/blog" class="btn btn-secondary">← Zpět na blog</a>
            </div>

        </div>
    </div>
</section>
