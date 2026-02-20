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

        <!-- Logo (obdélníkové – soubor assets/brand/akrasia_logo_rect.svg) -->
        <a href="<?= SITE_URL ?>/" class="site-logo" aria-label="Akrasia – domovská stránka">
            <!-- Obdélníkové logo – prioritně PNG (pokud nahrano), jinak SVG kombinace -->
            <img src="<?= SITE_URL ?>/assets/brand/akrasia_logo_rect.png"
                 alt="Akrasia"
                 height="36"
                 width="auto"
                 onerror="this.src='<?= SITE_URL ?>/assets/brand/akrasia_logo_rect.svg'">
        </a>

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

            <!-- Sociální sítě -->
            <?php if (SOCIAL_FACEBOOK || SOCIAL_INSTAGRAM || SOCIAL_LINKEDIN): ?>
            <div class="nav-social">
                <?php if (SOCIAL_FACEBOOK): ?>
                <a href="<?= h(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener" aria-label="Facebook">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                </a>
                <?php endif; ?>
                <?php if (SOCIAL_INSTAGRAM): ?>
                <a href="<?= h(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener" aria-label="Instagram">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                </a>
                <?php endif; ?>
                <?php if (SOCIAL_LINKEDIN): ?>
                <a href="<?= h(SOCIAL_LINKEDIN) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                </a>
                <?php endif; ?>
            </div>
            <?php endif; ?>

        </nav>
    </div>
</header>
<!-- Burger tlačítko + blob jsou MIMO header (backdrop-filter mění containing block pro fixed) -->
<button class="nav-toggle" aria-label="Otevřít menu" aria-expanded="false" aria-controls="main-nav">
    <span></span>
    <span></span>
    <span></span>
</button>
<img class="nav-blob" src="<?= SITE_URL ?>/assets/brand/burger-blob.svg" aria-hidden="true" alt="">

<main id="main-content">
