<?php
/**
 * Site settings functions
 */

function settings_get(string $key, string $default = ''): string {
    $db = db_connect();
    $stmt = $db->prepare('SELECT value FROM settings WHERE `key` = ?');
    $stmt->execute([$key]);
    $val = $stmt->fetchColumn();
    return $val !== false ? $val : $default;
}

function settings_get_all(): array {
    $db = db_connect();
    $stmt = $db->query('SELECT `key`, value, label, type, `group` FROM settings ORDER BY `group`, id');
    $rows = $stmt->fetchAll();
    $result = [];
    foreach ($rows as $row) {
        $result[$row['key']] = $row;
    }
    return $result;
}

function settings_set(string $key, string $value): void {
    $db = db_connect();
    $stmt = $db->prepare('UPDATE settings SET value = ? WHERE `key` = ?');
    $stmt->execute([$value, $key]);
}

function settings_save_multiple(array $data): void {
    $db = db_connect();
    $stmt = $db->prepare('UPDATE settings SET value = ? WHERE `key` = ?');
    foreach ($data as $key => $value) {
        $stmt->execute([(string) $value, $key]);
    }
}
