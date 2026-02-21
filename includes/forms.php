<?php
/**
 * Forms functions
 */

function forms_list(): array {
    $db = db_connect();
    return $db->query('SELECT f.*, COUNT(fs.id) AS submissions_count
                        FROM forms f
                        LEFT JOIN form_submissions fs ON f.id = fs.form_id
                        GROUP BY f.id
                        ORDER BY f.created_at DESC')->fetchAll();
}

function form_get(int $id): ?array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT * FROM forms WHERE id = ?');
    $stmt->execute([$id]);
    $form = $stmt->fetch();
    if (!$form) return null;
    $form['fields'] = json_decode($form['fields'] ?? '[]', true) ?: [];
    return $form;
}

function form_save(array $data): int {
    $db = db_connect();
    $fieldsJson = json_encode($data['fields'] ?? []);

    if (!empty($data['id'])) {
        $stmt = $db->prepare('UPDATE forms SET name = ?, slug = ?, fields = ?, email_to = ?, email_subject = ?, success_message = ? WHERE id = ?');
        $stmt->execute([
            $data['name'], $data['slug'], $fieldsJson,
            $data['email_to'] ?? '', $data['email_subject'] ?? '',
            $data['success_message'] ?? 'Děkujeme za zprávu!',
            $data['id']
        ]);
        return (int) $data['id'];
    } else {
        $stmt = $db->prepare('INSERT INTO forms (name, slug, fields, email_to, email_subject, success_message, created_at)
                               VALUES (?, ?, ?, ?, ?, ?, NOW())');
        $stmt->execute([
            $data['name'], $data['slug'], $fieldsJson,
            $data['email_to'] ?? '', $data['email_subject'] ?? '',
            $data['success_message'] ?? 'Děkujeme za zprávu!'
        ]);
        return (int) $db->lastInsertId();
    }
}

function form_delete(int $id): void {
    $db = db_connect();
    $db->prepare('DELETE FROM form_submissions WHERE form_id = ?')->execute([$id]);
    $db->prepare('DELETE FROM forms WHERE id = ?')->execute([$id]);
}

function form_submissions_list(int $formId, int $limit = 50, int $offset = 0): array {
    $db = db_connect();
    $stmt = $db->prepare('SELECT * FROM form_submissions WHERE form_id = ? ORDER BY created_at DESC LIMIT ? OFFSET ?');
    $stmt->execute([$formId, $limit, $offset]);
    $rows = $stmt->fetchAll();
    foreach ($rows as &$row) {
        $row['data'] = json_decode($row['data'] ?? '{}', true) ?: [];
    }
    return $rows;
}

function form_submissions_count(int $formId): int {
    $db = db_connect();
    $stmt = $db->prepare('SELECT COUNT(*) FROM form_submissions WHERE form_id = ?');
    $stmt->execute([$formId]);
    return (int) $stmt->fetchColumn();
}

function form_submit(int $formId, array $data, string $ip): int {
    $db = db_connect();
    $stmt = $db->prepare('INSERT INTO form_submissions (form_id, data, ip, created_at) VALUES (?, ?, ?, NOW())');
    $stmt->execute([$formId, json_encode($data), $ip]);
    return (int) $db->lastInsertId();
}
