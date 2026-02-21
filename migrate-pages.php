<?php
/**
 * Migrace statických stránek do CMS databáze
 *
 * Spusťte JEDNOU přes prohlížeč nebo CLI, pak SMAŽTE tento soubor!
 * Bezpecne – pouziva INSERT IGNORE, takze existujici zaznamy neposkodi.
 */

require_once __DIR__ . '/includes/config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/helpers.php';

$results = [];
$errors  = [];

// ── Definice stranek ─────────────────────────────────────────────────────────
$staticPages = [
    'kdo-jsme'         => ['Kdo jsme',                     'kdo-jsme',        'Nezisková organizace, která verí, ze ADHD není prekazka – je to jiný zpusob vnímání sveta.'],
    'pribeh'           => ['Príbeh Akrasie',               'pribeh',          'Jak vznikla organizace, která chce zmenit zpusob, jakým Cesko vnímá ADHD.'],
    'mise'             => ['Nase mise',                    'mise',            'Veríme ve svet, kde ADHD není prekazkou – ale soucástí pestrosti lidské zkusenosti.'],
    'tym'              => ['Tým',                          'tym',             'Lidé, kterí stojí za Akrasií.'],
    'spolupracujeme'   => ['Spolupracujeme',               'spolupracujeme',  'Organizace a instituce, které sdílejí nasi vizi.'],
    'hledam-podporu'   => ['Hledám podporu',               'hledam-podporu',  'Nabízíme propojení s overenými odborníky a komunitou.'],
    'vase-pribehy'     => ['Vase príbehy',                 'vase-pribehy',    'Skutecné príbehy lidí, kterí zijí s ADHD a nasli svou cestu.'],
    'pro-firmy'        => ['Pro firmy',                    'pro-firmy',       'Pomáháme firmám vytváret prostredí, kde mohou lidé s ADHD naplno fungovat.'],
    'pro-skoly'        => ['Pro skoly',                    'pro-skoly',       'Vzdelávání pedagogu a programy pro záky s ADHD.'],
    'zapojte-se'       => ['Zapojte se',                   'zapojte-se',      'Sdílíte nasi vizi? Existuje mnoho zpusobu, jak prispet k nasi misi.'],
    'staz'             => ['Stáz',                         'staz',            'Získejte praktické zkusenosti v neziskovém sektoru.'],
    'dobrovolnictvi'   => ['Dobrovolnictví',               'dobrovolnictvi',  'Venujte svuj cas a talent nasi komunite.'],
    'stante-se-clenem' => ['Stante se clenem',             'stante-se-clenem','Jako clen Akrasie aktivne spolurozhodujete o smerování organizace.'],
    'darujte'          => ['Darujte',                      'darujte',         'Podporte lidi s ADHD financním darem.'],
    'terapeuti'        => ['Adresár terapeuta',            'terapeuti',       'Overení odborníci specializovaní na ADHD. Filtrujte podle kraje, mesta nebo typu podpory.'],
    'gdpr'             => ['Zásady ochrany osobních údaju','gdpr',            'Informace o zpracování osobních údaju.'],
];

// ── Najdeme admina ───────────────────────────────────────────────────────────
$db = db_connect();
$stmt = $db->query("SELECT id FROM users WHERE role='admin' AND active=1 ORDER BY id LIMIT 1");
$adminUser = $stmt->fetch();

if (!$adminUser) {
    die('<p style="color:red">Nenalezen admin uzivatel. Nejdrive spustte install.php.</p>');
}

$adminId = (int) $adminUser['id'];
$order   = 0;

