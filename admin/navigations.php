<?php
$pageTitle = 'Navigace';
require_once __DIR__ . '/includes/header.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/navigations.php'); }
    navigation_delete((int) $_POST['delete_id']);
    flash_set('success', 'Navigace byla smazána.');
    redirect(ADMIN_URL . '/navigations.php');
}

// Quick create
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['new_name'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/navigations.php'); }
    $name = trim($_POST['new_name'] ?? '');
    if ($name) {
        navigation_save(['name' => $name, 'slug' => slug($name)]);
        flash_set('success', 'Navigace vytvořena.');
    }
    redirect(ADMIN_URL . '/navigations.php');
}

$navs = navigations_list();
?>

<h1>Navigace</h1>

<div class="card">
    <div class="card-header">
        <h2>Všechna menu</h2>
    </div>

    <form method="post" style="display:flex;gap:.5rem;margin-bottom:1rem;">
        <?= csrf_field() ?>
        <input type="text" name="new_name" class="form-control" placeholder="Název nového menu (např. Hlavní menu)" required>
        <button type="submit" class="btn btn-primary" style="white-space:nowrap;">+ Vytvořit</button>
    </form>

    <?php if (empty($navs)): ?>
        <p class="empty-state">Žádná navigace.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Název</th>
                    <th>Slug</th>
                    <th>Počet položek</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($navs as $nav): ?>
                <tr>
                    <td><?= h($nav['name']) ?></td>
                    <td><code><?= h($nav['slug']) ?></code></td>
                    <td><?= (int) $nav['items_count'] ?></td>
                    <td>
                        <a href="<?= ADMIN_URL ?>/navigation-edit.php?id=<?= $nav['id'] ?>" class="btn btn-secondary btn-sm">Upravit položky</a>
                        <form method="post" style="display:inline;" onsubmit="return confirm('Smazat menu?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="delete_id" value="<?= $nav['id'] ?>">
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
