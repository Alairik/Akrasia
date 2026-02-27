<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, subtitle, section_class, deco (array indexů pro deco_html), items[] = {number, label}
$sectionClass = $section_class ?? 'section--alt';
$deco         = $deco ?? null;
?>

<section class="section <?= e($sectionClass) ?>" style="position:relative;overflow:hidden">
    <?php if (!empty($deco)): echo deco_html($deco); endif; ?>
    <div class="container">
        <?php if (!empty($title) || !empty($subtitle)): ?>
        <div class="section-header">
            <?php if (!empty($title)): ?><h2><?= e($title) ?></h2><?php endif; ?>
            <?php if (!empty($subtitle)): ?><p><?= e($subtitle) ?></p><?php endif; ?>
        </div>
        <?php endif; ?>
        <div class="stats-grid">
            <?php foreach ($items ?? [] as $item): ?>
            <div class="stat-card fade-up">
                <div class="stat-number"><?= e($item['number'] ?? '') ?></div>
                <div class="stat-label"><?= e($item['label'] ?? '') ?></div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>
