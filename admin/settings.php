<?php
$pageTitle = 'Nastavení';
require_once __DIR__ . '/includes/header.php';

if (!auth_is_admin()) { flash_set('error', 'Přístup odepřen.'); redirect(ADMIN_URL . '/'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/settings.php'); }

    $data = [];
    foreach ($_POST as $k => $v) {
        if ($k === 'csrf_token') continue;
        $data[$k] = is_array($v) ? implode(',', $v) : (string) $v;
    }
    settings_save_multiple($data);
    flash_set('success', 'Nastavení bylo uloženo.');
    redirect(ADMIN_URL . '/settings.php');
}

$settings = settings_get_all();

// Group settings by group
$grouped = [];
foreach ($settings as $key => $s) {
    $grouped[$s['group']][$key] = $s;
}
?>

<h1>Nastavení</h1>

<?php if (empty($settings)): ?>
    <div class="alert alert-warning">
        Nastavení nejsou inicializována. Spusťte <a href="<?= SITE_URL ?>/install.php">install.php</a> pro vytvoření výchozích hodnot.
    </div>
<?php else: ?>

<form method="post">
    <?= csrf_field() ?>
    <?php foreach ($grouped as $group => $items): ?>
    <div class="card">
        <h3 style="margin-bottom:1.2rem;font-size:1rem;color:#555;text-transform:uppercase;letter-spacing:.5px;font-size:.85rem;"><?= h($group) ?></h3>
        <?php foreach ($items as $key => $s): ?>
        <div class="form-group">
            <label><?= h($s['label']) ?></label>
            <?php if ($s['type'] === 'textarea'): ?>
                <textarea name="<?= h($key) ?>" class="form-control" rows="3"><?= h($s['value']) ?></textarea>
            <?php elseif ($s['type'] === 'checkbox'): ?>
                <label style="font-weight:normal;display:flex;align-items:center;gap:.5rem;">
                    <input type="checkbox" name="<?= h($key) ?>" value="1" <?= $s['value'] ? 'checked' : '' ?>>
                    Zapnuto
                </label>
            <?php else: ?>
                <input type="<?= h($s['type']) ?>" name="<?= h($key) ?>" class="form-control" value="<?= h($s['value']) ?>">
            <?php endif; ?>
        </div>
        <?php endforeach; ?>
    </div>
    <?php endforeach; ?>
    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Uložit nastavení</button>
    </div>
</form>

<?php endif; ?>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
