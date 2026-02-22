<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, subtitle, items[] = {icon_svg, title, text, link_text, link_url}
// columns: '3' (default) nebo '4'
$cols = $columns ?? '3';
$gridClass = $cols === '4' ? 'cards-grid--4 cards-grid' : 'cards-grid';
$sectionClass = $section_class ?? '';
?>

<section class="section <?= e($sectionClass) ?>">
    <div class="container">
        <?php if (!empty($title) || !empty($subtitle)): ?>
        <div class="section-header">
            <?php if (!empty($title)): ?><h2><?= e($title) ?></h2><?php endif; ?>
            <?php if (!empty($subtitle)): ?><p><?= e($subtitle) ?></p><?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($items)): ?>
        <div class="<?= $gridClass ?>">
            <?php foreach ($items as $item): ?>
            <div class="card fade-up">
                <?php if (!empty($item['icon_svg'])): ?>
                <div class="card-icon card-icon--navy" aria-hidden="true">
                    <?= $item['icon_svg'] ?>
                </div>
                <?php endif; ?>
                <?php if (!empty($item['title'])): ?><h3><?= e($item['title']) ?></h3><?php endif; ?>
                <?php if (!empty($item['text'])): ?><p><?= e($item['text']) ?></p><?php endif; ?>
                <?php if (!empty($item['link_text']) && !empty($item['link_url'])): ?>
                <a href="<?= e($item['link_url']) ?>" class="read-more" style="margin-top:var(--space-4)">
                    <?= e($item['link_text']) ?>
                    <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                </a>
                <?php endif; ?>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
