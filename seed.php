<?php
/**
 * Akrasia — ZveleCMS Seed
 * Naplní databázi všemi stránkami webu akrasia.zvelebil.online.
 * Spusť jednou po install.php: navštiv /seed.php v prohlížeči.
 */

define('ZVELE_CMS', true);
require_once __DIR__ . '/config.php';
require_once CORE_PATH . '/bootstrap.php';

// ── Pomocné funkce ────────────────────────────────────────────────────────
function seed_page(PDO $pdo, array $p): void {
    $exists = $pdo->prepare("SELECT id FROM zvele_pages WHERE slug = ?");
    $exists->execute([$p['slug']]);
    if ($exists->fetchColumn()) {
        echo "<li>⏭ Přeskočeno (existuje): <strong>{$p['slug']}</strong></li>";
        return;
    }
    $stmt = $pdo->prepare("
        INSERT INTO zvele_pages (slug, title, meta_description, blocks, status, sort_order, created_at, updated_at)
        VALUES (?, ?, ?, ?, 'published', ?, NOW(), NOW())
    ");
    $stmt->execute([
        $p['slug'],
        $p['title'],
        $p['meta'] ?? '',
        json_encode($p['blocks'], JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES),
        $p['sort'] ?? 99,
    ]);
    echo "<li>✅ Vytvořeno: <strong>{$p['slug']}</strong> – {$p['title']}</li>";
}

function seed_setting(PDO $pdo, string $key, string $value): void {
    $pdo->prepare("INSERT INTO zvele_settings (`key`, `value`) VALUES (?, ?)
                   ON DUPLICATE KEY UPDATE `value` = VALUES(`value`)")
        ->execute([$key, $value]);
}

function seed_menu(PDO $pdo, string $location, array $items): void {
    $exists = $pdo->prepare("SELECT id FROM zvele_menus WHERE location = ?");
    $exists->execute([$location]);
    if ($exists->fetchColumn()) {
        $pdo->prepare("UPDATE zvele_menus SET items = ? WHERE location = ?")
            ->execute([json_encode($items, JSON_UNESCAPED_UNICODE), $location]);
        echo "<li>🔄 Menu aktualizováno: <strong>$location</strong></li>";
    } else {
        $pdo->prepare("INSERT INTO zvele_menus (location, items) VALUES (?, ?)")
            ->execute([$location, json_encode($items, JSON_UNESCAPED_UNICODE)]);
        echo "<li>✅ Menu vytvořeno: <strong>$location</strong></li>";
    }
}

// ── Připojení k DB ────────────────────────────────────────────────────────
$pdo = Database::getInstance()->getPdo();

echo '<!DOCTYPE html><html lang="cs"><head><meta charset="UTF-8">
<title>Akrasia Seed</title>
<style>body{font-family:sans-serif;max-width:800px;margin:3rem auto;padding:0 1.5rem}
h1{color:#4e5699}ul{line-height:2}li{margin:.2rem 0}
.ok{color:green}.err{color:red}.warn{color:orange}</style></head><body>
<h1>🌱 Akrasia Seed</h1><ul>';

// ── Nastavení ─────────────────────────────────────────────────────────────
seed_setting($pdo, 'site_name',        'Akrasia');
seed_setting($pdo, 'site_description', 'Nezisková organizace propojující lidi s ADHD s ověřenými odborníky a komunitou.');
seed_setting($pdo, 'site_url',         'https://akrasia.zvelebil.online');
seed_setting($pdo, 'language',         'cs');
seed_setting($pdo, 'blog_posts_per_page', '9');
seed_setting($pdo, 'social_facebook',  'https://www.facebook.com/akrasia');
seed_setting($pdo, 'social_instagram', 'https://www.instagram.com/akrasia');
seed_setting($pdo, 'social_linkedin',  'https://www.linkedin.com/company/akrasia');
seed_setting($pdo, 'social_youtube',   '');
echo '<li>✅ Nastavení uložena</li>';

// ── Stránky ───────────────────────────────────────────────────────────────
$pages = [];

// ── HOMEPAGE ─────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'homepage',
    'title'  => 'Domů',
    'meta'   => 'Akrasia – nezisková organizace propojující lidi s ADHD s ověřenými odborníky a komunitou.',
    'sort'   => 1,
    'blocks' => [
        [
            'type' => 'hero',
            'data' => [
                'title'    => "Prostor, který<br>ADHD rozumí.",
                'subtitle' => 'Pomáháme lidem s ADHD najít podporu, porozumění a cestu vpřed. Propojujeme vás s ověřenými terapeuty a komunitou, která chápe.',
                'btn1_text'=> 'Hledám podporu',
                'btn1_url' => '/hledam-podporu',
                'btn2_text'=> 'Kdo jsme',
                'btn2_url' => '/kdo-jsme',
                'photo'    => 'photo-1.png',
            ],
        ],
        [
            'type' => 'stats',
            'data' => [
                'title'    => 'ADHD v číslech',
                'subtitle' => 'Realita, která nás motivuje jednat a podporovat.',
                'section_class' => 'section--alt',
                'items'    => [
                    ['number' => '5–7 %',     'label' => 'dospělých má ADHD'],
                    ['number' => '80 %',      'label' => 'případů zůstává nediagnostikováno'],
                    ['number' => '3×',        'label' => 'vyšší riziko propadu ve škole bez podpory'],
                    ['number' => '40+',       'label' => 'ověřených terapeutů v našem adresáři'],
                ],
            ],
        ],
        [
            'type' => 'features',
            'data' => [
                'title'    => 'Co děláme',
                'subtitle' => 'Tři cesty, jak pomáháme.',
                'section_class' => '',
                'items'    => [
                    [
                        'title'     => 'Adresář terapeutů',
                        'text'      => 'Ověření odborníci specializovaní na ADHD. Filtrujte podle kraje, města nebo specializace.',
                        'link_text' => 'Najít terapeuta →',
                        'link_url'  => '/terapeuti',
                    ],
                    [
                        'title'     => 'Pro firmy a školy',
                        'text'      => 'Vzdělávací programy, workshopy a konzultace pro zaměstnavatele a pedagogy.',
                        'link_text' => 'Pro firmy →',
                        'link_url'  => '/pro-firmy',
                    ],
                    [
                        'title'     => 'Komunita a příběhy',
                        'text'      => 'Sdílíme zkušenosti, které pomáhají. Přečtěte si příběhy lidí s ADHD.',
                        'link_text' => 'Číst příběhy →',
                        'link_url'  => '/vase-pribehy',
                    ],
                ],
            ],
        ],
        [
            'type' => 'cta',
            'data' => [
                'title'       => 'Podpořte nás',
                'text'        => 'Vaše podpora nám umožňuje pomáhat lidem s ADHD po celé České republice.',
                'button_text' => 'Darujte',
                'button_url'  => '/darujte',
                'style'       => 'donate',
            ],
        ],
    ],
];

// ── KDO JSME ─────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'kdo-jsme',
    'title'  => 'Kdo jsme',
    'meta'   => 'Jsme nezisková organizace, která věří, že ADHD není překážka – je to jiný způsob vnímání světa.',
    'sort'   => 2,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Kdo jsme',
                'subtitle'   => 'Jsme nezisková organizace, která věří, že ADHD není překážka – je to jiný způsob vnímání světa.',
                'breadcrumb' => [['label' => 'Kdo jsme']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<p>Akrasia je česká nezisková organizace zaměřená na zvyšování povědomí o ADHD a na podporu lidí, kteří s tímto neurovývojovým rozdílem žijí. Věříme, že každý člověk si zaslouží prostor, kde bude pochopen, podpořen a kde může naplno rozvinout svůj potenciál.</p><p>Náš název – Akrasia – pochází z řeckého slova označujícího jednání navzdory vlastnímu úsudku. Pro lidi s ADHD je tato zkušenost velmi blízká: vědí, co chtějí dělat, ale mozek jim to zkomplikuje. Chceme tuto zkušenost pojmenovat, pochopit a překonat.</p>',
            ],
        ],
        [
            'type' => 'features',
            'data' => [
                'columns' => '4',
                'items'   => [
                    ['title' => 'Příběh',        'text' => 'Jak a proč Akrasia vznikla. Odkud pocházíme a co nás pohání vpřed.', 'link_text' => 'Číst příběh →', 'link_url' => '/pribeh'],
                    ['title' => 'Mise',          'text' => 'Naše poslání, vize a hodnoty, které nás vedou při každém rozhodnutí.', 'link_text' => 'Naše mise →', 'link_url' => '/mise'],
                    ['title' => 'Tým',           'text' => 'Lidé, kteří stojí za Akrasií – s osobní zkušeností s ADHD i bez ní.', 'link_text' => 'Poznat tým →', 'link_url' => '/tym'],
                    ['title' => 'Spolupracujeme','text' => 'Organizace a instituce, které sdílejí naši vizi inkluzivní společnosti.', 'link_text' => 'Naši partneři →', 'link_url' => '/spolupracujeme'],
                ],
            ],
        ],
    ],
];

