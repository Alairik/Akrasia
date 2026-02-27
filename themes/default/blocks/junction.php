<?php defined('ZVELE_CMS') or die(); ?>
<?php
// Sekce "Kdo jste?" – rozcestník se 4 barevnými kartami
// Pole: title, subtitle, deco (array), items[] = {variant, title, text, link_text, link_url}
// variant: 'support' | 'company' | 'school' | 'involve'
// Ikony jsou načteny per variant
$deco = $deco ?? null;
$icons = [
    'support' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M12 18v-5.25m0 0a6.01 6.01 0 0 0 1.5-.189m-1.5.189a6.01 6.01 0 0 1-1.5-.189m3.75 7.478a12.06 12.06 0 0 1-4.5 0m3.75 2.383a14.406 14.406 0 0 1-3 0M14.25 18v-.192c0-.983.658-1.823 1.508-2.316a7.5 7.5 0 1 0-7.517 0c.85.493 1.509 1.333 1.509 2.316V18"/></svg>',
    'company' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M3.75 21h16.5M4.5 3h15M5.25 3v18m13.5-18v18M9 6.75h1.5m-1.5 3h1.5m-1.5 3h1.5m3-6H15m-1.5 3H15m-1.5 3H15M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21"/></svg>',
    'school'  => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 3.741-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5"/></svg>',
    'involve' => '<svg viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="1.5" stroke-linecap="round" stroke-linejoin="round"><path d="M11.48 3.499a.562.562 0 0 1 1.04 0l2.125 5.111a.563.563 0 0 0 .475.345l5.518.442c.499.04.701.663.321.988l-4.204 3.602a.563.563 0 0 0-.182.557l1.285 5.385a.562.562 0 0 1-.84.61l-4.725-2.885a.562.562 0 0 0-.586 0L6.982 20.54a.562.562 0 0 1-.84-.61l1.285-5.386a.562.562 0 0 0-.182-.557l-4.204-3.602a.562.562 0 0 1 .321-.988l5.518-.442a.563.563 0 0 0 .475-.345L11.48 3.5z"/></svg>',
];
?>

<section class="section section--logo-bg section--logo-bg-left" aria-label="Kdo jste?" style="position:relative;overflow:hidden">
    <?php if (!empty($deco)): echo deco_html($deco); endif; ?>
    <div class="container">
        <?php if (!empty($title) || !empty($subtitle)): ?>
        <div class="section-header">
            <?php if (!empty($title)): ?><h2><?= e($title) ?></h2><?php endif; ?>
            <?php if (!empty($subtitle)): ?><p><?= e($subtitle) ?></p><?php endif; ?>
        </div>
        <?php endif; ?>
        <?php if (!empty($items)): ?>
        <div class="junction-grid">
            <?php foreach ($items as $item):
                $variant = e($item['variant'] ?? 'support');
                $icon    = $icons[$item['variant'] ?? 'support'] ?? $icons['support'];
            ?>
            <a href="<?= e($item['link_url'] ?? '#') ?>" class="junction-card junction-card--<?= $variant ?> fade-up">
                <img src="<?= asset('assets/brand/prvek_horizontal.svg') ?>" class="junction-card-prvek" aria-hidden="true" alt="">
                <div class="junction-card__inner">
                    <div class="junction-card__text">
                        <h3><?= e($item['title'] ?? '') ?></h3>
                        <?php if (!empty($item['text'])): ?>
                        <p><?= e($item['text']) ?></p>
                        <?php endif; ?>
                        <span class="junction-link"><?= e($item['link_text'] ?? 'Zjistit více') ?>
                            <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" aria-hidden="true"><path d="M5 12h14"/><path d="m12 5 7 7-7 7"/></svg>
                        </span>
                    </div>
                    <div class="junction-icon" aria-hidden="true">
                        <?= $icon ?>
                    </div>
                </div>
            </a>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</section>