// ── Pomocná funkce: extrakce obsahu z PHP sablony ────────────────────────────
function extract_page_content(string $filePath): string
{
    $raw = file_get_contents($filePath);

    // a) Odstranit oteviraci PHP bloky (terapeuti.php ma <?php ... ?> na zacatku)
    $raw = preg_replace('#<\?php.*?\?>#s', '', $raw);

    // b) Odstranit deco_html volani
    $raw = preg_replace('#<\?=\s*deco_html\([^)]*\)\s*\?>#', '', $raw);

    // c) Nahradit <?= SITE_URL ?> skutecnou URL
    $raw = str_replace('<?= SITE_URL ?>', SITE_URL, $raw);

    // d) Nahradit <?= SITE_URL . '/neco' ?> nebo <?= SITE_URL.'/neco' ?>
    $raw = preg_replace_callback(
        '#<\?=\s*SITE_URL\s*\.\s*["\']([^"\']*)["\'\s]*\?>#',
        function ($m) { return SITE_URL . $m[1]; },
        $raw
    );

    // e) Odstranit zbytek PHP tagu s json_encode nebo jinych vyrazu
    $raw = preg_replace('#<\?=\s*json_encode\([^)]*\)\s*\?>#', '""', $raw);

    // f) Odstranit veskeré zbyvající <?= ... ?> a <?php ... ?>
    $raw = preg_replace('#<\?(?:=|php)[^?]*\?>#s', '', $raw);

    // ── Pokus extrahovat jen .page-content-body ──────────────────────────────
    $marker = '<div class="page-content-body">';
    $pos = strpos($raw, $marker);

    if ($pos !== false) {
        $inner = substr($raw, $pos + strlen($marker));

        // Najit matching </div> (pocitame zanoreni)
        $depth = 1;
        $cursor = 0;
        $len = strlen($inner);
        while ($depth > 0 && $cursor < $len) {
            $open  = strpos($inner, '<div', $cursor);
            $close = strpos($inner, '</div>', $cursor);

            if ($open !== false && ($close === false || $open < $close)) {
                $depth++;
                $cursor = $open + 4;
            } elseif ($close !== false) {
                $depth--;
                if ($depth === 0) {
                    $inner = substr($inner, 0, $close);
                }
                $cursor = $close + 6;
            } else {
                break;
            }
        }
        return trim($inner);
    }

    // ── Fallback: vzit vse po hero sekci ────────────────────────────────────
    $heroEnd = strpos($raw, '</section>');
    if ($heroEnd !== false) {
        $rest = substr($raw, $heroEnd + 10);
        $rest = strip_tags($rest, '<p><h1><h2><h3><h4><ul><ol><li><strong><em><a><br><table><tr><td><th><img><blockquote><div><span><article><section>');
        if (trim(strip_tags($rest)) === '') {
            return '<p><em>Tato stránka používá vlastní PHP šablonu. Obsah spravujte v souboru pages/' . basename($filePath) . '</em></p>';
        }
        return trim($rest);
    }

    return '<p><em>Obsah nebyl automaticky extrahován. Upravte stránku ručně v editoru.</em></p>';
}

// ── Migrace ──────────────────────────────────────────────────────────────────
foreach ($staticPages as $slug => $pageData) {
    $title   = $pageData[0];
    $file    = $pageData[1];
    $excerpt = $pageData[2];

    $filePath = __DIR__ . '/pages/' . $file . '.php';

    if (!file_exists($filePath)) {
        $errors[] = "Soubor nenalezen: pages/{$file}.php";
        continue;
    }

    $content = extract_page_content($filePath);

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

        if ($stmt->rowCount() > 0) {
            $results[] = ['status' => 'ok',   'slug' => $slug, 'title' => $title, 'note' => 'Vlozeno'];
        } else {
            $results[] = ['status' => 'skip', 'slug' => $slug, 'title' => $title, 'note' => 'Preskoceno (jiz existuje)'];
        }
    } catch (PDOException $e) {
        $errors[] = "[$slug] DB chyba: " . $e->getMessage();
    }

    $order += 10;
}

// ── Statistika ───────────────────────────────────────────────────────────────
$okCount = 0;
foreach ($results as $r) {
    if ($r['status'] === 'ok') {
        $okCount++;
    }
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
        .ok   { color: #155724; background: #d4edda; padding: .2rem .6rem; border-radius: 4px; font-size: .85rem; }
        .skip { color: #856404; background: #fff3cd; padding: .2rem .6rem; border-radius: 4px; font-size: .85rem; }
        table { width: 100%; border-collapse: collapse; margin: 1rem 0; }
        td, th { padding: .5rem .7rem; border-bottom: 1px solid #eee; text-align: left; }
        th { background: #f0f2f5; font-weight: 600; }
        .warn { background: #fff3cd; border: 1px solid #ffc107; padding: 1rem; border-radius: 6px; margin: 1rem 0; }
        .errs { background: #f8d7da; border: 1px solid #f5c6cb; padding: 1rem; border-radius: 6px; margin: 1rem 0; }
    </style>
</head>
<body>
<h1>Migrace stránek do CMS</h1>

<?php if ($errors): ?>
<div class="errs">
    <strong>Chyby:</strong>
    <ul><?php foreach ($errors as $e): ?><li><?= htmlspecialchars($e) ?></li><?php endforeach; ?></ul>
</div>
<?php endif; ?>

<table>
    <thead><tr><th>Slug</th><th>Název</th><th>Výsledek</th></tr></thead>
    <tbody>
        <?php foreach ($results as $r): ?>
        <tr>
            <td><code>/<?= htmlspecialchars($r['slug']) ?></code></td>
            <td><?= htmlspecialchars($r['title']) ?></td>
            <td><span class="<?= $r['status'] ?>"><?= htmlspecialchars($r['note']) ?></span></td>
        </tr>
        <?php endforeach; ?>
    </tbody>
</table>

<?php if ($okCount > 0): ?>
<p><strong><?= $okCount ?> stránek vloženo do databáze.</strong></p>
<?php endif; ?>

<div class="warn">
    <strong>Dulezite:</strong> Smažte soubor <code>migrate-pages.php</code> ze serveru!<br>
    Poté v <a href="<?= ADMIN_URL ?>/pages.php">adminu → Stránky</a> zkontrolujte a doupravte obsah.
</div>
<p>
    <a href="<?= ADMIN_URL ?>/pages.php"
       style="background:#4361ee;color:#fff;padding:.6rem 1.2rem;border-radius:4px;text-decoration:none;">
        Přejít do správy stránek
    </a>
</p>
</body>
</html>