// ── MISE ──────────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'mise',
    'title'  => 'Naše mise',
    'meta'   => 'Posláním Akrasie je zvyšovat povědomí o ADHD v České republice a propojovat lidi s ADHD s odbornou pomocí.',
    'sort'   => 3,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Naše mise',
                'subtitle'   => 'Věříme ve svět, kde ADHD není překážkou – ale součástí pestrosti lidské zkušenosti.',
                'breadcrumb' => [['label' => 'Kdo jsme', 'url' => '/kdo-jsme'], ['label' => 'Mise']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Poslání</h2><p>Posláním Akrasie je zvyšovat povědomí o ADHD v České republice, bourat mýty a stigmata spojená s touto diagnózou a propojovat lidi s ADHD s odbornou pomocí, komunitou a zdroji, které potřebují k plnohodnotnému životu.</p><h2>Vize</h2><p>Svět, kde každý člověk s ADHD má přístup k pochopení, odborné péči a komunitě, která ho podporuje. Svět, kde neurodiverzita je vnímána jako přirozená součást lidské různorodosti – ne jako handicap.</p><h2>Naše hodnoty</h2><ul><li><strong>Přijetí:</strong> Každý člověk si zaslouží být přijat takový, jaký je.</li><li><strong>Odbornost:</strong> Spolupracujeme jen s ověřenými odborníky a opíráme se o vědu.</li><li><strong>Přístupnost:</strong> Naše služby a informace jsou dostupné pro všechny.</li><li><strong>Komunita:</strong> Společně jsme silnější – sdílíme zkušenosti a podporujeme se.</li><li><strong>Transparentnost:</strong> Jednáme otevřeně vůči lidem, partnerům i dárcům.</li></ul><h2>Co děláme</h2><p>Provozujeme adresář ověřených terapeutů specializovaných na ADHD, pořádáme vzdělávací akce pro firmy a školy, publikujeme informační materiály a budujeme komunitu lidí, kteří si navzájem rozumí.</p>',
            ],
        ],
    ],
];

