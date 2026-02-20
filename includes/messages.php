<?php
/**
 * Messages – zprávy z kontaktních formulářů
 */

function _messages_ensure_table(): void {
    static $done = false;
    if ($done) return;
    db_connect()->exec("
        CREATE TABLE IF NOT EXISTS messages (
            id         INT AUTO_INCREMENT PRIMARY KEY,
            subject    VARCHAR(255) NOT NULL DEFAULT 'Kontaktní formulář',
            name       VARCHAR(255) NOT NULL,
            email      VARCHAR(255) NOT NULL,
            message    TEXT         NOT NULL,
            read_at    DATETIME     NULL,
            created_at DATETIME     NOT NULL DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci
    ");
    $done = true;
}

function message_save(array $data): int {
    _messages_ensure_table();
    $db   = db_connect();
    $stmt = $db->prepare('INSERT INTO messages (subject, name, email, message) VALUES (?, ?, ?, ?)');
    $stmt->execute([
        $data['subject'] ?? 'Kontaktní formulář',
        $data['name'],
        $data['email'],
        $data['message'],
    ]);
    return (int) $db->lastInsertId();
}

function messages_list(int $limit = 200, int $offset = 0): array {
    _messages_ensure_table();
    $stmt = db_connect()->prepare('SELECT * FROM messages ORDER BY created_at DESC LIMIT ? OFFSET ?');
    $stmt->execute([$limit, $offset]);
    return $stmt->fetchAll();
}

function messages_count(): int {
    _messages_ensure_table();
    return (int) db_connect()->query('SELECT COUNT(*) FROM messages')->fetchColumn();
}

function messages_unread_count(): int {
    _messages_ensure_table();
    return (int) db_connect()->query('SELECT COUNT(*) FROM messages WHERE read_at IS NULL')->fetchColumn();
}

function message_get(int $id): ?array {
    _messages_ensure_table();
    $stmt = db_connect()->prepare('SELECT * FROM messages WHERE id = ?');
    $stmt->execute([$id]);
    return $stmt->fetch() ?: null;
}

function message_mark_read(int $id): void {
    $stmt = db_connect()->prepare('UPDATE messages SET read_at = NOW() WHERE id = ? AND read_at IS NULL');
    $stmt->execute([$id]);
}

function message_delete(int $id): void {
    $stmt = db_connect()->prepare('DELETE FROM messages WHERE id = ?');
    $stmt->execute([$id]);
}
