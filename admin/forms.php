<?php
$pageTitle = 'Formuláře';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/forms.php'); }
    form_delete((int) $_POST['delete_id']);
    flash_set('success', 'Formulář byl smazán.');
    redirect(ADMIN_URL . '/forms.php');
}

$forms = forms_list();
?>

<h1>Formuláře</h1>

<div class="card">
    <div class="card-header">
        <h2>Všechny formuláře</h2>
        <a href="<?= ADMIN_URL ?>/form-edit.php" class="btn btn-primary btn-sm">+ Nový formulář</a>
    </div>

    <?php if (empty($forms)): ?>
        <p class="empty-state">Žádné formuláře. <a href="<?= ADMIN_URL ?>/form-edit.php">Vytvořte první!</a></p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Slug</th>
                    <th>Odesláno</th>
                    <th>E-mail příjemce</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($forms as $f): ?>
                <tr>
                    <td><a href="<?= ADMIN_URL ?>/form-edit.php?id=<?= $f['id'] ?>"><?= h($f['name']) ?></a></td>
                    <td><code><?= h($f['slug']) ?></code></td>
                    <td>
                        <?php if ($f['submissions_count'] > 0): ?>
                            <a href="<?= ADMIN_URL ?>/form-submissions.php?form_id=<?= $f['id'] ?>"><?= (int)$f['submissions_count'] ?> odpovědí</a>
                        <?php else: ?>
                            <span style="color:#888;">0</span>
                        <?php endif; ?>
                    </td>
                    <td><?= h($f['email_to'] ?: '—') ?></td>
                    <td>
                        <a href="<?= ADMIN_URL ?>/form-edit.php?id=<?= $f['id'] ?>" class="btn btn-secondary btn-sm">Upravit</a>
                        <form method="post" style="display:inline;" onsubmit="return confirm('Smazat formulář včetně odpovědí?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="delete_id" value="<?= $f['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">Smazat</button>
                        </form>
                    </td>
                </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