// ── PŘÍBĚH ────────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'pribeh',
    'title'  => 'Příběh Akrasie',
    'meta'   => 'Jak vznikla organizace, která chce změnit způsob, jakým Česko vnímá ADHD.',
    'sort'   => 4,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Příběh Akrasie',
                'subtitle'   => 'Jak vznikla organizace, která chce změnit způsob, jakým Česko vnímá ADHD.',
                'breadcrumb' => [['label' => 'Kdo jsme', 'url' => '/kdo-jsme'], ['label' => 'Příběh']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Kde to začalo</h2><p>Akrasia vznikla z osobní zkušenosti zakladatelů, kteří sami žijí s ADHD nebo mají blízké s touto diagnózou. Narazili na stejné překážky, které zná mnoho lidí: nedostatek informací, dlouhé čekací doby na odborníky, stigma ve společnosti a pocit, že jejich mozek prostě „nefunguje správně".</p><p>Rozhodli se, že to změní. V roce 2023 vzniklo neformální uskupení lidí, kteří chtěli sdílet zkušenosti, vzdělávat se navzájem a pomáhat ostatním najít cestu. Brzy bylo jasné, že zájem je obrovský – a že je potřeba dát tomuto úsilí pevnější strukturu.</p><h2>Proč Akrasia</h2><p>Název pochází z řeckého slova <em>akrasia</em> – jednání navzdory vlastnímu záměru. Tato zkušenost je pro lidi s ADHD každodenní realitou: víte, co chcete udělat, ale mozek vás odvede jinam. Místo aby byl tento stav zdrojem studu, chceme ho pojmenovat a pochopit.</p><p>Akrasia pro nás znamená přijetí – sebe sama takového, jaký jsem, a zároveň odhodlání hledat způsoby, jak žít naplno přes všechny výzvy.</p><h2>Dnes</h2><p>Dnes Akrasia propojuje lidi s ověřenými terapeuty, vzdělává firmy a školy, pořádá osvětové akce a buduje komunitu, kde každý najde pochopení. Jsme malý tým s velkým srdcem – a každý den nás posiluje vědomí, že naše práce má smysl.</p>',
            ],
        ],
    ],
];

