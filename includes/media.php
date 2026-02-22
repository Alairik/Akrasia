<?php
/**
 * Media management functions
 */

function media_list(int $limit = 60, int $offset = 0, ?string $type = null): array {
    $db = db_connect();
    $where = [];
    $params = [];

    if ($type !== null) {
        $where[] = 'mime_type LIKE ?';
        $params[] = $type . '%';
    }

    $whereSQL = $where ? 'WHERE ' . implode(' AND ', $where) : '';
    $params[] = $limit;
    $params[] = $offset;

    $stmt = $db->prepare("SELECT m.*, u.username AS uploader
                           FROM media m
                           LEFT JOIN users u ON m.uploaded_by = u.id
                           {$whereSQL}
                           ORDER BY m.created_at DESC
                           LIMIT ? OFFSET ?");
    $stmt->execute($params);
    return $stmt->fetchAll();
}

function media_count(?string $type = null): int {
    $db = db_connect();
    if ($type !== null) {
        $stmt = $db->prepare('SELECT COUNT(*) FROM media WHERE mime_type LIKE ?');
        $stmt->execute([$type . '%']);
    } else {
        $stmt = $db->query('SELECT COUNT(*) FROM media');
    }
    return (int) $stmt->fetchColumn();
}

function media_get(int $id): ?array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT m.*, u.username AS uploader FROM media m LEFT JOIN users u ON m.uploaded_by = u.id WHERE m.id = ?');
    $stmt->execute([$id]);
    $item = $stmt->fetch();
    return $item ?: null;
}

function media_upload(array $file, int $userId): ?array {
    $allowed = [
        'image/jpeg', 'image/png', 'image/gif', 'image/webp', 'image/svg+xml',
        'application/pdf',
        'video/mp4', 'video/webm',
        'audio/mpeg', 'audio/ogg',
        'text/plain',
        'application/zip',
    ];

    if ($file['error'] !== UPLOAD_ERR_OK) return null;
    if (!in_array($file['type'], $allowed, true)) return null;
    if ($file['size'] > 20 * 1024 * 1024) return null;

    $originalName = basename($file['name']);
    $ext = strtolower(pathinfo($originalName, PATHINFO_EXTENSION));
    $yearMonth = date('Y/m');
    $dir = UPLOADS_PATH . '/' . $yearMonth;

    if (!is_dir($dir)) mkdir($dir, 0755, true);

    $filename = bin2hex(random_bytes(8)) . '.' . $ext;
    $path = $dir . '/' . $filename;

    if (!move_uploaded_file($file['tmp_name'], $path)) return null;

    $relativePath = $yearMonth . '/' . $filename;

    $db = db_connect();
    $stmt = $db->prepare('INSERT INTO media (filename, original_name, mime_type, size, path, uploaded_by, created_at)
                           VALUES (?, ?, ?, ?, ?, ?, NOW())');
    $stmt->execute([$filename, $originalName, $file['type'], $file['size'], $relativePath, $userId]);

    return [
        'id'            => (int) $db->lastInsertId(),
        'filename'      => $filename,
        'original_name' => $originalName,
        'mime_type'     => $file['type'],
        'size'          => $file['size'],
        'path'          => $relativePath,
        'url'           => UPLOADS_URL . '/' . $relativePath,
    ];
}

function media_delete(int $id): bool {
    $db = db_connect();
    $item = media_get($id);
    if (!$item) return false;

    $file = UPLOADS_PATH . '/' . $item['path'];
    if (file_exists($file)) @unlink($file);

    $db->prepare('DELETE FROM media WHERE id = ?')->execute([$id]);
    return true;
}

function media_url(string $path): string {
    return UPLOADS_URL . '/' . $path;
}

function media_is_image(string $mimeType): bool {
    return str_starts_with($mimeType, 'image/');
}

function media_format_size(int $bytes): string {
    if ($bytes >= 1048576) return round($bytes / 1048576, 1) . ' MB';
    if ($bytes >= 1024) return round($bytes / 1024, 1) . ' KB';
    return $bytes . ' B';
}
