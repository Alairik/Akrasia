<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, subtitle, items[] = {number, label}
$sectionClass = $section_class ?? 'section--alt';
?>

<section class="section <?= e($sectionClass) ?>" style="position:relative;overflow:hidden">
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
