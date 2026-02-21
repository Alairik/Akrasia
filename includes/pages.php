<?php
/**
 * Static pages functions
 */

function pages_list(?string $status = null): array {
    $db = db_connect();
    $where = [];
    $params = [];

    if ($status !== null) {
        $where[] = 'p.status = ?';
        $params[] = $status;
    }

    $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';

    $sql = "SELECT p.*, u.username AS author_name
            FROM pages p
            LEFT JOIN users u ON p.author_id = u.id
            {$whereSQL}
            ORDER BY p.menu_order ASC, p.title ASC";

    $stmt = $db->prepare($sql);
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function pages_count(?string $status = null): int {
    $db = db_connect();
    if ($status !== null) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM pages WHERE status = ?');
        $stmt->execute([$status]);
    } else {
        $stmt = $db->query('SELECT COUNT(*) FROM pages');
    }
    return (int) $stmt->fetchColumn();
}

function page_get(int $id): ?array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT p.*, u.username AS author_name
                           FROM pages p
                           LEFT JOIN users u ON p.author_id = u.id
                           WHERE p.id = ?');
    $stmt->execute([$id]);
    $page = $stmt->fetch();
    return $page ?: null;
}

function page_get_by_slug(string $slug): ?array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT * FROM pages WHERE slug = ? AND status = ?');
    $stmt->execute([$slug, 'published']);
    $page = $stmt->fetch();
    return $page ?: null;
}

function page_save(array $data): int {
    $db = db_connect();

    if (!empty($data['id'])) {
        $stmt = $db->prepare('UPDATE pages SET
            title = ?, slug = ?, content = ?, excerpt = ?,
            meta_title = ?, meta_description = ?,
            status = ?, menu_order = ?, updated_at = NOW()
            WHERE id = ?');
        $stmt->execute([
            $data['title'], $data['slug'], $data['content'], $data['excerpt'] ?? '',
            $data['meta_title'] ?? '', $data['meta_description'] ?? '',
            $data['status'], $data['menu_order'] ?? 0, $data['id']
        ]);
        return (int) $data['id'];
    } else {
        $stmt = $db->prepare('INSERT INTO pages
            (title, slug, content, excerpt, meta_title, meta_description, status, menu_order, author_id, created_at, updated_at)
            VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())');
        $stmt->execute([
            $data['title'], $data['slug'], $data['content'], $data['excerpt'] ?? '',
            $data['meta_title'] ?? '', $data['meta_description'] ?? '',
            $data['status'], $data['menu_order'] ?? 0, $data['author_id']
        ]);
        return (int) $db->lastInsertId();
    }
}

function page_delete(int $id): void {
    $db = db_connect();
    $db->prepare('DELETE FROM pages WHERE id = ?')->execute([$id]);
}
