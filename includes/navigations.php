<?php
/**
 * Navigation menus functions
 */

function navigations_list(): array {
    $db = db_connect();
    return $db->query('SELECT n.*, COUNT(ni.id) AS items_count
                        FROM navigations n
                        LEFT JOIN navigation_items ni ON n.id = ni.nav_id
                        GROUP BY n.id
                        ORDER BY n.name ASC')->fetchAll();
}

function navigation_get(int $id): ?array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT * FROM navigations WHERE id = ?');
    $stmt->execute([$id]);
    $nav = $stmt->fetch();
    return $nav ?: null;
}

function navigation_get_by_slug(string $slug): ?array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT * FROM navigations WHERE slug = ?');
    $stmt->execute([$slug]);
    $nav = $stmt->fetch();
    return $nav ?: null;
}

function navigation_save(array $data): int {
    $db = db_connect();

    if (!empty($data['id'])) {
        $stmt = $db->prepare('UPDATE navigations SET name = ?, slug = ? WHERE id = ?');
        $stmt->execute([$data['name'], $data['slug'], $data['id']]);
        return (int) $data['id'];
    } else {
        $stmt = $db->prepare('INSERT INTO navigations (name, slug, created_at) VALUES (?, ?, NOW())');
        $stmt->execute([$data['name'], $data['slug']]);
        return (int) $db->lastInsertId();
    }
}

function navigation_delete(int $id): void {
    $db = db_connect();
    $db->prepare('DELETE FROM navigation_items WHERE nav_id = ?')->execute([$id]);
    $db->prepare('DELETE FROM navigations WHERE id = ?')->execute([$id]);
}

function navigation_items_list(int $navId): array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT * FROM navigation_items WHERE nav_id = ? ORDER BY menu_order ASC, id ASC');
    $stmt->execute([$navId]);
    return $stmt->fetchAll();
}

function navigation_items_save(int $navId, array $items): void {
    $db = db_connect();
    $db->prepare('DELETE FROM navigation_items WHERE nav_id = ?')->execute([$navId]);

    $stmt = $db->prepare('INSERT INTO navigation_items (nav_id, label, url, target, menu_order, parent_id) VALUES (?, ?, ?, ?, ?, ?)');
    foreach ($items as $i => $item) {
        $stmt->execute([
            $navId,
            $item['label'],
            $item['url'],
            $item['target'] ?? '_self',
            $i,
            $item['parent_id'] ?? null,
        ]);
    }
}
