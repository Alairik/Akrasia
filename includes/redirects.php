<?php
/**
 * URL redirect functions
 */

function redirects_list(): array {
    $db = db_connect();
    return $db->query('SELECT * FROM redirects ORDER BY created_at DESC')->fetchAll();
}

function redirect_get(int $id): ?array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT * FROM redirects WHERE id = ?');
    $stmt->execute([$id]);
    $row = $stmt->fetch();
    return $row ?: null;
}

function redirect_save(array $data): int {
    $db = db_connect();

    if (!empty($data['id'])) {
        $stmt = $db->prepare('UPDATE redirects SET from_url = ?, to_url = ?, type = ?, active = ? WHERE id = ?');
        $stmt->execute([$data['from_url'], $data['to_url'], $data['type'], (int) $data['active'], $data['id']]);
        return (int) $data['id'];
    } else {
        $stmt = $db->prepare('INSERT INTO redirects (from_url, to_url, type, active, created_at) VALUES (?, ?, ?, ?, NOW())');
        $stmt->execute([$data['from_url'], $data['to_url'], $data['type'] ?? 301, 1]);
        return (int) $db->lastInsertId();
    }
}

function redirect_delete(int $id): void {
    $db = db_connect();
    $db->prepare('DELETE FROM redirects WHERE id = ?')->execute([$id]);
}

function redirect_toggle(int $id): void {
    $db = db_connect();
    $db->prepare('UPDATE redirects SET active = NOT active WHERE id = ?')->execute([$id]);
}
