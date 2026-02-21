<?php
/**
 * Migrace statických stránek do CMS databáze
 *
 * Spusťte JEDNOU přes prohlížeč nebo CLI, pak SMAŽTE tento soubor!
 * Bezpečné – používá INSERT IGNORE, takže existující záznamy nepoškodí.
 */

require_once __DIR__ . '/includes/config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/auth.php';
require_once INCLUDES_PATH . '/helpers.php';
require_once INCLUDES_PATH . '/pages.php';

$results = [];
$errors  = [];

// ── Definice stránek (slug => [title, file, excerpt]) ──────────────────────
$staticPages = [
    'kdo-jsme'         => ['Kdo jsme',                    'kdo-jsme',        'Nezisková organizace, která věří, že ADHD není překážka – je to jiný způsob vnímání světa.'],
    'pribeh'           => ['Příběh Akrasie',               'pribeh',          'Jak vznikla organizace, která chce změnit způsob, jakým Česko vnímá ADHD.'],
    'mise'             => ['Naše mise',                    'mise',            'Věříme ve svět, kde ADHD není překážkou – ale součástí pestrosti lidské zkušenosti.'],
    'tym'              => ['Tým',                          'tym',             'Lidé, kteří stojí za Akrasií.'],
    'spolupracujeme'   => ['Spolupracujeme',               'spolupracujeme',  'Organizace a instituce, které sdílejí naši vizi.'],
    'hledam-podporu'   => ['Hledám podporu',               'hledam-podporu',  'Nabízíme propojení s ověřenými odborníky a komunitou.'],
    'vase-pribehy'     => ['Vaše příběhy',                 'vase-pribehy',    'Skutečné příběhy lidí, kteří žijí s ADHD a našli svou cestu.'],
    'pro-firmy'        => ['Pro firmy',                    'pro-firmy',       'Pomáháme firmám vytvářet prostředí, kde mohou lidé s ADHD naplno fungovat.'],
    'pro-skoly'        => ['Pro školy',                    'pro-skoly',       'Vzdělávání pedagogů a programy pro žáky s ADHD.'],
    'zapojte-se'       => ['Zapojte se',                   'zapojte-se',      'Sdílíte naši vizi? Existuje mnoho způsobů, jak přispět k naší misi.'],
    'staz'             => ['Stáž',                         'staz',            'Získejte praktické zkušenosti v neziskovém sektoru.'],
    'dobrovolnictvi'   => ['Dobrovolnictví',               'dobrovolnictvi',  'Věnujte svůj čas a talent naší komunitě.'],
    'stante-se-clenem' => ['Staňte se členem',             'stante-se-clenem','Jako člen Akrasie aktivně spolurozhodujete o směřování organizace.'],
    'darujte'          => ['Darujte',                      'darujte',         'Podpořte lidi s ADHD finančním darem.'],
    'terapeuti'        => ['Adresář terapeutů',            'terapeuti',       'Ověření odborníci specializovaní na ADHD. Filtrujte podle kraje, města nebo typu podpory.'],
    'gdpr'             => ['Zásady ochrany osobních údajů','gdpr',            'Informace o zpracování osobních údajů.'],
];

// Najdeme ID admina (prvního aktivního)
$db = db_connect();
$stmt = $db->query("SELECT id FROM users WHERE role='admin' AND active=1 ORDER BY id LIMIT 1");
$adminUser = $stmt->fetch();

if (!$adminUser) {
    die('<p style="color:red">❌ Nenalezen žádný admin uživatel. Nejdříve spusťte install.php.</p>');
}

$adminId = $adminUser['id'];
$order   = 0;

