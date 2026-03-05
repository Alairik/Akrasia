<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

/**
 * MemberContent – manages protected content items (videos, PDFs).
 * Files are stored in PROTECTED_PATH outside web root (blocked by .htaccess).
 */
class MemberContent
{
    /** Fetch single content item by ID, published only. */
    public static function getById(int $id): ?array
    {
        $db = Database::getInstance();
        return $db->fetchOne(
            "SELECT * FROM zvele_member_content WHERE id = ? AND status = 'published'",
            [$id]
        );
    }

    /** Fetch all published items accessible to a given access level, optionally filtered by type. */
    public static function listForLevel(int $level, string $type = ''): array
    {
        $db  = Database::getInstance();
        $sql = "SELECT * FROM zvele_member_content
                WHERE status = 'published' AND required_level <= ?";
        $params = [$level];

        if ($type !== '') {
            $sql .= ' AND type = ?';
            $params[] = $type;
        }

        $sql .= ' ORDER BY sort_order ASC, created_at DESC';
        return $db->fetchAll($sql, $params);
    }

    /** Can the current member access this item? */
    public static function canAccess(int $contentId): bool
    {
        if (!MemberAuth::check()) {
            return false;
        }
        $item = self::getById($contentId);
        if (!$item) {
            return false;
        }
        return MemberAuth::level() >= (int) $item['required_level'];
    }

    /**
     * Stream an MP4 video with Range support.
     * No Content-Disposition → browser cannot "Save as".
     * Exits after sending.
     */
    public static function streamVideo(string $id): void
    {
        MemberAuth::requireLogin();

        $db   = Database::getInstance();
        $item = $db->fetchOne(
            "SELECT * FROM zvele_member_content WHERE id = ? AND type = 'video' AND status = 'published'",
            [(int) $id]
        );

        if (!$item) {
            http_response_code(404);
            exit;
        }

        if (MemberAuth::level() < (int) $item['required_level']) {
            http_response_code(403);
            exit;
        }

        $file = PROTECTED_PATH . '/videos/' . basename($item['filename']);
        if (!file_exists($file)) {
            http_response_code(404);
            exit;
        }

        $size  = filesize($file);
        $start = 0;
        $end   = $size - 1;

        header('Content-Type: video/mp4');
        header('Accept-Ranges: bytes');
        header('Cache-Control: no-store, private');
        header('X-Content-Type-Options: nosniff');
        // Intentionally no Content-Disposition to prevent "Save as"

        if (isset($_SERVER['HTTP_RANGE'])) {
            if (preg_match('/bytes=(\d+)-(\d*)/', $_SERVER['HTTP_RANGE'], $m)) {
                $start = (int) $m[1];
                $end   = $m[2] !== '' ? min((int) $m[2], $size - 1) : $size - 1;
            }
            http_response_code(206);
            header("Content-Range: bytes $start-$end/$size");
        }

        $length = $end - $start + 1;
        header("Content-Length: $length");

        $fp = fopen($file, 'rb');
        fseek($fp, $start);
        $remaining = $length;
        while (!feof($fp) && $remaining > 0) {
            $chunk = (int) min(1048576, $remaining);
            echo fread($fp, $chunk);
            $remaining -= $chunk;
            flush();
        }
        fclose($fp);
        exit;
    }

    /**
     * Serve a PDF/document for download.
     * Uses Content-Disposition: attachment so the browser saves it.
     * Exits after sending.
     */
    public static function downloadFile(string $id): void
    {
        MemberAuth::requireLogin();

        $db   = Database::getInstance();
        $item = $db->fetchOne(
            "SELECT * FROM zvele_member_content WHERE id = ? AND type IN ('pdf','document') AND status = 'published'",
            [(int) $id]
        );

        if (!$item) {
            http_response_code(404);
            exit;
        }

        if (MemberAuth::level() < (int) $item['required_level']) {
            http_response_code(403);
            exit;
        }

        $file = PROTECTED_PATH . '/documents/' . basename($item['filename']);
        if (!file_exists($file)) {
            http_response_code(404);
            exit;
        }

        $mime = mime_content_type($file) ?: 'application/octet-stream';
        $name = $item['original_name'];

        header('Content-Type: ' . $mime);
        header('Content-Disposition: attachment; filename="' . addslashes($name) . '"');
        header('Content-Length: ' . filesize($file));
        header('Cache-Control: no-store, private');
        readfile($file);
        exit;
    }

    /**
     * Upload a protected file.
     * $type: 'video' | 'pdf' | 'document'
     * Returns hashed filename (stored in PROTECTED_PATH).
     */
    public static function upload(array $file, string $type): string
    {
        $allowedMime = [
            'video'    => ['video/mp4'],
            'pdf'      => ['application/pdf'],
            'document' => ['application/pdf', 'application/msword',
                           'application/vnd.openxmlformats-officedocument.wordprocessingml.document'],
        ];

        if (!isset($allowedMime[$type])) {
            throw new InvalidArgumentException('Neznámý typ souboru.');
        }

        $mime = mime_content_type($file['tmp_name']);
        if (!in_array($mime, $allowedMime[$type], true)) {
            throw new RuntimeException('Nepodporovaný formát souboru.');
        }

        $ext      = $type === 'video' ? 'mp4' : pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = bin2hex(random_bytes(16)) . '.' . strtolower($ext);
        $subdir   = $type === 'video' ? 'videos' : 'documents';
        $dest     = PROTECTED_PATH . '/' . $subdir . '/' . $filename;

        if (!move_uploaded_file($file['tmp_name'], $dest)) {
            throw new RuntimeException('Uložení souboru se nezdařilo.');
        }

        return $filename;
    }
}
