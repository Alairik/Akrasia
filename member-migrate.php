<?php
/**
 * Akrasia – member section DB migration
 * Run once: https://akrasia.zvelebil.online/akrasia/member-migrate.php
 * DELETE this file from server after running!
 */

define('ZVELE_CMS', true);
require_once __DIR__ . '/config.php';
require_once CORE_PATH . '/Database.php';

$db  = Database::getInstance();
$pdo = $db->getPdo();
$log = [];
$ok  = true;

// ── 1. ALTER zvele_users ─────────────────────────────────────────
try {
    // Add ENUM value 'member' to role column
    $pdo->exec("ALTER TABLE zvele_users MODIFY COLUMN role ENUM('admin','editor','member') NOT NULL DEFAULT 'editor'");
    $log[] = '✅ zvele_users.role ENUM expanded to include "member"';
} catch (PDOException $e) {
    $log[] = '⚠️  role ENUM: ' . $e->getMessage();
    $ok = false;
}

try {
    // Check if member_level column already exists
    $check = $pdo->query("SHOW COLUMNS FROM zvele_users LIKE 'member_level'")->fetch();
    if ($check) {
        $log[] = '⏭️  zvele_users.member_level already exists, skipped';
    } else {
        $pdo->exec("ALTER TABLE zvele_users ADD COLUMN member_level TINYINT UNSIGNED NOT NULL DEFAULT 0 AFTER role");
        $log[] = '✅ zvele_users.member_level column added';
    }
} catch (PDOException $e) {
    $log[] = '❌ member_level: ' . $e->getMessage();
    $ok = false;
}

// ── 2. CREATE zvele_member_content ────────────────────────────────
try {
    $pdo->exec("CREATE TABLE IF NOT EXISTS zvele_member_content (
        id            INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title         VARCHAR(255) NOT NULL,
        description   TEXT,
        type          ENUM('video','pdf','document') NOT NULL,
        filename      VARCHAR(255) NOT NULL,
        original_name VARCHAR(255) NOT NULL,
        required_level TINYINT UNSIGNED NOT NULL DEFAULT 1,
        category      VARCHAR(100) NOT NULL DEFAULT '',
        sort_order    INT NOT NULL DEFAULT 0,
        status        ENUM('draft','published') NOT NULL DEFAULT 'draft',
        file_size     INT UNSIGNED DEFAULT NULL,
        duration_sec  INT UNSIGNED DEFAULT NULL,
        created_by    INT UNSIGNED DEFAULT NULL,
        created_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP,
        updated_at    TIMESTAMP NOT NULL DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci");
    $log[] = '✅ zvele_member_content table created (or already existed)';
} catch (PDOException $e) {
    $log[] = '❌ zvele_member_content: ' . $e->getMessage();
    $ok = false;
}

// ── Output ────────────────────────────────────────────────────────
header('Content-Type: text/html; charset=utf-8');
echo '<!DOCTYPE html><html lang="cs"><head><meta charset="UTF-8">';
echo '<title>Member Migration</title>';
echo '<style>body{font-family:sans-serif;padding:2rem;max-width:600px;margin:auto}';
echo '.ok{color:#16a34a}.fail{color:#dc2626}.warn{color:#d97706}</style></head><body>';
echo '<h1>Akrasia – Member Migration</h1>';
echo '<ul>';
foreach ($log as $line) {
    $cls = str_starts_with($line, '✅') ? 'ok' : (str_starts_with($line, '❌') ? 'fail' : 'warn');
    echo '<li class="' . $cls . '">' . htmlspecialchars($line) . '</li>';
}
echo '</ul>';
if ($ok) {
    echo '<p class="ok"><strong>✅ Migrace proběhla úspěšně. Smažte tento soubor!</strong></p>';
} else {
    echo '<p class="fail"><strong>❌ Migrace měla chyby – zkontrolujte log výše.</strong></p>';
}
echo '</body></html>';
