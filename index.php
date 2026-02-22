<?php
/**
 * Akrasia – ZveleCMS Front Controller
 */

define('ZVELE_CMS', true);
require_once __DIR__ . '/config.php';

// Bootstrap ZveleCMS
require_once CORE_PATH . '/bootstrap.php';

// ── Domovská stránka ────────────────────────────────────────────
Router::get('/', function () {
    $db = Database::getInstance();
    $page = $db->fetchOne(
        "SELECT * FROM zvele_pages WHERE slug = ? AND status = 'published'",
        ['homepage']
    );
    if (!$page) {
        $page = $db->fetchOne(
            "SELECT * FROM zvele_pages WHERE status = 'published' ORDER BY sort_order ASC, id ASC LIMIT 1"
        );
    }
    if ($page) {
        $page['blocks'] = json_decode($page['blocks'], true) ?? [];
        require_once CORE_PATH . '/Template.php';
        Template::render('page', ['page' => $page]);
    } else {
        echo '<!DOCTYPE html><html lang="cs"><head><meta charset="UTF-8"><title>Akrasia</title></head>';
        echo '<body style="font-family:sans-serif;padding:3rem;text-align:center"><h1>Vítejte v Akrasii</h1>';
        echo '<p>Web se připravuje. <a href="/admin">Přejít do administrace</a></p></body></html>';
    }
});

// ── Blog – výpis ────────────────────────────────────────────────
Router::get('/blog', function () {
    $db      = Database::getInstance();
    $perPage = (int) setting('blog_posts_per_page', 9);
    $current = max(1, (int) ($_GET['page'] ?? 1));
    $offset  = ($current - 1) * $perPage;
    $total   = $db->count('zvele_posts', "status = 'published'");
    $posts   = $db->fetchAll(
        "SELECT p.*, u.name as author_name FROM zvele_posts p
         LEFT JOIN zvele_users u ON p.author_id = u.id
         WHERE p.status = 'published' ORDER BY p.published_at DESC LIMIT ? OFFSET ?",
        [$perPage, $offset]
    );
    require_once CORE_PATH . '/Template.php';
    Template::render('blog', [
        'posts'      => $posts,
        'currentPage'=> $current,
        'totalPages' => (int) ceil($total / $perPage),
    ]);
});

// ── Jednotlivý blogový příspěvek ────────────────────────────────
Router::get('/blog/{slug}', function (string $slug) {
    $db   = Database::getInstance();
    $post = $db->fetchOne(
        "SELECT p.*, u.name as author_name, u.email as author_email
         FROM zvele_posts p
         LEFT JOIN zvele_users u ON p.author_id = u.id
         WHERE p.slug = ? AND p.status = 'published'",
        [$slug]
    );
    if (!$post) { Router::notFound(); }
    $post['tags'] = json_decode($post['tags'], true) ?? [];
    require_once CORE_PATH . '/Template.php';
    Template::render('post', ['post' => $post]);
});

// ── Odeslání formuláře ──────────────────────────────────────────
Router::post('/form/submit', function () {
    Security::validateCsrf();
    require_once CORE_PATH . '/Form.php';
    Form::handleSubmission();
});

// ── CMS stránka (slug) ──────────────────────────────────────────
Router::get('/{slug}', function (string $slug) {
    if ($slug === 'admin') return;

    $db   = Database::getInstance();
    $page = $db->fetchOne(
        "SELECT * FROM zvele_pages WHERE slug = ? AND status = 'published'",
        [$slug]
    );
    if (!$page) { Router::notFound(); }
    $page['blocks'] = json_decode($page['blocks'], true) ?? [];
    require_once CORE_PATH . '/Template.php';
    Template::render('page', ['page' => $page]);
});

Router::dispatch();
