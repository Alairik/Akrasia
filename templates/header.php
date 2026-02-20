<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle ?? SITE_NAME) ?> | <?= h(SITE_NAME) ?></title>
    <meta name="description" content="<?= h($metaDescription ?? SITE_DESCRIPTION) ?>">

    <!-- Canonical -->
    <link rel="canonical" href="<?= SITE_URL . h($_SERVER['REQUEST_URI']) ?>">

    <!-- Open Graph -->
    <meta property="og:site_name" content="<?= h(SITE_NAME) ?>">
    <meta property="og:title" content="<?= h($pageTitle ?? SITE_NAME) ?>">
    <meta property="og:description" content="<?= h($metaDescription ?? SITE_DESCRIPTION) ?>">
    <meta property="og:type" content="<?= isset($article) ? 'article' : 'website' ?>">
    <meta property="og:url" content="<?= SITE_URL . h($_SERVER['REQUEST_URI']) ?>">
    <meta property="og:locale" content="cs_CZ">
    <?php if (isset($article) && !empty($article['featured_image'])): ?>
    <meta property="og:image" content="<?= UPLOADS_URL . '/' . h($article['featured_image']) ?>">
    <?php endif; ?>

    <!-- Twitter Card -->
    <meta name="twitter:card" content="summary_large_image">
    <meta name="twitter:title" content="<?= h($pageTitle ?? SITE_NAME) ?>">
    <meta name="twitter:description" content="<?= h($metaDescription ?? SITE_DESCRIPTION) ?>">

    <!-- JSON-LD Structured Data -->
    <script type="application/ld+json">
    {
        "@context": "https://schema.org",
        "@type": "NGO",
        "name": "Akrasia",
        "description": "<?= addslashes(SITE_DESCRIPTION) ?>",
        "url": "<?= SITE_URL ?>",
        "sameAs": [
            <?php $socials = array_filter([SOCIAL_FACEBOOK, SOCIAL_INSTAGRAM, SOCIAL_LINKEDIN]);
            echo '"' . implode('","', array_values($socials)) . '"'; ?>
        ]
    }
    </script>

    <!-- Google Fonts – Montserrat -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Montserrat:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <!-- Stylesheet -->
    <link rel="stylesheet" href="<?= SITE_URL ?>/assets/css/style.css">
</head>
<body>

<a href="#main-content" class="skip-nav">Přeskočit na obsah</a>

<?php
// Determine active route for nav highlighting
$currentRoute = $_GET['route'] ?? 'home';
$currentSlug  = $_GET['slug'] ?? '';

function nav_active(string $route, string $current): string {
    return $route === $current ? ' active' : '';
}
?>

