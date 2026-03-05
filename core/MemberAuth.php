<?php
defined('ZVELE_CMS') or die('Direct access forbidden.');

/**
 * MemberAuth – authentication for front-end member area.
 * Uses separate session keys from admin Auth to avoid conflicts.
 * Session keys: member_id, member_name, member_level
 */
class MemberAuth
{
    private const SID   = 'member_id';
    private const SNAME = 'member_name';
    private const SLVL  = 'member_level';

    /**
     * Attempt login. Returns true on success.
     * Rate-limited to 10 attempts/hour per IP.
     */
    public static function attempt(string $email, string $password): bool
    {
        $key = 'member_login:' . client_ip();
        if (!Security::checkRateLimit($key, 10, 3600)) {
            return false;
        }
        Security::recordRateLimit($key);

        $db   = Database::getInstance();
        $user = $db->fetchOne(
            "SELECT * FROM zvele_users WHERE email = ? AND role = 'member'",
            [trim($email)]
        );

        if (!$user || !password_verify($password, $user['password_hash'])) {
            return false;
        }

        session_regenerate_id(true);
        $_SESSION[self::SID]   = (int) $user['id'];
        $_SESSION[self::SNAME] = $user['name'];
        $_SESSION[self::SLVL]  = (int) ($user['member_level'] ?? 1);

        $db->update('zvele_users', ['last_login' => date('Y-m-d H:i:s')], 'id = ?', [(int) $user['id']]);
        return true;
    }

    /** Is a member logged in? */
    public static function check(): bool
    {
        return !empty($_SESSION[self::SID]);
    }

    /** Current member ID or null. */
    public static function id(): ?int
    {
        return isset($_SESSION[self::SID]) ? (int) $_SESSION[self::SID] : null;
    }

    /** Current member display name. */
    public static function name(): string
    {
        return $_SESSION[self::SNAME] ?? '';
    }

    /** Current member access level (0 = none). */
    public static function level(): int
    {
        return (int) ($_SESSION[self::SLVL] ?? 0);
    }

    /** Redirect to /login if not authenticated. */
    public static function requireLogin(): void
    {
        if (!self::check()) {
            redirect(url('login'));
        }
    }

    /** Require minimum access level; redirect to dashboard with notice if denied. */
    public static function requireLevel(int $min): void
    {
        self::requireLogin();
        if (self::level() < $min) {
            flash('error', 'Nemáte dostatečná oprávnění pro přístup k tomuto obsahu.');
            redirect(url('clen'));
        }
    }

    /** Destroy member session (does not affect admin session). */
    public static function logout(): void
    {
        unset($_SESSION[self::SID], $_SESSION[self::SNAME], $_SESSION[self::SLVL]);
    }

    /**
     * Register a new member. Returns new user ID.
     * Throws RuntimeException on duplicate email.
     */
    public static function register(string $name, string $email, string $password): int
    {
        $db = Database::getInstance();

        $existing = $db->fetchOne("SELECT id FROM zvele_users WHERE email = ?", [trim($email)]);
        if ($existing) {
            throw new RuntimeException('E-mail je již zaregistrován.');
        }

        return $db->insert('zvele_users', [
            'name'          => Security::sanitize($name),
            'email'         => Security::sanitizeEmail($email),
            'password_hash' => password_hash($password, PASSWORD_BCRYPT),
            'role'          => 'member',
            'member_level'  => 1,
        ]);
    }
}