// ── TÝM ──────────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'tym',
    'title'  => 'Tým',
    'meta'   => 'Lidé, kteří stojí za Akrasií – s osobní zkušeností s ADHD i bez ní.',
    'sort'   => 5,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Tým',
                'subtitle'   => 'Lidé, kteří stojí za Akrasií – s osobní zkušeností s ADHD i bez ní.',
                'breadcrumb' => [['label' => 'Kdo jsme', 'url' => '/kdo-jsme'], ['label' => 'Tým']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<p>Náš tým tvoří lidé s různými zkušenostmi, ale se společným cílem: pomáhat lidem s ADHD žít plnohodnotný život. Někteří z nás mají ADHD sami, jiní mají blízké s touto diagnózou. Všichni věříme, že neurodiverzita je bohatstvím – ne překážkou.</p><p><em>Tato stránka se připravuje – brzy zde najdete profily členů našeho týmu.</em></p>',
            ],
        ],
    ],
];

// ── HLEDÁM PODPORU ────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'hledam-podporu',
    'title'  => 'Hledám podporu',
    'meta'   => 'Máte ADHD nebo podezření na diagnózu? Pomůžeme vám najít správnou cestu.',
    'sort'   => 6,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Hledám podporu',
                'subtitle'   => 'Máte ADHD nebo podezření na diagnózu? Pomůžeme vám najít správnou cestu.',
                'breadcrumb' => [['label' => 'Hledám podporu']],
            ],
        ],
        [
            'type' => 'cta',
            'data' => [
                'title'       => 'Najděte svého terapeuta',
                'text'        => 'Náš adresář obsahuje ověřené terapeuty a odborníky specializované na ADHD po celé České republice. Filtrujte podle kraje, města nebo specializace.',
                'button_text' => 'Otevřít adresář terapeutů',
                'button_url'  => '/terapeuti',
                'style'       => 'old-rose',
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Kde začít?</h2><p>Pokud máte podezření na ADHD nebo jste právě dostali diagnózu, může být těžké vědět, kam se obrátit. Zde jsou základní kroky, které vám pomohou zorientovat se.</p><h3>1. Získejte diagnózu</h3><p>Pokud ještě nemáte diagnózu, prvním krokem je návštěva praktického lékaře nebo psychiatra. Požádejte o doporučení na specializované vyšetření ADHD.</p><h3>2. Najděte odbornou pomoc</h3><p>Terapie, koučink nebo psychiatrická péče – každý potřebuje něco jiného. V našem <a href="/terapeuti">adresáři terapeutů</a> najdete ověřené odborníky, kteří mají zkušenosti s ADHD a jsou připraveni vám pomoci.</p><h3>3. Najděte komunitu</h3><p>Sdílení zkušeností s lidmi, kteří vás chápou, může být nesmírně léčivé. Přečtěte si <a href="/vase-pribehy">příběhy ostatních</a> nebo se zapojte do naší komunity.</p>',
            ],
        ],
        [
            'type' => 'features',
            'data' => [
                'items' => [
                    ['title' => 'Adresář terapeutů', 'text' => 'Ověření odborníci specializovaní na ADHD ve vašem okolí.', 'link_text' => 'Najít terapeuta →', 'link_url' => '/terapeuti'],
                    ['title' => 'Vaše příběhy',      'text' => 'Přečtěte si, jak ostatní zvládají ADHD v každodenním životě.',   'link_text' => 'Číst příběhy →',  'link_url' => '/vase-pribehy'],
                    ['title' => 'Blog',               'text' => 'Informace, tipy a inspirace pro život s ADHD.',                   'link_text' => 'Číst blog →',     'link_url' => '/blog'],
                ],
            ],
        ],
    ],
];

// ── TERAPEUTI ─────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'terapeuti',
    'title'  => 'Adresář terapeutů',
    'meta'   => 'Adresář ověřených terapeutů specializovaných na ADHD. Filtrujte podle kraje, města nebo typu podpory.',
    'sort'   => 7,
    'blocks' => [
        ['type' => 'terapeuti', 'data' => []],
    ],
];

