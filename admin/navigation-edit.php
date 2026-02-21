<?php
$id = (int) ($_GET['id'] ?? 0);
$nav = $id ? navigation_get($id) : null;
$pageTitle = $nav ? 'Editace: ' . $nav['name'] : 'Navigace';
require_once __DIR__ . '/includes/header.php';

if (!$nav) { flash_set('error', 'Navigace nenalezena.'); redirect(ADMIN_URL . '/navigations.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/navigation-edit.php?id=' . $id); }

    // Save nav name
    $newName = trim($_POST['nav_name'] ?? $nav['name']);
    navigation_save(['id' => $id, 'name' => $newName, 'slug' => $nav['slug']]);

    // Save items
    $labels  = $_POST['item_label']  ?? [];
    $urls    = $_POST['item_url']    ?? [];
    $targets = $_POST['item_target'] ?? [];
    $items = [];
    foreach ($labels as $i => $label) {
        $label = trim($label);
        $url   = trim($urls[$i] ?? '');
        if (!$label || !$url) continue;
        $items[] = [
            'label'  => $label,
            'url'    => $url,
            'target' => $targets[$i] ?? '_self',
        ];
    }
    navigation_items_save($id, $items);
    flash_set('success', 'Navigace byla uložena.');
    redirect(ADMIN_URL . '/navigation-edit.php?id=' . $id);
}

$items = navigation_items_list($id);
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1><?= h($nav['name']) ?></h1>
    <a href="<?= ADMIN_URL ?>/navigations.php" class="btn btn-secondary btn-sm">← Zpět na navigace</a>
</div>

<form method="post">
    <?= csrf_field() ?>
    <div class="card">
        <div class="form-group">
            <label>Název menu</label>
            <input type="text" name="nav_name" class="form-control" value="<?= h($nav['name']) ?>" style="max-width:300px;">
        </div>
    </div>

    <div class="card">
        <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
            <h3 style="font-size:1rem;margin:0;">Položky menu</h3>
            <button type="button" onclick="addItem()" class="btn btn-secondary btn-sm">+ Přidat položku</button>
        </div>

        <div id="items-container">
            <?php foreach ($items as $i => $item): ?>
            <div class="nav-item-row" style="display:grid;grid-template-columns:1fr 1fr auto auto;gap:.5rem;align-items:end;margin-bottom:.5rem;padding:.7rem;background:#f8f9fa;border-radius:6px;">
                <div class="form-group" style="margin:0;">
                    <?php if ($i === 0): ?><label style="font-size:.8rem;">Popisek</label><?php endif; ?>
                    <input type="text" name="item_label[]" class="form-control" value="<?= h($item['label']) ?>" placeholder="Popisek" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <?php if ($i === 0): ?><label style="font-size:.8rem;">URL</label><?php endif; ?>
                    <input type="text" name="item_url[]" class="form-control" value="<?= h($item['url']) ?>" placeholder="/stranka nebo https://..." required>
                </div>
                <div class="form-group" style="margin:0;">
                    <?php if ($i === 0): ?><label style="font-size:.8rem;">Target</label><?php endif; ?>
                    <select name="item_target[]" class="form-control">
                        <option value="_self"  <?= $item['target'] === '_self'  ? 'selected' : '' ?>>_self</option>
                        <option value="_blank" <?= $item['target'] === '_blank' ? 'selected' : '' ?>>_blank</option>
                    </select>
                </div>
                <div style="<?= $i === 0 ? 'padding-top:1.3rem;' : '' ?>">
                    <button type="button" onclick="this.closest('.nav-item-row').remove()" class="btn btn-danger btn-sm">×</button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>

    <div class="form-actions">
        <button type="submit" class="btn btn-primary">Uložit navigaci</button>
    </div>
</form>

<script>
let isFirst = <?= count($items) === 0 ? 'true' : 'false' ?>;
function addItem() {
    const labels = isFirst ? '<label style="font-size:.8rem;">Popisek</label>' : '';
    const labelsUrl = isFirst ? '<label style="font-size:.8rem;">URL</label>' : '';
    const labelsTgt = isFirst ? '<label style="font-size:.8rem;">Target</label>' : '';
    const ptop = isFirst ? 'padding-top:1.3rem;' : '';
    isFirst = false;
    const row = `<div class="nav-item-row" style="display:grid;grid-template-columns:1fr 1fr auto auto;gap:.5rem;align-items:end;margin-bottom:.5rem;padding:.7rem;background:#f8f9fa;border-radius:6px;">
        <div class="form-group" style="margin:0;">${labels}<input type="text" name="item_label[]" class="form-control" placeholder="Popisek" required></div>
        <div class="form-group" style="margin:0;">${labelsUrl}<input type="text" name="item_url[]" class="form-control" placeholder="/stranka nebo https://..." required></div>
        <div class="form-group" style="margin:0;">${labelsTgt}<select name="item_target[]" class="form-control"><option value="_self">_self</option><option value="_blank">_blank</option></select></div>
        <div style="${ptop}"><button type="button" onclick="this.closest('.nav-item-row').remove()" class="btn btn-danger btn-sm">×</button></div>
    </div>`;
    document.getElementById('items-container').insertAdjacentHTML('beforeend', row);
}
<?php if (empty($items)): ?>addItem();<?php endif; ?>
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
