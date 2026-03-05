<?php defined('ZVELE_CMS') or die(); ?>

<!-- Video breadcrumb / header -->
<section class="member-video-header">
    <div class="container">
        <a href="<?= url('clen') ?>" class="member-back-link">
            <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="18" height="18">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5L3 12m0 0l7.5-7.5M3 12h18"/>
            </svg>
            Zpět na přehled
        </a>
        <h1><?= e($item['title']) ?></h1>
        <?php if ($item['description']): ?>
        <p class="member-video-desc"><?= e($item['description']) ?></p>
        <?php endif; ?>
    </div>
</section>

<!-- Video player -->
<section class="member-video-section">
    <div class="container">
        <div class="member-video-wrapper">
            <video
                controls
                controlslist="nodownload"
                oncontextmenu="return false"
                preload="metadata"
                class="member-video-player">
                <source src="<?= url('clen/stream/' . $item['id']) ?>" type="video/mp4">
                Váš prohlížeč nepodporuje přehrávač videa.
            </video>
        </div>

        <?php if ($item['category']): ?>
        <div class="member-video-meta">
            <span class="member-card-tag"><?= e($item['category']) ?></span>
        </div>
        <?php endif; ?>
    </div>
</section>