// ── PRO FIRMY ─────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'pro-firmy',
    'title'  => 'Pro firmy',
    'meta'   => 'Pomáháme zaměstnavatelům vytvářet inkluzivní prostředí, kde mohou lidé s ADHD naplno prospívat.',
    'sort'   => 8,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Pro firmy',
                'subtitle'   => 'Pomáháme zaměstnavatelům vytvářet inkluzivní prostředí, kde mohou lidé s ADHD naplno prospívat.',
                'breadcrumb' => [['label' => 'Pro firmy']],
            ],
        ],
        [
            'type' => 'features',
            'data' => [
                'title'        => 'Proč investovat do neurodiverzity?',
                'subtitle'     => 'Zaměstnanci s ADHD přinášejí jedinečné silné stránky – kreativitu, hyperfokus a schopnost nekonvenčního myšlení.',
                'section_class'=> 'section--alt',
                'items'        => [
                    ['title' => 'Audit inkluzivity',   'text' => 'Zhodnotíme vaše firemní prostředí z pohledu přístupnosti pro zaměstnance s ADHD a navrhneme konkrétní zlepšení.'],
                    ['title' => 'Školení a workshopy', 'text' => 'Vzdělávací programy pro manažery a HR týmy – jak rozpoznat ADHD, jak vést rozhovory a jak nastavit podpůrné procesy.'],
                    ['title' => 'Konzultace',          'text' => 'Individuální konzultace pro firmy, které chtějí zavést konkrétní opatření na podporu neurodiverzních zaměstnanců.'],
                ],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Co získáte spoluprací s Akrasií</h2><ul><li>Lepší pochopení potřeb neurodiverzních zaměstnanců</li><li>Konkrétní nástroje pro inkluzivní vedení</li><li>Snížení fluktuace a zvýšení spokojenosti zaměstnanců</li><li>Posílení reputace jako inkluzivního zaměstnavatele</li><li>Přístup k talentům, které jiní přehlíží</li></ul><h2>Máte zájem?</h2><p>Napište nám na <a href="mailto:info@akrasia.cz">info@akrasia.cz</a> a společně navrhneme řešení na míru vaší organizaci.</p>',
            ],
        ],
    ],
];

// ── PRO ŠKOLY ─────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'pro-skoly',
    'title'  => 'Pro školy',
    'meta'   => 'Pomáháme pedagogům lépe rozumět žákům s ADHD a vytvářet prostředí, kde mohou uspět.',
    'sort'   => 9,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Pro školy',
                'subtitle'   => 'Pomáháme pedagogům lépe rozumět žákům s ADHD a vytvářet prostředí, kde mohou uspět.',
                'breadcrumb' => [['label' => 'Pro školy']],
            ],
        ],
        [
            'type' => 'features',
            'data' => [
                'title'         => 'Co nabízíme školám',
                'subtitle'      => 'Vzdělávání a podpora pro učitele, asistenty i vedení škol.',
                'section_class' => 'section--alt',
                'items'         => [
                    ['title' => 'Školení pro pedagogy', 'text' => 'Workshopy zaměřené na pochopení ADHD, praktické strategie ve výuce a komunikaci s žáky a jejich rodiči.'],
                    ['title' => 'Metodická podpora',    'text' => 'Materiály a metodiky pro práci s žáky s ADHD v běžné třídě i ve speciálním vzdělávání.'],
                    ['title' => 'Konzultace pro školy', 'text' => 'Individuální konzultace pro pedagogické týmy – jak nastavit podpůrná opatření a spolupracovat s rodiči.'],
                ],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Proč je to důležité</h2><p>ADHD se projevuje u 5–7 % dětí školního věku. Bez správné podpory mají tyto děti výrazně horší výsledky, vyšší riziko školního neúspěchu a negativního sebeobrazu. S informovanými pedagogy a správným prostředím mohou tyto děti plně rozvinout svůj potenciál.</p><h2>Jak začít spolupráci</h2><p>Napište nám na <a href="mailto:info@akrasia.cz">info@akrasia.cz</a> a domluvíme se na bezplatné úvodní konzultaci, kde zjistíme, co vaše škola nejvíce potřebuje.</p>',
            ],
        ],
    ],
];

