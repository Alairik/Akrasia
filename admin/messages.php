<?php
$pageTitle = 'Zprávy';
require_once __DIR__ . '/includes/header.php';
auth_require();

// Handle POST actions
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    $id     = (int) ($_POST['id'] ?? 0);

    if ($action === 'delete' && $id) {
        message_delete($id);
        flash_set('success', 'Zpráva byla smazána.');
        redirect(ADMIN_URL . '/messages.php');
    }
    if ($action === 'read' && $id) {
        message_mark_read($id);
        redirect(ADMIN_URL . '/messages.php#msg-' . $id);
    }
    if ($action === 'read_all') {
        db_connect()->exec('UPDATE messages SET read_at = NOW() WHERE read_at IS NULL');
        flash_set('success', 'Všechny zprávy označeny jako přečtené.');
        redirect(ADMIN_URL . '/messages.php');
    }
}

$messages = messages_list(200);
$unread   = messages_unread_count();
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1>
        Zprávy
        <?php if ($unread): ?>
            <span class="badge badge-info" style="font-size:.8rem;vertical-align:middle;"><?= $unread ?> <?= $unread === 1 ? 'nová' : 'nových' ?></span>
        <?php endif; ?>
    </h1>
    <?php if ($unread): ?>
    <form method="post">
        <input type="hidden" name="action" value="read_all">
        <button type="submit" class="btn btn-secondary btn-sm">Označit vše jako přečtené</button>
    </form>
    <?php endif; ?>
</div>

<div class="card">
<?php if (empty($messages)): ?>
    <p class="empty-state">Žádné zprávy. Kontaktní formuláře jsou napojeny a čekají na první zprávu!</p>
<?php else: ?>
    <table class="table">
        <thead>
            <tr>
                <th>Datum</th>
                <th>Předmět</th>
                <th>Jméno</th>
                <th>E-mail</th>
                <th>Zpráva</th>
                <th>Stav</th>
                <th></th>
            </tr>
        </thead>
        <tbody>
        <?php foreach ($messages as $msg): ?>
            <tr id="msg-<?= $msg['id'] ?>" style="<?= $msg['read_at'] ? '' : 'background:#fffdf0;' ?>">
                <td style="white-space:nowrap;color:#666;font-size:.85rem;"><?= format_date($msg['created_at']) ?></td>
                <td style="<?= $msg['read_at'] ? '' : 'font-weight:600;' ?>"><?= h($msg['subject']) ?></td>
                <td><?= h($msg['name']) ?></td>
                <td><a href="mailto:<?= h($msg['email']) ?>"><?= h($msg['email']) ?></a></td>
                <td style="max-width:300px;">
                    <details>
                        <summary style="cursor:pointer;color:#4361ee;font-size:.9rem;">Zobrazit zprávu</summary>
                        <p style="margin-top:.5rem;white-space:pre-wrap;font-size:.9rem;color:#333;line-height:1.5;"><?= h($msg['message']) ?></p>
                    </details>
                </td>
                <td>
                    <?php if ($msg['read_at']): ?>
                        <span class="badge badge-success">Přečteno</span>
                    <?php else: ?>
                        <span class="badge badge-warning">Nová</span>
                    <?php endif; ?>
                </td>
                <td style="white-space:nowrap;">
                    <?php if (!$msg['read_at']): ?>
                    <form method="post" style="display:inline;">
                        <input type="hidden" name="action" value="read">
                        <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-success">✓</button>
                    </form>
                    <?php endif; ?>
                    <form method="post" style="display:inline;" onsubmit="return confirm('Smazat tuto zprávu?');">
                        <input type="hidden" name="action" value="delete">
                        <input type="hidden" name="id" value="<?= $msg['id'] ?>">
                        <button type="submit" class="btn btn-sm btn-danger">Smazat</button>
                    </form>
                </td>
            </tr>
        <?php endforeach; ?>
        </tbody>
    </table>
<?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
