<?php defined('ZVELE_CMS') or die(); ?>

<?php
$db        = Database::getInstance();
$menu      = $db->fetchOne("SELECT items FROM zvele_menus WHERE location = 'main'");
$menuItems = $menu ? (json_decode($menu['items'], true) ?? []) : [];
$siteName  = setting('site_name', 'Akrasia');
$logoId    = setting('site_logo_id');
?>

<header class="site-header" role="banner">
    <div class="container site-header__inner">

        <a href="<?= url('/') ?>" class="site-header__logo" aria-label="<?= e($siteName) ?> – domovská stránka" style="text-decoration:none;display:flex;align-items:center">
            <?php if ($logoId): ?>
                <img src="<?= e(Template::mediaUrl((int)$logoId)) ?>" alt="<?= e($siteName) ?>" height="40">
            <?php else: ?>
                <img src="<?= asset('assets/brand/akrasia_logo_rect.svg') ?>" alt="<?= e($siteName) ?>" height="40"
                     onerror="this.style.display='none';this.nextElementSibling.style.display='block'">
                <span style="display:none;font-family:var(--font-display);font-weight:700;color:var(--navy)"><?= e($siteName) ?></span>
            <?php endif; ?>
        </a>

        <button class="nav-toggle" aria-label="Otevřít menu" aria-expanded="false" aria-controls="main-nav" id="nav-toggle">
            <span></span><span></span><span></span>
        </button>

        <nav class="site-nav" id="main-nav" role="navigation" aria-label="Hlavní navigace">
            <?php if (!empty($menuItems)): ?>
            <ul class="site-nav__list">
                <?php foreach ($menuItems as $item): ?>
                <li class="site-nav__item">
                    <a href="<?= e($item['url'] ?? '#') ?>" class="site-nav__link"
                       <?= !empty($item['target']) ? 'target="' . e($item['target']) . '" rel="noopener"' : '' ?>>
                        <?= e($item['label'] ?? '') ?>
                    </a>
                </li>
                <?php endforeach; ?>
            </ul>
            <?php endif; ?>
        </nav>

    </div>
</header>