// ── ZAPOJTE SE ────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'zapojte-se',
    'title'  => 'Zapojte se',
    'meta'   => 'Připojte se k Akrasii – jako člen, dobrovolník nebo dárce.',
    'sort'   => 10,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Zapojte se',
                'subtitle'   => 'Připojte se k Akrasii – jako člen, dobrovolník nebo dárce.',
                'breadcrumb' => [['label' => 'Zapojte se']],
            ],
        ],
        [
            'type' => 'features',
            'data' => [
                'items' => [
                    ['title' => 'Staňte se členem',  'text' => 'Jako člen spolurozhodujete o směřování organizace a jste součástí komunity.', 'link_text' => 'Přihlášení →', 'link_url' => '/stante-se-clenem'],
                    ['title' => 'Dobrovolnictví',     'text' => 'Pomozte nám s konkrétními projekty – dle vašich schopností a časových možností.',   'link_text' => 'Chci pomoci →','link_url' => '/dobrovolnictvi'],
                    ['title' => 'Stáž',               'text' => 'Získejte praxi v neziskovém sektoru a zároveň pomozte těm, kdo to potřebují.',       'link_text' => 'O stáži →',   'link_url' => '/staz'],
                    ['title' => 'Darujte',            'text' => 'Finanční podpora nám umožňuje rozvíjet naše aktivity a pomáhat více lidem.',          'link_text' => 'Darovat →',   'link_url' => '/darujte'],
                ],
                'columns' => '4',
            ],
        ],
    ],
];

// ── DOBROVOLNICTVÍ ────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'dobrovolnictvi',
    'title'  => 'Dobrovolnictví',
    'meta'   => 'Pomozte Akrasii jako dobrovolník – dle vašich schopností a časových možností.',
    'sort'   => 11,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Dobrovolnictví',
                'subtitle'   => 'Pomozte nám s konkrétními projekty – dle vašich schopností a časových možností.',
                'breadcrumb' => [['label' => 'Zapojte se', 'url' => '/zapojte-se'], ['label' => 'Dobrovolnictví']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Jak pomoci?</h2><p>Hledáme lidi se srdcem na pravém místě. Nezáleží na tom, zda máte ADHD nebo ne – záleží na tom, co umíte a co vás baví.</p><h2>Oblasti dobrovolnictví</h2><ul><li><strong>Komunikace a sociální sítě</strong> – tvorba obsahu, správa profilů</li><li><strong>Grafika a design</strong> – materiály pro akce, infografiky</li><li><strong>Organizace akcí</strong> – pomoc s plánováním a realizací</li><li><strong>Překlad a korektury</strong> – čeština i angličtina</li><li><strong>IT a web</strong> – technická podpora projektu</li></ul><h2>Zájem?</h2><p>Napište nám na <a href="mailto:info@akrasia.cz">info@akrasia.cz</a> a řekněte nám, čím chcete přispět. Ozveme se vám co nejdříve.</p>',
            ],
        ],
    ],
];

// ── STÁŽ ─────────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'staz',
    'title'  => 'Stáž',
    'meta'   => 'Získejte praxi v neziskovém sektoru a zároveň pomozte těm, kdo to potřebují.',
    'sort'   => 12,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Stáž v Akrasii',
                'subtitle'   => 'Získejte praxi v neziskovém sektoru a zároveň pomozte těm, kdo to potřebují.',
                'breadcrumb' => [['label' => 'Zapojte se', 'url' => '/zapojte-se'], ['label' => 'Stáž']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Co nabízíme stážistům</h2><p>Stáž v Akrasii je příležitost zapojit se do smysluplné práce s přímým dopadem na životy lidí s ADHD. Nabízíme volnou ruku, mentorování a reálné zkušenosti z neziskového sektoru.</p><h2>Oblasti stáže</h2><ul><li>Marketing a komunikace</li><li>Fundraising</li><li>Koordinace projektů</li><li>Výzkum a vzdělávání</li></ul><h2>Požadavky</h2><ul><li>Zájem o téma ADHD a neurodiverzity</li><li>Spolehlivost a samostatnost</li><li>Alespoň 10 hodin týdně (domluva možná)</li></ul><h2>Přihlaste se</h2><p>Napište nám na <a href="mailto:info@akrasia.cz">info@akrasia.cz</a> s předmětem „Stáž" a přiložte krátký motivační dopis. Rádi se vám ozveme.</p>',
            ],
        ],
    ],
];

