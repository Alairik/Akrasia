<?php defined('ZVELE_CMS') or die(); ?>

<!-- Member hero -->
<section class="member-hero">
    <div class="container">
        <div class="member-hero-inner">
            <h1>
                <?php if ($welcome ?? false): ?>
                    Vítejte, <?= e(MemberAuth::name()) ?>!
                <?php else: ?>
                    Dobrý den, <?= e(MemberAuth::name()) ?>
                <?php endif; ?>
            </h1>
            <p>Váš přístup: <strong>Úroveň <?= MemberAuth::level() ?></strong></p>
            <a href="<?= url('logout-clen') ?>" class="member-logout-link">Odhlásit se</a>
        </div>
    </div>
</section>

<!-- Content grid -->
<section class="member-content-section">
    <div class="container">

        <?php if (empty($items)): ?>
        <div class="member-empty">
            <p>Zatím zde nejsou žádné materiály. Brzy přidáme první videa a dokumenty.</p>
        </div>

        <?php else: ?>

        <?php
        $videos    = array_filter($items, fn($i) => $i['type'] === 'video');
        $documents = array_filter($items, fn($i) => in_array($i['type'], ['pdf', 'document']));
        ?>

        <?php if ($videos): ?>
        <div class="member-section-header">
            <h2>Videa</h2>
        </div>
        <div class="member-grid">
            <?php foreach ($videos as $item): ?>
            <a href="<?= url('clen/video/' . $item['id']) ?>" class="member-card member-card--video">
                <div class="member-card-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="32" height="32">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M5.25 5.653c0-.856.917-1.398 1.667-.986l11.54 6.347a1.125 1.125 0 010 1.972l-11.54 6.347a1.125 1.125 0 01-1.667-.986V5.653z"/>
                    </svg>
                </div>
                <div class="member-card-body">
                    <h3><?= e($item['title']) ?></h3>
                    <?php if ($item['description']): ?>
                    <p><?= e($item['description']) ?></p>
                    <?php endif; ?>
                    <?php if ($item['category']): ?>
                    <span class="member-card-tag"><?= e($item['category']) ?></span>
                    <?php endif; ?>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php if ($documents): ?>
        <div class="member-section-header <?= $videos ? 'member-section-header--gap' : '' ?>">
            <h2>Materiály ke stažení</h2>
        </div>
        <div class="member-list">
            <?php foreach ($documents as $item): ?>
            <a href="<?= url('clen/stahnout/' . $item['id']) ?>" class="member-doc-item" download>
                <div class="member-doc-icon">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="24" height="24">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M19.5 14.25v-2.625a3.375 3.375 0 00-3.375-3.375h-1.5A1.125 1.125 0 0113.5 7.125v-1.5a3.375 3.375 0 00-3.375-3.375H8.25m2.25 0H5.625c-.621 0-1.125.504-1.125 1.125v17.25c0 .621.504 1.125 1.125 1.125h12.75c.621 0 1.125-.504 1.125-1.125V11.25a9 9 0 00-9-9z"/>
                    </svg>
                </div>
                <div class="member-doc-body">
                    <strong><?= e($item['title']) ?></strong>
                    <?php if ($item['description']): ?>
                    <span><?= e($item['description']) ?></span>
                    <?php endif; ?>
                </div>
                <div class="member-doc-dl">
                    <svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" width="20" height="20">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 005.25 21h13.5A2.25 2.25 0 0021 18.75V16.5M16.5 12L12 16.5m0 0L7.5 12m4.5 4.5V3"/>
                    </svg>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>

        <?php endif; ?>

    </div>
</section>
