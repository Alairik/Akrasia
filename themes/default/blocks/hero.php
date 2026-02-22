<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Pole: title, subtitle, btn1_text, btn1_url, btn2_text, btn2_url, photo (filename z assets/brand/)
$photo = $photo ?? 'photo-1.png';
?>

<section class="hero" aria-label="Úvodní sekce">
    <?php if ($photo): ?>
    <img src="<?= asset('assets/brand/' . e($photo)) ?>"
         class="hero-photo"
         style="position:absolute;right:0;top:0;height:100%;width:45%;object-fit:contain;object-position:right bottom;pointer-events:none;"
         aria-hidden="true" alt="" loading="eager">
    <?php endif; ?>
    <div class="container">
        <?php if (!empty($title)): ?>
        <h1><?= $title ?></h1>
        <?php endif; ?>
        <?php if (!empty($subtitle)): ?>
        <p class="hero-subtitle"><?= e($subtitle) ?></p>
        <?php endif; ?>
        <?php if (!empty($btn1_text) && !empty($btn1_url)): ?>
        <div class="hero-btns">
            <a href="<?= e($btn1_url) ?>" class="btn btn-primary">
                <?= e($btn1_text) ?>
                <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
            </a>
            <?php if (!empty($btn2_text) && !empty($btn2_url)): ?>
            <a href="<?= e($btn2_url) ?>" class="btn btn-secondary"><?= e($btn2_text) ?></a>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