// ── STAŇTE SE ČLENEM ──────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'stante-se-clenem',
    'title'  => 'Staňte se členem',
    'meta'   => 'Jako člen Akrasie se stáváte součástí komunity, která mění způsob, jak Česko vnímá ADHD.',
    'sort'   => 13,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Staňte se členem',
                'subtitle'   => 'Jako člen Akrasie se stáváte součástí komunity, která mění způsob, jak Česko vnímá ADHD.',
                'breadcrumb' => [['label' => 'Zapojte se', 'url' => '/zapojte-se'], ['label' => 'Staňte se členem']],
            ],
        ],
        [
            'type' => 'features',
            'data' => [
                'title'         => 'Co členství obnáší',
                'section_class' => 'section--alt',
                'items'         => [
                    ['title' => 'Spolurozhodování', 'text' => 'Jako člen máte právo hlasovat na valné hromadě a aktivně se podílet na směřování organizace.'],
                    ['title' => 'Informace jako první', 'text' => 'Členský newsletter s nejnovějšími informacemi, akcemi a příležitostmi dříve, než jsou zveřejněny.'],
                    ['title' => 'Komunita', 'text' => 'Přístup do uzavřené komunity členů, kde sdílíme zkušenosti, podporujeme se a spolupracujeme.'],
                ],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Přihláška za člena</h2><p>Vyplňte přihlášku a my se vám ozveme s dalšími informacemi. Napište nám na <a href="mailto:info@akrasia.cz">info@akrasia.cz</a> s předmětem „Členství".</p>',
            ],
        ],
    ],
];

// ── DARUJTE ───────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'darujte',
    'title'  => 'Darujte',
    'meta'   => 'Podpořte Akrasii finančně a pomozte nám pomáhat lidem s ADHD.',
    'sort'   => 14,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Darujte',
                'subtitle'   => 'Vaše podpora nám umožňuje pomáhat lidem s ADHD po celé České republice.',
                'breadcrumb' => [['label' => 'Darujte']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Proč darovat?</h2><p>Akrasia je nezisková organizace závislá na podpoře dárců. Každý příspěvek nám pomáhá udržovat adresář terapeutů, pořádat vzdělávací akce a budovat komunitu.</p><h2>Jak darovat</h2><p>Dar lze poslat bankovním převodem na účet Akrasia, z.s.:</p><ul><li><strong>Číslo účtu:</strong> <em>(brzy doplníme)</em></li><li><strong>IBAN:</strong> <em>(brzy doplníme)</em></li><li><strong>Variabilní symbol:</strong> vaše jméno nebo IČO</li></ul><h2>Transparentnost</h2><p>Všechny příjmy a výdaje zveřejňujeme v naší výroční zprávě. Vaše peníze jdou přímo k lidem, kteří je potřebují.</p>',
            ],
        ],
    ],
];

// ── VAŠE PŘÍBĚHY ─────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'vase-pribehy',
    'title'  => 'Vaše příběhy',
    'meta'   => 'Přečtěte si příběhy lidí s ADHD – inspiraci, zkušenosti a odvahu sdílet.',
    'sort'   => 15,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Vaše příběhy',
                'subtitle'   => 'Sdílení zkušeností pomáhá – čtěte příběhy lidí, kteří vědí, jaké to je.',
                'breadcrumb' => [['label' => 'Vaše příběhy']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<p>Každý příběh je jiný – ale všechny mají jedno společné: odvahu pojmenovat ADHD a hledat cestu vpřed. Tady najdete příběhy skutečných lidí, kteří souhlasili s tím, aby se jejich zkušenost stala inspirací pro ostatní.</p><p><em>Příběhy se připravují. Chcete sdílet svůj příběh? Napište nám na <a href="mailto:info@akrasia.cz">info@akrasia.cz</a>.</em></p>',
            ],
        ],
    ],
];

// ── SPOLUPRACUJEME ────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'spolupracujeme',
    'title'  => 'Spolupracujeme',
    'meta'   => 'Partneři a organizace, které sdílejí naši vizi inkluzivní společnosti.',
    'sort'   => 16,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Spolupracujeme',
                'subtitle'   => 'Partneři a organizace, které sdílejí naši vizi inkluzivní společnosti.',
                'breadcrumb' => [['label' => 'Kdo jsme', 'url' => '/kdo-jsme'], ['label' => 'Spolupracujeme']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<p>Naše práce by nebyla možná bez podpory partnerů, kteří věří v to, co děláme. Spolupracujeme s firmami, akademickými institucemi a dalšími organizacemi, které aktivně přispívají k vytváření inkluzivního prostředí pro lidi s ADHD.</p>',
            ],
        ],
        [
            'type' => 'features',
            'data' => [
                'items' => [
                    ['title' => 'Tamly',         'text' => 'Strategický partner v oblasti HR a inkluzivního zaměstnávání.'],
                    ['title' => 'UTB Zlín',       'text' => 'Akademický partner – Univerzita Tomáše Bati ve Zlíně podporuje naše vzdělávací aktivity.'],
                    ['title' => 'Thermo Fisher',  'text' => 'Korporátní partner s aktivním programem podpory neurodiverzity na pracovišti.'],
                ],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Chcete spolupracovat?</h2><p>Jsme otevřeni novým partnerstvím – ať už jste firma, škola, akademická instituce nebo jiná nezisková organizace. Napište nám na <a href="mailto:info@akrasia.cz">info@akrasia.cz</a>.</p>',
            ],
        ],
    ],
];

