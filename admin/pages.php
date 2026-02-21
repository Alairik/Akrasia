<?php
$pageTitle = 'Stránky';
require_once __DIR__ . '/includes/header.php';

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/pages.php'); }
    page_delete((int) $_POST['delete_id']);
    flash_set('success', 'Stránka byla smazána.');
    redirect(ADMIN_URL . '/pages.php');
}

$pages = pages_list();
?>

<h1>Stránky</h1>

<div class="card">
    <div class="card-header">
        <h2>Všechny stránky</h2>
        <a href="<?= ADMIN_URL ?>/page-edit.php" class="btn btn-primary btn-sm">+ Nová stránka</a>
    </div>

    <?php if (empty($pages)): ?>
        <p class="empty-state">Zatím žádné stránky. <a href="<?= ADMIN_URL ?>/page-edit.php">Vytvořte první!</a></p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Slug</th>
                    <th>Stav</th>
                    <th>Pořadí</th>
                    <th>Upraveno</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($pages as $page): ?>
                <tr>
                    <td><a href="<?= ADMIN_URL ?>/page-edit.php?id=<?= $page['id'] ?>"><?= h($page['title']) ?></a></td>
                    <td><code>/<?= h($page['slug']) ?></code></td>
                    <td><span class="badge badge-<?= $page['status'] === 'published' ? 'success' : 'warning' ?>"><?= $page['status'] === 'published' ? 'Publikována' : 'Koncept' ?></span></td>
                    <td><?= (int) $page['menu_order'] ?></td>
                    <td><?= format_date($page['updated_at']) ?></td>
                    <td>
                        <a href="<?= ADMIN_URL ?>/page-edit.php?id=<?= $page['id'] ?>" class="btn btn-secondary btn-sm">Upravit</a>
                        <form method="post" style="display:inline;" onsubmit="return confirm('Opravdu smazat?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="delete_id" value="<?= $page['id'] ?>">
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
