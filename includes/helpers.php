<?php
/**
 * Helper functions
 */

function h(string $str): string {
    return htmlspecialchars($str, ENT_QUOTES, 'UTF-8');
}

function redirect(string $url): void {
    header('Location: ' . $url);
    exit;
}

function csrf_token(): string {
    if (empty($_SESSION['csrf_token'])) {
        $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
    }
    return $_SESSION['csrf_token'];
}

function csrf_field(): string {
    return '<input type="hidden" name="csrf_token" value="' . h(csrf_token()) . '">';
}

function csrf_verify(): bool {
    $token = $_POST['csrf_token'] ?? '';
    return hash_equals($_SESSION['csrf_token'] ?? '', $token);
}

function flash_set(string $type, string $message): void {
    $_SESSION['flash'][] = ['type' => $type, 'message' => $message];
}

function flash_get(): array {
    $messages = $_SESSION['flash'] ?? [];
    unset($_SESSION['flash']);
    return $messages;
}

function slug(string $text): string {
    $text = transliterator_transliterate('Any-Latin; Latin-ASCII; Lower()', $text);
    $text = preg_replace('/[^a-z0-9]+/', '-', $text);
    return trim($text, '-');
}

function format_date(string $date): string {
    return date('j. n. Y H:i', strtotime($date));
}

function excerpt(string $html, int $length = 200): string {
    $text = strip_tags($html);
    if (mb_strlen($text) <= $length) return $text;
    return mb_substr($text, 0, $length) . '…';
}

function upload_image(array $file): ?string {
    $allowed = ['image/jpeg', 'image/png', 'image/gif', 'image/webp'];

    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    if (!in_array($file['type'], $allowed, true)) return null;
    if ($file['size'] > 5 * 1024 * 1024) return null; // 5MB max

    // Verify it's actually an image
    $imageInfo = getimagesize($file['tmp_name']);
    if ($imageInfo === false) return null;

    $ext = match($imageInfo['mime']) {
        'image/jpeg' => 'jpg',
        'image/png' => 'png',
        'image/gif' => 'gif',
        'image/webp' => 'webp',
        default => null,
    };
    if ($ext === null) return null;

    $yearMonth = date('Y/m');
    $dir = UPLOADS_PATH . '/' . $yearMonth;
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
    }

    $filename = bin2hex(random_bytes(8)) . '.' . $ext;
    $path = $dir . '/' . $filename;

    if (move_uploaded_file($file['tmp_name'], $path)) {
        return $yearMonth . '/' . $filename;
    }
    return null;
}

/**
 * Dekorativní prvky (logo symbol + puntíky) do sekce.
 * $picks = pole indexů 0–11 z 12 předdefinovaných kombinací.
 * Každá kombinace = [asset, css_position, transform, width, opacity]
 */
function deco_html(array $picks): string {
    $base = SITE_URL . '/assets/brand/';
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
        if (!isset($combos[$i])) continue;
        [$asset, $pos, $rot, $w, $op] = $combos[$i];
        $html .= "    <div class=\"deco-item\" style=\"{$pos};transform:{$rot};opacity:{$op}\">"
               . "<img src=\"{$base}{$asset}\" width=\"{$w}\" alt=\"\" aria-hidden=\"true\"></div>\n";
    }
    $html .= '</div>';
    return $html;
}

function paginate(int $total, int $perPage, int $currentPage): array {
    $totalPages = max(1, (int) ceil($total / $perPage));
    $currentPage = max(1, min($currentPage, $totalPages));
    $offset = ($currentPage - 1) * $perPage;

    return [
        'total' => $total,
        'per_page' => $perPage,
        'current_page' => $currentPage,
        'total_pages' => $totalPages,
        'offset' => $offset,
    ];
}
