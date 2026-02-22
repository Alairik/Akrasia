<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, subtitle, breadcrumb[] = [{label, url}] (poslední item bez url)
$breadcrumb = $breadcrumb ?? [];
?>

<section class="page-hero" style="position:relative;overflow:hidden">
    <div class="container">
        <?php if (!empty($breadcrumb)): ?>
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= url('/') ?>">Domů</a>
            <?php foreach ($breadcrumb as $crumb): ?>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <?php if (!empty($crumb['url'])): ?>
                <a href="<?= e($crumb['url']) ?>"><?= e($crumb['label']) ?></a>
            <?php else: ?>
                <span><?= e($crumb['label']) ?></span>
            <?php endif; ?>
            <?php endforeach; ?>
        </nav>
        <?php endif; ?>
        <?php if (!empty($title)): ?><h1><?= e($title) ?></h1><?php endif; ?>
        <?php if (!empty($subtitle)): ?><p><?= e($subtitle) ?></p><?php endif; ?>
    </div>
</section>
