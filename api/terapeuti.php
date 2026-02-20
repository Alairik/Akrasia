<?php
/**
 * Akrasia – Proxy pro Google Sheets CSV terapeutů
 * Načítá CSV server-side (obchází CORS), parsuje a vrací JSON.
 */

require_once dirname(__DIR__) . '/includes/config.php';

header('Content-Type: application/json; charset=utf-8');
header('Access-Control-Allow-Origin: ' . SITE_URL);
header('Cache-Control: public, max-age=300'); // 5 min cache

$csvUrl = defined('TERAPEUTI_CSV_URL') ? TERAPEUTI_CSV_URL : '';

if (empty($csvUrl)) {
    echo json_encode(['error' => 'CSV URL není nakonfigurována.', 'columns' => [], 'data' => []]);
    exit;
}

// ── Stažení CSV ──────────────────────────────────────────────────────────────
$csv = false;

// Pokus 1: cURL (preferováno)
if (function_exists('curl_init')) {
    $ch = curl_init($csvUrl);
    curl_setopt_array($ch, [
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_FOLLOWLOCATION => true,
        CURLOPT_MAXREDIRS      => 5,
        CURLOPT_TIMEOUT        => 15,
        CURLOPT_SSL_VERIFYPEER => true,
        CURLOPT_USERAGENT      => 'Mozilla/5.0 (compatible; Akrasia/1.0)',
        CURLOPT_HTTPHEADER     => ['Accept: text/csv, text/plain, */*'],
    ]);
    $csv      = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curlErr  = curl_error($ch);
    curl_close($ch);

    if ($csv === false || $httpCode !== 200) {
        $csv = false;
    }
}

// Pokus 2: file_get_contents jako záloha
if ($csv === false) {
    $context = stream_context_create([
        'http' => [
            'method'          => 'GET',
            'timeout'         => 15,
            'follow_location' => true,
            'max_redirects'   => 5,
            'header'          => "User-Agent: Mozilla/5.0 (compatible; Akrasia/1.0)\r\nAccept: text/csv\r\n",
        ],
        'ssl' => ['verify_peer' => true],
    ]);
    $csv = @file_get_contents($csvUrl, false, $context);
}

if ($csv === false || trim($csv) === '') {
    echo json_encode([
        'error'   => 'Nepodařilo se načíst data z Google Sheets. Zkontrolujte, zda je tabulka zveřejněna.',
        'columns' => [],
        'data'    => [],
    ]);
    exit;
}

// ── Parsování CSV ────────────────────────────────────────────────────────────

// Odstraní UTF-8 BOM
$csv = ltrim($csv, "\xEF\xBB\xBF");
// Sjednotit konce řádků
$csv = str_replace(["\r\n", "\r"], "\n", $csv);

$lines = array_values(array_filter(explode("\n", $csv), fn($l) => trim($l) !== ''));

if (count($lines) < 2) {
    echo json_encode([
        'error'   => 'Tabulka neobsahuje žádná data (méně než 2 řádky).',
        'columns' => [],
        'data'    => [],
    ]);
    exit;
}

/**
 * Rozdělí jeden CSV řádek na hodnoty (respektuje uvozovky).
 */
function parseCsvLine(string $line): array
{
    $result   = [];
    $current  = '';
    $inQuotes = false;
    $len      = strlen($line);

    for ($i = 0; $i < $len; $i++) {
        $ch = $line[$i];
        if ($ch === '"') {
            if ($inQuotes && isset($line[$i + 1]) && $line[$i + 1] === '"') {
                $current .= '"';
                $i++;
            } else {
                $inQuotes = !$inQuotes;
            }
        } elseif ($ch === ',' && !$inQuotes) {
            $result[] = $current;
            $current  = '';
        } else {
            $current .= $ch;
        }
    }
    $result[] = $current;
    return $result;
}

// Záhlaví – normalize: lowercase, trim
$rawHeaders = parseCsvLine($lines[0]);
$headers    = array_map(
    fn($h) => mb_strtolower(trim($h, " \t\r\n\"\xEF\xBB\xBF"), 'UTF-8'),
    $rawHeaders
);

// Řádky dat
$data = [];
for ($i = 1; $i < count($lines); $i++) {
    $values = parseCsvLine($lines[$i]);
    $row    = [];
    foreach ($headers as $j => $h) {
        $row[$h] = trim($values[$j] ?? '', " \t\r\n\"");
    }
    // Přeskočit prázdné řádky
    if (implode('', $row) === '') continue;
    $data[] = $row;
}

echo json_encode(
    ['columns' => $headers, 'data' => $data, 'count' => count($data)],
    JSON_UNESCAPED_UNICODE | JSON_UNESCAPED_SLASHES
);
