<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, text, button_text, button_url, style ('donate' | 'old-rose' | 'alt')
$style = $style ?? 'donate';
if ($style === 'donate') {
    $sectionClass = 'donate-section section--logo-bg';
} elseif ($style === 'old-rose') {
    $sectionClass = 'section section--old-rose';
} else {
    $sectionClass = 'section section--alt';
}
?>

<section class="<?= $sectionClass ?>">
    <div class="container" style="text-align:center">
        <?php if (!empty($title)): ?><h2><?= e($title) ?></h2><?php endif; ?>
        <?php if (!empty($text)): ?><p><?= e($text) ?></p><?php endif; ?>
        <?php if (!empty($button_text) && !empty($button_url)): ?>
        <a href="<?= e($button_url) ?>" class="btn btn-primary">
            <?= e($button_text) ?>
            <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
        </a>
        <?php endif; ?>
    </div>
</section>