<header class="site-header" id="site-header">
    <div class="container">

        <!-- Logo -->
        <a href="<?= SITE_URL ?>/" class="site-logo" aria-label="Akrasia – domovská stránka">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 134.89 143.45" height="36" width="auto" aria-hidden="true" focusable="false">
                <path fill="#4e5699" d="M86.33,28.39l-6.17-2.64L5.68,95.55l-4.87-2.08-.81,1.9,26.23,11.21,.81-1.9-12.22-5.23,16.78-19.22,27.3,11.67-.16,26.32-9.73-4.16-.81,1.9,38.93,16.64,.07-5.32-.86-98.89Zm-53.23,49.96l25.87-29.65-.12,40.66-25.75-11.01Z"/>
                <path fill="#4e5699" d="M115.53,114.66c-9.16,0-14.33,5.81-14.33,14.33s5.16,14.46,14.33,14.46,14.33-5.81,14.33-14.46-5.16-14.33-14.33-14.33Z"/>
                <polygon fill="#4e5699" points="128.44 106.59 128.44 0 95.27 11.62 102.63 17.94 102.63 94.58 102.63 106.59 102.63 108.66 134.89 108.66 134.89 106.59 128.44 106.59"/>
            </svg>
            <span class="site-logo-text">Akrasia</span>
        </a>

        <!-- Hamburger (mobil) – floating sticky tlačítko -->
        <button class="nav-toggle" aria-label="Otevřít menu" aria-expanded="false" aria-controls="main-nav">
            <span></span>
            <span></span>
            <span></span>
        </button>
        <!-- Dekorativní blob za burgerem (mobil) -->
        <div class="nav-blob" aria-hidden="true"></div>

        <!-- Navigace -->
        <nav class="site-nav" id="main-nav" aria-label="Hlavní navigace">

            <!-- Domů -->
            <div class="nav-item">
                <a href="<?= SITE_URL ?>/" class="nav-link<?= nav_active('home', $currentRoute) ?>" aria-label="Domovská stránka">
                    <svg width="16" height="16" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m3 9 9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"/><polyline points="9 22 9 12 15 12 15 22"/></svg>
                </a>
            </div>

            <!-- Kdo jsme -->
            <div class="nav-item">
                <a href="<?= SITE_URL ?>/kdo-jsme" class="nav-link<?= nav_active('kdo-jsme', $currentRoute) ?>" aria-haspopup="true">
                    Kdo jsme
                    <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </a>
                <div class="dropdown" role="menu">
                    <a href="<?= SITE_URL ?>/pribeh" role="menuitem">Příběh</a>
                    <a href="<?= SITE_URL ?>/mise" role="menuitem">Mise</a>
                    <a href="<?= SITE_URL ?>/tym" role="menuitem">Tým</a>
                    <a href="<?= SITE_URL ?>/spolupracujeme" role="menuitem">Spolupracujeme</a>
                </div>
            </div>

            <!-- Hledám podporu -->
            <div class="nav-item">
                <a href="<?= SITE_URL ?>/hledam-podporu" class="nav-link<?= nav_active('hledam-podporu', $currentRoute) ?>" aria-haspopup="true">
                    Hledám podporu
                    <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </a>
                <div class="dropdown" role="menu">
                    <a href="<?= SITE_URL ?>/terapeuti" role="menuitem">Adresář terapeutů</a>
                    <a href="<?= SITE_URL ?>/vase-pribehy" role="menuitem">Vaše příběhy</a>
                </div>
            </div>

            <!-- Pro firmy -->
            <div class="nav-item">
                <a href="<?= SITE_URL ?>/pro-firmy" class="nav-link<?= nav_active('pro-firmy', $currentRoute) ?>">Pro firmy</a>
            </div>

            <!-- Pro školy -->
            <div class="nav-item">
                <a href="<?= SITE_URL ?>/pro-skoly" class="nav-link<?= nav_active('pro-skoly', $currentRoute) ?>">Pro školy</a>
            </div>

            <!-- Zapojte se -->
            <div class="nav-item">
                <a href="<?= SITE_URL ?>/zapojte-se" class="nav-link<?= nav_active('zapojte-se', $currentRoute) ?>" aria-haspopup="true">
                    Zapojte se
                    <svg class="nav-arrow" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><path d="m6 9 6 6 6-6"/></svg>
                </a>
                <div class="dropdown" role="menu">
                    <a href="<?= SITE_URL ?>/staz" role="menuitem">Stáž</a>
                    <a href="<?= SITE_URL ?>/dobrovolnictvi" role="menuitem">Dobrovolnictví</a>
                    <a href="<?= SITE_URL ?>/stante-se-clenem" role="menuitem">Staňte se členem</a>
                </div>
            </div>

            <!-- Blog -->
            <div class="nav-item">
                <a href="<?= SITE_URL ?>/blog" class="nav-link<?= nav_active('blog', $currentRoute) ?>">Blog</a>
            </div>

            <!-- Darujte (CTA) -->
            <div class="nav-item">
                <a href="<?= SITE_URL ?>/darujte" class="nav-link nav-link--donate<?= nav_active('darujte', $currentRoute) ?>">Darujte</a>
            </div>

        </nav>
    </div>
</header>

<main id="main-content">
