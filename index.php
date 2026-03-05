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

// ── Členská sekce ────────────────────────────────────────────────
// Login
Router::get('/login', function () {
    require_once CORE_PATH . '/MemberAuth.php';
    require_once CORE_PATH . '/Template.php';
    if (MemberAuth::check()) { redirect(url('clen')); }
    Template::render('member-login', ['error' => $_GET['chyba'] ?? null]);
});

Router::post('/login', function () {
    Security::validateCsrf();
    require_once CORE_PATH . '/MemberAuth.php';
    $ok = MemberAuth::attempt(
        trim($_POST['email'] ?? ''),
        $_POST['password'] ?? ''
    );
    if ($ok) {
        redirect(url('clen'));
    } else {
        redirect(url('login') . '?chyba=1');
    }
});

// Registrace
Router::get('/registrace', function () {
    require_once CORE_PATH . '/MemberAuth.php';
    require_once CORE_PATH . '/Template.php';
    if (MemberAuth::check()) { redirect(url('clen')); }
    Template::render('member-register', ['error' => null, 'old' => []]);
});

Router::post('/registrace', function () {
    Security::validateCsrf();
    require_once CORE_PATH . '/MemberAuth.php';

    $rateKey = 'member_register:' . client_ip();
    if (!Security::checkRateLimit($rateKey, 3, 3600)) {
        require_once CORE_PATH . '/Template.php';
        Template::render('member-register', [
            'error' => 'Příliš mnoho pokusů o registraci. Zkuste to za hodinu.',
            'old'   => $_POST,
        ]);
        return;
    }

    $name     = Security::sanitize($_POST['name'] ?? '');
    $email    = Security::sanitizeEmail($_POST['email'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirm  = $_POST['password_confirm'] ?? '';

    $error = null;
    if (!$name || !$email || !$password) {
        $error = 'Vyplňte všechna povinná pole.';
    } elseif (strlen($password) < 8) {
        $error = 'Heslo musí mít alespoň 8 znaků.';
    } elseif ($password !== $confirm) {
        $error = 'Hesla se neshodují.';
    }

    if ($error) {
        require_once CORE_PATH . '/Template.php';
        Template::render('member-register', ['error' => $error, 'old' => $_POST]);
        return;
    }

    try {
        Security::recordRateLimit($rateKey);
        $userId = MemberAuth::register($name, $email, $password);
        MemberAuth::attempt($email, $password);
        redirect(url('clen') . '?vitej=1');
    } catch (RuntimeException $e) {
        require_once CORE_PATH . '/Template.php';
        Template::render('member-register', ['error' => $e->getMessage(), 'old' => $_POST]);
    }
});

// Odhlášení člena
Router::get('/logout-clen', function () {
    require_once CORE_PATH . '/MemberAuth.php';
    MemberAuth::logout();
    redirect(url('login'));
});

// Přehled člena (dashboard)
Router::get('/clen', function () {
    require_once CORE_PATH . '/MemberAuth.php';
    require_once CORE_PATH . '/MemberContent.php';
    require_once CORE_PATH . '/Template.php';
    MemberAuth::requireLogin();
    $items = MemberContent::listForLevel(MemberAuth::level());
    Template::render('member-dashboard', ['items' => $items, 'welcome' => isset($_GET['vitej'])]);
});

// Přehrávač videa
Router::get('/clen/video/{id}', function (string $id) {
    require_once CORE_PATH . '/MemberAuth.php';
    require_once CORE_PATH . '/MemberContent.php';
    require_once CORE_PATH . '/Template.php';
    MemberAuth::requireLogin();
    $item = MemberContent::getById((int) $id);
    if (!$item || $item['type'] !== 'video') { Router::notFound(); }
    if (!MemberContent::canAccess((int) $id)) {
        flash('error', 'Nemáte oprávnění k tomuto obsahu.');
        redirect(url('clen'));
    }
    Template::render('member-video', ['item' => $item]);
});

// Streamování videa (Range-based)
Router::get('/clen/stream/{id}', function (string $id) {
    require_once CORE_PATH . '/MemberAuth.php';
    require_once CORE_PATH . '/MemberContent.php';
    MemberContent::streamVideo($id);
});

// Stažení dokumentu/PDF
Router::get('/clen/stahnout/{id}', function (string $id) {
    require_once CORE_PATH . '/MemberAuth.php';
    require_once CORE_PATH . '/MemberContent.php';
    MemberContent::downloadFile($id);
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
