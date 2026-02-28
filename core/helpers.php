<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

/**
 * Escape HTML output — primary XSS prevention.
 */
function e(?string $value): string
{
    return htmlspecialchars($value ?? '', ENT_QUOTES, 'UTF-8');
}

/**
 * Generate full URL from path.
 */
function url(string $path = ''): string
{
    $path = ltrim($path, '/');
    return rtrim(SITE_URL, '/') . ($path ? '/' . $path : '');
}

/**
 * Generate asset URL with cache-busting.
 */
function asset(string $path): string
{
    $filePath = ROOT_PATH . '/' . ltrim($path, '/');
    $version = file_exists($filePath) ? filemtime($filePath) : '1';
    return url($path) . '?v=' . $version;
}

/**
 * Generate CSRF hidden input field.
 */
function csrf_field(): string
{
    $token = $_SESSION['csrf_token'] ?? '';
    return '<input type="hidden" name="csrf_token" value="' . e($token) . '">';
}

/**
 * Generate CSRF token (called in bootstrap).
 */
function csrf_token(): string
{
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

/**
 * Validate CSRF token from POST request.
 */
function csrf_verify(): bool
{
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

/**
 * Generate slug from string (Czech diacritics safe).
 */
function slugify(string $text): string
{
    if (function_exists('transliterator_transliterate')) {
        $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    } else {
        $text = iconv('UTF-8', 'ASCII//TRANSLIT//IGNORE', $text);
        $text = strtolower($text);
    }
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

/**
 * Redirect to URL and exit.
 */
function redirect(string $url, int $code = 302): never
{
    http_response_code($code);
    header('Location: ' . $url);
    exit;
}

/**
 * Get setting value from database.
 */
function setting(string $key, mixed $default = null): mixed
{
    static $cache = [];

    if (isset($cache[$key])) {
        return $cache[$key];
    }

    $db = Database::getInstance();
    $row = $db->fetchOne("SELECT `value`, `type` FROM zvele_settings WHERE `key` = ?", [$key]);

    if (!$row) {
        return $default;
    }

    $value = match ($row['type']) {
        'int' => (int) $row['value'],
        'bool' => (bool) $row['value'],
        'json' => json_decode($row['value'], true),
        default => $row['value'],
    };

    $cache[$key] = $value;
    return $value;
}

/**
 * Flash message — set or get.
 */
function flash(string $type = null, string $message = null): ?array
{
    if ($type !== null && $message !== null) {
        $_SESSION['flash'] = ['type' => $type, 'message' => $message];
        return null;
    }

    $flash = $_SESSION['flash'] ?? null;
    unset($_SESSION['flash']);
    return $flash;
}

/**
 * Debug dump and die.
 */
function dd(mixed ...$vars): never
{
    if (ENVIRONMENT !== 'dev') {
        die('Debug not available.');
    }

    echo '<pre style="background:#1e293b;color:#e2e8f0;padding:1rem;margin:1rem;border-radius:0.5rem;overflow:auto;">';
    foreach ($vars as $var) {
        var_dump($var);
    }
    echo '</pre>';
    die();
}

/**
 * Format date in Czech locale.
 */
function format_date(?string $datetime, ?string $format = null): string
{
    if (!$datetime) {
        return '';
    }

    $format = $format ?? setting('date_format', 'j. n. Y');
    return date($format, strtotime($datetime));
}

/**
 * Truncate text to given length.
 */
function excerpt(string $text, int $length = 160): string
{
    $text = strip_tags($text);
    if (mb_strlen($text) <= $length) {
        return $text;
    }
    return mb_substr($text, 0, $length) . '...';
}

/**
 * Get current request URI (without query string).
 * Strips SITE_BASE prefix when the app runs in a subfolder.
 */
function request_uri(): string
{
    $uri = parse_url($_SERVER['REQUEST_URI'] ?? '/', PHP_URL_PATH);
    $uri = '/' . trim($uri, '/');

    // Strip subfolder prefix (e.g. /akrasia) so router sees /kdo-jsme not /akrasia/kdo-jsme
    $base = defined('SITE_BASE') ? rtrim(SITE_BASE, '/') : '';
    if ($base !== '' && str_starts_with($uri, $base)) {
        $uri = substr($uri, strlen($base));
    }

    return $uri === '' ? '/' : $uri;
}

/**
 * Check if current request is POST.
 */
function is_post(): bool
{
    return $_SERVER['REQUEST_METHOD'] === 'POST';
}

/**
 * Get client IP address.
 */
function client_ip(): string
{
    return $_SERVER['HTTP_X_FORWARDED_FOR']
        ?? $_SERVER['HTTP_X_REAL_IP']
        ?? $_SERVER['REMOTE_ADDR']
        ?? '0.0.0.0';
}

/**
 * Dekorativní prvky (logo symbol + puntíky) do sekce.
 * $picks = pole indexů 0–11 z 12 předdefinovaných kombinací.
 */
function deco_html(array $picks): string
{
    $base   = rtrim(SITE_URL, '/') . '/assets/brand/';
    $combos = [
        /* 0  */ ['akrasia_logo2025_symbol.svg', 'right:3%;bottom:10%',  'rotate(14deg)',   90,  0.12],
        /* 1  */ ['punct-1.svg',                 'left:1%;top:8%',       'rotate(-22deg)',  60,  0.09],
        /* 2  */ ['akrasia_logo2025_symbol.svg', 'right:5%;top:12%',     'rotate(9deg)',    70,  0.10],
        /* 3  */ ['punct-2.svg',                 'right:2%;top:5%',      'rotate(-17deg)',  55,  0.09],
        /* 4  */ ['akrasia_logo2025_symbol.svg', 'left:3%;bottom:8%',    'rotate(12deg)',   80,  0.10],
        /* 5  */ ['akrasia_logo2025_symbol.svg', 'left:15%;top:20%',     'rotate(-35deg)', 110,  0.07],
        /* 6  */ ['punct-1.svg',                 'right:22%;bottom:5%',  'rotate(55deg)',   45,  0.11],
        /* 7  */ ['punct-2.svg',                 'left:8%;top:40%',      'rotate(-8deg)',   75,  0.08],
        /* 8  */ ['akrasia_logo2025_symbol.svg', 'right:10%;top:50%',    'rotate(180deg)',  50,  0.13],
        /* 9  */ ['punct-1.svg',                 'left:48%;bottom:3%',   'rotate(30deg)',   65,  0.09],
        /* 10 */ ['punct-2.svg',                 'right:28%;top:15%',    'rotate(-60deg)',  85,  0.08],
        /* 11 */ ['akrasia_logo2025_symbol.svg', 'left:5%;bottom:20%',   'rotate(-15deg)', 100,  0.07],
    ];
    $html = '<div class="deco-layer" aria-hidden="true">' . "\n";
    foreach ($picks as $i) {
        if (!isset($combos[$i])) {
            continue;
        }
        [$asset, $pos, $rot, $w, $op] = $combos[$i];
        $isPunct = str_contains($asset, 'punct');
        $cls     = $isPunct ? 'deco-item deco-item--punct' : 'deco-item';
        $html   .= "    <div class=\"{$cls}\" style=\"{$pos};transform:{$rot};opacity:{$op}\">"
                 . "<img src=\"{$base}{$asset}\" width=\"{$w}\" alt=\"\" aria-hidden=\"true\"></div>\n";
    }
    $html .= '</div>';
    return $html;
}