foreach ($staticPages as $slug => [$title, $file, $excerpt]) {
    $filePath = __DIR__ . '/pages/' . $file . '.php';

    if (!file_exists($filePath)) {
        $errors[] = "Soubor nenalezen: pages/{$file}.php";
        continue;
    }

    $raw = file_get_contents($filePath);

    // ── Extrakce obsahu ─────────────────────────────────────────────────────

    // 1. Odstraníme <?php ... ?> bloky (PHP nastavení na začátku souboru jako terapeuti.php)
    $raw = preg_replace('/<\?php\b.*?\?>\s*/s', '', $raw);

    // 2. Odstraníme deco_html volání
    $raw = preg_replace('/<\?=\s*deco_html\([^)]*\)\s*\?>\s*/m', '', $raw);

    // 3. Nahradíme SITE_URL
    $raw = str_replace('<?= SITE_URL ?>', SITE_URL, $raw);
    $raw = str_replace("<?= SITE_URL . '/", SITE_URL . '/', $raw);

    // 4. Nahradíme json_encode(SITE_URL ...) v <script> tazích
    $raw = preg_replace('/\<\?=\s*json_encode\(SITE_URL\s*\.\s*\'([^\']+)\'\)\s*\?>/u',
        '"' . SITE_URL . '$1"', $raw);
    $raw = preg_replace('/\<\?=\s*json_encode\(SITE_URL\)\s*\?>/u',
        '"' . SITE_URL . '"', $raw);

    // 5. Odstraníme zbývající PHP tagy (for loops pro skeleton, atd.)
    $raw = preg_replace('/<\?(?:php|=)[^?]*(?:\?>[^<]*)?/s', '', $raw);

    // ── Pokus o extrakci jen page-content-body (pro jednoduché stránky) ────
    $content = '';
    if (preg_match('/<div[^>]*class="page-content-body"[^>]*>(.*)/s', $raw, $m)) {
        $inner = $m[1];

        // Najdeme správný matching closing </div> (zachováme vnořené divy)
        $depth = 1;
        $pos = 0;
        while ($depth > 0 && $pos < strlen($inner)) {
            $openPos  = strpos($inner, '<div', $pos);
            $closePos = strpos($inner, '</div>', $pos);

            if ($openPos === false && $closePos === false) break;

            if ($openPos !== false && ($closePos === false || $openPos < $closePos)) {
                $depth++;
                $pos = $openPos + 4;
            } else {
                $depth--;
                if ($depth === 0) {
                    $inner = substr($inner, 0, $closePos);
                }
                $pos = $closePos + 6;
            }
        }
        $content = trim($inner);
    }

    // ── Pokud nemáme page-content-body, vezmeme celou page-content sekci ───
    if (empty($content)) {
        // Odstraníme hero sekci, zobrazíme jen obsah
        $noHero = preg_replace('/<section[^>]*class="page-hero"[^>]*>.*?<\/section>\s*/s', '', $raw);
        // Odstraníme prázdné/nesmyslné PHP echo
        $noHero = preg_replace('/\<\?=\s*[^?]+\?>/s', '', $noHero);
        $content = trim($noHero);

        // Pokud je stránka komplexní (terapeuti s filtry), přidáme note
        if (empty(trim(strip_tags($content)))) {
            $content = '<p><em>Tato stránka používá vlastní PHP šablonu. Obsah spravujte v souboru <code>pages/' . $file . '.php</code>.</em></p>';
        }
    }

    // ── Vložení do DB (INSERT IGNORE = přeskočí pokud slug existuje) ────────
    try {
        $stmt = $db->prepare("
            INSERT IGNORE INTO pages
                (title, slug, content, excerpt, meta_title, meta_description, status, menu_order, author_id, created_at, updated_at)
            VALUES
                (?, ?, ?, ?, ?, ?, 'published', ?, ?, NOW(), NOW())
        ");
        $stmt->execute([
            $title,
            $slug,
            $content,
            $excerpt,
            $title . ' | ' . SITE_NAME,
            $excerpt,
            $order,
            $adminId,
        ]);

        $affected = $stmt->rowCount();
        if ($affected > 0) {
            $results[] = ['status' => 'ok',      'slug' => $slug, 'title' => $title, 'note' => 'Vloženo'];
        } else {
            $results[] = ['status' => 'skip',    'slug' => $slug, 'title' => $title, 'note' => 'Přeskočeno (slug již existuje)'];
        }
    } catch (PDOException $e) {
        $errors[]  = "[$slug] DB chyba: " . $e->getMessage();
    }

    $order += 10;
}
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <title>Migrace stránek</title>
    <style>
        body { font-family: -apple-system, sans-serif; max-width: 800px; margin: 2rem auto; padding: 1rem; }
        h1   { color: #1a1a2e; }
        .ok   { color: #155724; background: #d4edda; padding: .3rem .7rem; border-radius: 4px; font-size: .85rem; }
        .skip { color: #856404; background: #fff3cd; padding: .3rem .7rem; border-radius: 4px; font-size: .85rem; }
        .err  { color: #721c24; background: #f8d7da; padding: .3rem .7rem; border-radius: 4px; font-size: .85rem; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        td, th { padding: .5rem .7rem; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f0f2f5; font-weight: 600; }
        .warning { background: #fff3cd; border: 1px solid #ffc107; padding: 1rem; border-radius: 6px; margin: 1rem 0; }
    </style>
</head>
<body>
<h1>Migrace stránek do CMS</h1>

<?php if ($errors): ?>
    <div style="background:#f8d7da;border:1px solid #f5c6cb;padding:1rem;border-radius:6px;margin:1rem 0;">
        <strong>Chyby:</strong>
        <ul><?php foreach ($errors as $e): ?><li><?= h($e) ?></li><?php endforeach; ?></ul>
    </div>
<?php endif; ?>

<table>
    <thead><tr><th>Slug</th><th>Název</th><th>Výsledek</th></tr></thead>
    <tbody>
        <?php foreach ($results as $r): ?>
        <tr>
            <td><code>/<?= h($r['slug']) ?></code></td>
            <td><?= h($r['title']) ?></td>
            <td><span class="<?= $r['status'] ?>"><?= h($r['note']) ?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php $okCount = count(array_filter($results, fn($r) => $r['status'] === 'ok')); ?>
<?php if ($okCount > 0): ?>
<p><strong><?= $okCount ?> stránek bylo vloženo do databáze.</strong></p>
<?php endif; ?>

<div class="warning">
    <strong>⚠️ Důležité:</strong> Smažte soubor <code>migrate-pages.php</code> ze serveru!<br>
    Poté přejděte do <a href="<?= ADMIN_URL ?>/pages.php">admin → Stránky</a> a upravte obsah dle potřeby.
</div>

<p>
    <a href="<?= ADMIN_URL ?>/pages.php" style="background:#4361ee;color:#fff;padding:.6rem 1.2rem;border-radius:4px;text-decoration:none;">
        → Přejít do správy stránek
    </a>
</p>
</body>
</html>
