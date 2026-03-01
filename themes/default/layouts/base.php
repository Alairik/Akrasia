<?php defined('ZVELE_CMS') or die(); ?>
<!DOCTYPE html>
<html lang="<?= e(setting('language', 'cs')) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= e($seo['title'] ?? setting('site_name', 'Akrasia')) ?></title>

    <?php if (!empty($seo['meta_description'])): ?>
    <meta name="description" content="<?= e($seo['meta_description']) ?>">
    <?php endif; ?>
    <?php if (!empty($seo['no_index'])): ?>
    <meta name="robots" content="noindex, nofollow">
    <?php endif; ?>

    <link rel="canonical" href="<?= e($seo['canonical'] ?? url(request_uri())) ?>">

    <meta property="og:site_name" content="<?= e(setting('site_name', 'Akrasia')) ?>">
    <meta property="og:title" content="<?= e($seo['meta_title'] ?? $seo['title'] ?? '') ?>">
    <meta property="og:description" content="<?= e($seo['meta_description'] ?? '') ?>">
    <meta property="og:url" content="<?= e($seo['canonical'] ?? '') ?>">
    <meta property="og:type" content="<?= e($seo['og_type'] ?? 'website') ?>">
    <meta property="og:locale" content="cs_CZ">
    <?php if (!empty($seo['og_image'])): ?>
    <meta property="og:image" content="<?= e($seo['og_image']) ?>">
    <?php endif; ?>
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= e($seo['meta_title'] ?? $seo['title'] ?? '') ?>">
    <meta name="twitter:description" content="<?= e($seo['meta_description'] ?? '') ?>">

    <?php
    $ga4Id       = setting('ga4_id', '');
    $gtmId       = setting('gtm_id', '');
    $metaPixelId = setting('meta_pixel_id', '');
    ?>
    <?php if ($ga4Id): ?><meta name="ga4-id" content="<?= e($ga4Id) ?>"><?php endif; ?>
    <?php if ($gtmId): ?><meta name="gtm-id" content="<?= e($gtmId) ?>"><?php endif; ?>
    <?php if ($metaPixelId): ?><meta name="meta-pixel-id" content="<?= e($metaPixelId) ?>"><?php endif; ?>

    <!-- Google Fonts – Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <link rel="preload" href="<?= asset('themes/default/assets/style.css') ?>" as="style">
    <link rel="stylesheet" href="<?= asset('themes/default/assets/style.css') ?>">

    <!-- Cookie Consent (musí být před trackery) -->
    <script src="<?= asset('assets/js/cookie-consent.js') ?>"></script>
    <script>
    CookieConsent.init({
        gaId:        '<?= e($ga4Id) ?>',
        gtmId:       '<?= e($gtmId) ?>',
        metaPixelId: '<?= e($metaPixelId) ?>'
    });
    </script>

    <!-- JSON-LD: NGO -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NGO",
        "name": "<?= addslashes(setting('site_name', 'Akrasia')) ?>",
        "description": "<?= addslashes(setting('site_description', '')) ?>",
        "url": "<?= setting('site_url', SITE_URL) ?>",
        "sameAs": [<?php
            $socials = array_values(array_filter([
                setting('social_facebook', ''),
                setting('social_instagram', ''),
                setting('social_linkedin', ''),
            ]));
            echo implode(',', array_map(fn($s) => '"' . addslashes($s) . '"', $socials));
        ?>]
    }
    </script>
</head>
<body>
    <a href="#main" class="skip-nav">Přeskočit na obsah</a>

    <?php Template::partial('header'); ?>

    <main id="main" role="main">
        <?= $pageContent ?>
    </main>

    <?php Template::partial('footer'); ?>
    <?php Template::partial('cookie-banner'); ?>

    <script src="<?= asset('assets/js/main.js') ?>" defer></script>
    <?php if (!empty($extraScript)): ?>
    <script src="<?= asset('assets/js/' . $extraScript) ?>" defer></script>
    <?php endif; ?>

    <!-- Theme Switcher (testovací widget – odstraň tento blok z base.php až nebude potřeba) -->
    <div class="theme-switcher" id="themeSwitcher" role="group" aria-label="Barevná varianta">
        <button class="theme-swatch" data-theme-set="" style="background:#4e5699" title="Výchozí – navy"></button>
        <button class="theme-swatch" data-theme-set="orange" style="background:#ff9b42" title="Oranžová"></button>
    </div>
</body>
</html>
