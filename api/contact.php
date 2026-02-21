<?php
/**
 * Kontaktní formulář – POST handler
 * Přijímá multipart/form-data, ukládá zprávu do DB, vrací JSON.
 */
header('Content-Type: application/json; charset=utf-8');

require_once dirname(__DIR__) . '/includes/config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/helpers.php';
require_once INCLUDES_PATH . '/messages.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'error' => 'Metoda není povolena.']);
    exit;
}

// Rate limit: max 5 odeslaných zpráv za 10 minut z jedné IP
$ip = hash('sha256', $_SERVER['REMOTE_ADDR'] ?? '0.0.0.0');
if (!rate_limit_check('contact:' . $ip, 5, 600)) {
    http_response_code(429);
    echo json_encode(['success' => false, 'error' => 'Příliš mnoho zpráv. Zkuste to za chvíli.']);
    exit;
}

// Honeypot – boti pole vyplní, lidé ne
if (!empty($_POST['website'])) {
    echo json_encode(['success' => true]);
    exit;
}

$name    = trim($_POST['name']    ?? '');
$email   = trim($_POST['email']   ?? '');
$message = trim($_POST['message'] ?? '');
$subject = trim($_POST['subject'] ?? 'Kontaktní formulář');

$errors = [];
if (mb_strlen($name) < 2)                       $errors[] = 'Zadejte prosím své jméno.';
if (!filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Zadejte platnou e-mailovou adresu.';
if (mb_strlen($message) < 5)                    $errors[] = 'Zpráva je příliš krátká.';

if ($errors) {
    http_response_code(422);
    echo json_encode(['success' => false, 'error' => implode(' ', $errors)]);
    exit;
}

try {
    message_save([
        'subject' => $subject ?: 'Kontaktní formulář',
        'name'    => $name,
        'email'   => $email,
        'message' => $message,
    ]);
    echo json_encode(['success' => true]);
} catch (PDOException $e) {
    error_log('contact api: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'error' => 'Omlouváme se, nastala technická chyba. Zkuste to prosím znovu.']);
}
