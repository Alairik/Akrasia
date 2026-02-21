<?php
$pageTitle = 'Přesměrování';
require_once __DIR__ . '/includes/header.php';

if (!auth_is_admin()) { flash_set('error', 'Přístup odepřen.'); redirect(ADMIN_URL . '/'); }

// Save / update
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['from_url'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/redirects.php'); }

    $fromUrl = trim($_POST['from_url'] ?? '');
    $toUrl   = trim($_POST['to_url']   ?? '');
    $type    = (int) ($_POST['type']   ?? 301);
    $editId  = (int) ($_POST['edit_id'] ?? 0);

    if (!$fromUrl || !$toUrl) {
        flash_set('error', 'Zdrojová i cílová URL jsou povinné.');
    } else {
        redirect_save([
            'id'       => $editId ?: null,
            'from_url' => $fromUrl,
            'to_url'   => $toUrl,
            'type'     => in_array($type, [301, 302]) ? $type : 301,
            'active'   => 1,
        ]);
        flash_set('success', $editId ? 'Přesměrování bylo upraveno.' : 'Přesměrování bylo přidáno.');
    }
    redirect(ADMIN_URL . '/redirects.php');
}

// Delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/redirects.php'); }
    redirect_delete((int) $_POST['delete_id']);
    flash_set('success', 'Přesměrování bylo smazáno.');
    redirect(ADMIN_URL . '/redirects.php');
}

// Toggle active
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['toggle_id'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/redirects.php'); }
    redirect_toggle((int) $_POST['toggle_id']);
    redirect(ADMIN_URL . '/redirects.php');
}

$redirects = redirects_list();
$editItem = null;
if (isset($_GET['edit'])) {
    $editItem = redirect_get((int) $_GET['edit']);
}
?>

<h1>Přesměrování</h1>

<div class="card">
    <h3 style="font-size:1rem;margin-bottom:1rem;"><?= $editItem ? 'Upravit přesměrování' : 'Nové přesměrování' ?></h3>
    <form method="post">
        <?= csrf_field() ?>
        <?php if ($editItem): ?>
            <input type="hidden" name="edit_id" value="<?= $editItem['id'] ?>">
        <?php endif; ?>
        <div class="form-row" style="grid-template-columns:1fr 1fr auto auto;">
            <div class="form-group" style="margin:0;">
                <label>Ze (from)</label>
                <input type="text" name="from_url" class="form-control" value="<?= h($editItem['from_url'] ?? '') ?>" placeholder="/stara-stranka" required>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Na (to)</label>
                <input type="text" name="to_url" class="form-control" value="<?= h($editItem['to_url'] ?? '') ?>" placeholder="/nova-stranka nebo https://..." required>
            </div>
            <div class="form-group" style="margin:0;">
                <label>Typ</label>
                <select name="type" class="form-control">
                    <option value="301" <?= ($editItem['type'] ?? 301) == 301 ? 'selected' : '' ?>>301 Trvalé</option>
                    <option value="302" <?= ($editItem['type'] ?? 301) == 302 ? 'selected' : '' ?>>302 Dočasné</option>
                </select>
            </div>
            <div style="padding-top:1.5rem;">
                <button type="submit" class="btn btn-primary"><?= $editItem ? 'Uložit' : 'Přidat' ?></button>
                <?php if ($editItem): ?>
                    <a href="<?= ADMIN_URL ?>/redirects.php" class="btn btn-secondary" style="margin-left:.3rem;">Zrušit</a>
                <?php endif; ?>
            </div>
        </div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Všechna přesměrování (<?= count($redirects) ?>)</h2>
    </div>

    <?php if (empty($redirects)): ?>
        <p class="empty-state">Žádná přesměrování.</p>
    <?php else: ?>
        <table class="table">
            <thead>
                <tr>
                    <th>Ze</th>
                    <th>Na</th>
                    <th>Typ</th>
                    <th>Stav</th>
                    <th>Vytvořeno</th>
                    <th>Akce</th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($redirects as $r): ?>
                <tr <?= !$r['active'] ? 'style="opacity:.5;"' : '' ?>>
                    <td><code><?= h($r['from_url']) ?></code></td>
                    <td><code><?= h($r['to_url']) ?></code></td>
                    <td><span class="badge badge-info"><?= (int)$r['type'] ?></span></td>
                    <td>
                        <form method="post" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="toggle_id" value="<?= $r['id'] ?>">
                            <button type="submit" class="btn btn-sm <?= $r['active'] ? 'btn-success' : 'btn-secondary' ?>">
                                <?= $r['active'] ? 'Aktivní' : 'Vypnuto' ?>
                            </button>
                        </form>
                    </td>
                    <td><?= format_date($r['created_at']) ?></td>
                    <td>
                        <a href="?edit=<?= $r['id'] ?>" class="btn btn-secondary btn-sm">Upravit</a>
                        <form method="post" style="display:inline;" onsubmit="return confirm('Smazat?')">
                            <?= csrf_field() ?>
                            <input type="hidden" name="delete_id" value="<?= $r['id'] ?>">
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
