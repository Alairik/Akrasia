<?php defined('ZVELE_CMS') or die(); ?>

<?php
$db        = Database::getInstance();
$menu      = $db->fetchOne("SELECT items FROM zvele_menus WHERE location = 'main'");
$menuItems = $menu ? (json_decode($menu['items'], true) ?? []) : [];
$siteName  = setting('site_name', 'Akrasia');
$logoId    = setting('site_logo_id');
?>

<header class="site-header" role="banner">
    <div class="container">

        <a href="<?= url('/') ?>" class="site-logo" aria-label="<?= e($siteName) ?> – domovská stránka">
            <?php if ($logoId): ?>
                <img src="<?= e(Template::mediaUrl((int)$logoId)) ?>" alt="<?= e($siteName) ?>" height="36">
            <?php else: ?>
                <img src="<?= asset('themes/default/assets/akrasia_logo_rect.png') ?>" alt="<?= e($siteName) ?>" height="36" class="site-logo-img">
            <?php endif; ?>
        </a>

        <button class="nav-toggle" aria-label="Otevřít menu" aria-expanded="false" aria-controls="main-nav" id="nav-toggle">
            <span></span><span></span><span></span>
        </button>

        <nav class="site-nav" id="main-nav" role="navigation" aria-label="Hlavní navigace">

            <!-- Logo v mobilním menu -->
            <div class="nav-mobile-logo">
                <img src="<?= asset('themes/default/assets/akrasia_logo_rect.png') ?>" alt="<?= e($siteName) ?>" height="44" class="site-logo-img">
            </div>

            <?php foreach ($menuItems as $item):
                $label   = $item['label'] ?? '';
                $url     = $item['url']   ?? '#';
                $isDonate = ($label === 'Darujte' || str_ends_with(rtrim($url, '/'), '/darujte'));
                $linkClass = 'nav-link' . ($isDonate ? ' nav-link--donate' : '');
            ?>
            <div class="nav-item">
                <a href="<?= e($url) ?>"
                   class="<?= $linkClass ?>"
                   <?= !empty($item['target']) ? 'target="' . e($item['target']) . '" rel="noopener"' : '' ?>>
                    <?= e($label) ?>
                </a>
            </div>
            <?php endforeach; ?>

        </nav>

    </div>
</header>