// ── GDPR ──────────────────────────────────────────────────────────────────
$pages[] = [
    'slug'   => 'gdpr',
    'title'  => 'Zásady ochrany osobních údajů',
    'meta'   => 'Informace o zpracování osobních údajů v souladu s GDPR.',
    'sort'   => 17,
    'blocks' => [
        [
            'type' => 'page-hero',
            'data' => [
                'title'      => 'Zásady ochrany osobních údajů',
                'subtitle'   => 'Informace o zpracování osobních údajů v souladu s GDPR.',
                'breadcrumb' => [['label' => 'Ochrana osobních údajů']],
            ],
        ],
        [
            'type' => 'text',
            'data' => [
                'content' => '<h2>Správce osobních údajů</h2><p>Akrasia, z.s.<br>IČO: <em>(doplnit)</em><br>E-mail: <a href="mailto:info@akrasia.cz">info@akrasia.cz</a></p><h2>Jaké údaje zpracováváme</h2><p>Zpracováváme pouze údaje, které nám dobrovolně poskytnete prostřednictvím kontaktních formulářů na tomto webu (jméno, e-mail, zpráva). Tyto údaje používáme výhradně k zodpovězení vašeho dotazu nebo k realizaci vámi požadované spolupráce.</p><h2>Cookies</h2><p>Tento web používá pouze technicky nezbytné cookies. Analytické a marketingové cookies aktivujeme pouze s vaším souhlasem prostřednictvím cookie lišty.</p><h2>Vaše práva</h2><p>Máte právo na přístup k vašim osobním údajům, jejich opravu, výmaz, omezení zpracování a přenositelnost. Souhlas lze kdykoli odvolat. V případě dotazů nás kontaktujte na <a href="mailto:info@akrasia.cz">info@akrasia.cz</a>.</p>',
            ],
        ],
    ],
];

// ── Uložení stránek ───────────────────────────────────────────────────────
foreach ($pages as $p) {
    seed_page($pdo, $p);
}

// ── Menu – hlavní navigace ────────────────────────────────────────────────
seed_menu($pdo, 'main', [
    ['label' => 'Kdo jsme',       'url' => '/kdo-jsme'],
    ['label' => 'Hledám podporu', 'url' => '/hledam-podporu'],
    ['label' => 'Terapeuti',      'url' => '/terapeuti'],
    ['label' => 'Pro firmy',      'url' => '/pro-firmy'],
    ['label' => 'Pro školy',      'url' => '/pro-skoly'],
    ['label' => 'Zapojte se',     'url' => '/zapojte-se'],
    ['label' => 'Blog',           'url' => '/blog'],
    ['label' => 'Darujte',        'url' => '/darujte'],
]);

// ── Menu – patička ────────────────────────────────────────────────────────
seed_menu($pdo, 'footer', [
    ['label' => 'Ochrana osobních údajů', 'url' => '/gdpr'],
    ['label' => 'Vaše příběhy',           'url' => '/vase-pribehy'],
    ['label' => 'Spolupracujeme',         'url' => '/spolupracujeme'],
    ['label' => 'Staňte se členem',       'url' => '/stante-se-clenem'],
    ['label' => 'Dobrovolnictví',         'url' => '/dobrovolnictvi'],
    ['label' => 'Stáž',                   'url' => '/staz'],
]);

echo '</ul>';
echo '<h2>✅ Hotovo!</h2>';
echo '<p>Web je připraven. <a href="/">Přejít na web →</a> | <a href="/admin">Přejít do adminu →</a></p>';
echo '<p style="color:#888;font-size:.9rem">Doporučujeme seed.php smazat nebo zablokovat po prvním spuštění.</p>';
echo '</body></html>';
