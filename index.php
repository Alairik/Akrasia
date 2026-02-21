<?php
/**
 * Akrasia – hlavní router
 */
require_once __DIR__ . '/includes/config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/helpers.php';
require_once INCLUDES_PATH . '/articles.php';
require_once INCLUDES_PATH . '/categories.php';
require_once INCLUDES_PATH . '/pages.php';

$route = $_GET['route'] ?? 'home';
$pageTitle = SITE_NAME;
$metaDescription = SITE_DESCRIPTION;

// Statické stránky (slug → soubor)
$staticPages = [
    'kdo-jsme'         => ['title' => 'Kdo jsme',                'file' => 'kdo-jsme'],
    'pribeh'           => ['title' => 'Příběh',                  'file' => 'pribeh'],
    'mise'             => ['title' => 'Mise',                    'file' => 'mise'],
    'tym'              => ['title' => 'Tým',                     'file' => 'tym'],
    'spolupracujeme'   => ['title' => 'Spolupracujeme',          'file' => 'spolupracujeme'],
    'hledam-podporu'   => ['title' => 'Hledám podporu',          'file' => 'hledam-podporu'],
    'vase-pribehy'     => ['title' => 'Vaše příběhy',            'file' => 'vase-pribehy'],
    'pro-firmy'        => ['title' => 'Pro firmy',               'file' => 'pro-firmy'],
    'pro-skoly'        => ['title' => 'Pro školy',               'file' => 'pro-skoly'],
    'zapojte-se'       => ['title' => 'Zapojte se',              'file' => 'zapojte-se'],
    'staz'             => ['title' => 'Stáž',                    'file' => 'staz'],
    'dobrovolnictvi'   => ['title' => 'Dobrovolnictví',          'file' => 'dobrovolnictvi'],
    'stante-se-clenem' => ['title' => 'Staňte se členem',        'file' => 'stante-se-clenem'],
    'darujte'          => ['title' => 'Darujte',                 'file' => 'darujte'],
    'terapeuti'        => ['title' => 'Adresář terapeutů',       'file' => 'terapeuti'],
    'gdpr'             => ['title' => 'Zásady ochrany osobních údajů', 'file' => 'gdpr'],
];

switch ($route) {

    // ----- Blog – výpis článků -----
    case 'blog':
        $page   = max(1, (int) ($_GET['page'] ?? 1));
        $total  = articles_count('published');
        $pag    = paginate($total, ARTICLES_PER_PAGE, $page);
        $articles = articles_list($pag['per_page'], $pag['offset'], 'published');
        $pageTitle = 'Blog';
        $metaDescription = 'Čtěte naše články o ADHD, terapii a životě s pozornostním deficitem.';
        $template = 'list';
        break;

    // ----- Jednotlivý článek -----
    case 'article':
        $slug = $_GET['slug'] ?? '';
        $article = article_get_by_slug($slug);
        if (!$article) {
            http_response_code(404);
            $pageTitle = 'Stránka nenalezena';
            $template  = '404';
        } else {
            $pageTitle       = $article['title'];
            $metaDescription = $article['excerpt'] ?: excerpt($article['content'], 160);
            $articleTags     = article_get_tags($article['id']);
            $template        = 'article';
        }
        break;

    // ----- Kategorie -----
    case 'category':
        $slug = $_GET['slug'] ?? '';
        $db   = db_connect();
        $stmt = $db->prepare('SELECT * FROM categories WHERE slug = ?');
        $stmt->execute([$slug]);
        $category = $stmt->fetch();
        if (!$category) {
            http_response_code(404);
            $pageTitle = 'Kategorie nenalezena';
            $template  = '404';
        } else {
            $page  = max(1, (int) ($_GET['page'] ?? 1));
            $total = articles_count('published', $category['id']);
            $pag   = paginate($total, ARTICLES_PER_PAGE, $page);
            $articles  = articles_list($pag['per_page'], $pag['offset'], 'published', $category['id']);
            $pageTitle = 'Kategorie: ' . $category['name'];
            $metaDescription = 'Články v kategorii ' . $category['name'];
            $template  = 'list';
        }
        break;

    // ----- Tag -----
    case 'tag':
        $slug = $_GET['slug'] ?? '';
        $db   = db_connect();
        $stmt = $db->prepare('SELECT * FROM tags WHERE slug = ?');
        $stmt->execute([$slug]);
        $tag = $stmt->fetch();
        if (!$tag) {
            http_response_code(404);
            $pageTitle = 'Tag nenalezen';
            $template  = '404';
        } else {
            $page = max(1, (int) ($_GET['page'] ?? 1));
            $stmt = $db->prepare('SELECT COUNT(*) FROM article_tags at2 JOIN articles a ON a.id = at2.article_id WHERE at2.tag_id = ? AND a.status = ?');
            $stmt->execute([$tag['id'], 'published']);
            $total = (int) $stmt->fetchColumn();
            $pag   = paginate($total, ARTICLES_PER_PAGE, $page);
            $stmt  = $db->prepare('SELECT a.*, u.username AS author_name, c.name AS category_name, c.slug AS category_slug
                FROM articles a
                JOIN article_tags at2 ON a.id = at2.article_id
                LEFT JOIN users u ON a.author_id = u.id
                LEFT JOIN categories c ON a.category_id = c.id
                WHERE at2.tag_id = ? AND a.status = ?
                ORDER BY a.created_at DESC LIMIT ? OFFSET ?');
            $stmt->execute([$tag['id'], 'published', $pag['per_page'], $pag['offset']]);
            $articles  = $stmt->fetchAll();
            $pageTitle = 'Tag: ' . $tag['name'];
            $metaDescription = 'Články s tagem ' . $tag['name'];
            $template  = 'list';
        }
        break;

    // ----- Statické stránky + CMS stránky -----
    default:
        if (isset($staticPages[$route])) {
            // Hardcoded statické stránky (PHP soubory)
            $pageTitle   = $staticPages[$route]['title'];
            $pageFile    = __DIR__ . '/pages/' . $staticPages[$route]['file'] . '.php';
            $template    = 'static';
        } elseif ($route !== 'home' && ($cmsPage = page_get_by_slug($route)) !== null) {
            // CMS stránka z databáze
            $pageTitle       = $cmsPage['title'];
            $metaDescription = $cmsPage['meta_description'] ?: ($cmsPage['excerpt'] ?: '');
            $template        = 'page';
        } elseif ($route === 'home' || $route === '') {
            // Homepage
            $template = 'home';
        } else {
            // 404
            http_response_code(404);
            $pageTitle = 'Stránka nenalezena';
            $template  = '404';
        }
        break;
}

// Kategorie a tagy pro navigaci/sidebar
$allCategories = categories_list();
$allTags       = tags_list();

// Render
require_once __DIR__ . '/templates/header.php';

switch ($template) {
    case 'home':
        require_once __DIR__ . '/pages/home.php';
        break;
    case 'page':
        require_once __DIR__ . '/templates/page.php';
        break;
    case 'static':
        if (file_exists($pageFile)) {
            require_once $pageFile;
        } else {
            http_response_code(404);
            require_once __DIR__ . '/templates/404.php';
        }
        break;
    default:
        require_once __DIR__ . '/templates/' . $template . '.php';
        break;
}

require_once __DIR__ . '/templates/footer.php';
