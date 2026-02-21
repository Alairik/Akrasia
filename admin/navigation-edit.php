<?php
$id = (int) ($_GET['id'] ?? 0);
$nav = $id ? navigation_get($id) : null;
$pageTitle = $nav ? 'Editace: ' . $nav['name'] : 'Navigace';
require_once __DIR__ . '/includes/header.php';

if (!$nav) { flash_set('error', 'Navigace nenalezena.'); redirect(ADMIN_URL . '/navigations.php'); }

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/navigation-edit.php?id=' . $id); }

    $newName = trim($_POST['nav_name'] ?? $nav['name']);
    navigation_save(['id' => $id, 'name' => $newName, 'slug' => $nav['slug']]);

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

// Load CMS content for link picker
$cmsPages    = pages_list('published');
$cmsArticles = articles_list(200, 0, 'published');
$cmsLinks = [];
foreach ($cmsPages as $p) {
    $cmsLinks[] = ['url' => '/' . ltrim($p['slug'], '/'), 'label' => $p['title'], 'group' => 'Stránky'];
}
foreach ($cmsArticles as $a) {
    $cmsLinks[] = ['url' => '/clanek/' . $a['slug'], 'label' => $a['title'], 'group' => 'Články'];
}
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
            <div class="nav-item-row" style="display:grid;grid-template-columns:1fr 1fr auto auto auto;gap:.5rem;align-items:end;margin-bottom:.5rem;padding:.7rem;background:#f8f9fa;border-radius:6px;">
                <div class="form-group" style="margin:0;">
                    <?php if ($i === 0): ?><label style="font-size:.8rem;color:#888;">Popisek</label><?php endif; ?>
                    <input type="text" name="item_label[]" class="form-control nav-label" value="<?= h($item['label']) ?>" placeholder="Popisek" required>
                </div>
                <div class="form-group" style="margin:0;">
                    <?php if ($i === 0): ?><label style="font-size:.8rem;color:#888;">URL</label><?php endif; ?>
                    <input type="text" name="item_url[]" class="form-control nav-url" value="<?= h($item['url']) ?>" placeholder="/stranka nebo https://..." required>
                </div>
                <div class="form-group" style="margin:0;">
                    <?php if ($i === 0): ?><label style="font-size:.8rem;color:#888;">Z CMS</label><?php endif; ?>
                    <select class="form-control cms-picker" onchange="applyCmsPick(this)" style="font-size:.8rem;min-width:120px;">
                        <option value="">— CMS —</option>
                        <?php
                        $lastGroup = '';
                        foreach ($cmsLinks as $cl):
                            if ($cl['group'] !== $lastGroup):
                                if ($lastGroup) echo '</optgroup>';
                                echo '<optgroup label="' . h($cl['group']) . '">';
                                $lastGroup = $cl['group'];
                            endif;
                        ?>
                        <option value="<?= h($cl['url']) ?>" data-label="<?= h($cl['label']) ?>"><?= h($cl['label']) ?></option>
                        <?php endforeach; if ($lastGroup) echo '</optgroup>'; ?>
                    </select>
                </div>
                <div class="form-group" style="margin:0;">
                    <?php if ($i === 0): ?><label style="font-size:.8rem;color:#888;">Target</label><?php endif; ?>
                    <select name="item_target[]" class="form-control" style="font-size:.8rem;">
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
const CMS_LINKS = <?= json_encode($cmsLinks, JSON_UNESCAPED_UNICODE) ?>;

function applyCmsPick(select) {
    if (!select.value) return;
    const row = select.closest('.nav-item-row');
    const urlInput   = row.querySelector('.nav-url');
    const labelInput = row.querySelector('.nav-label');
    const opt = select.options[select.selectedIndex];
    urlInput.value = select.value;
    if (!labelInput.value) labelInput.value = opt.dataset.label;
    select.value = '';
}

function buildCmsSelect() {
    let html = '<option value="">— CMS —</option>';
    let lastGroup = '';
    CMS_LINKS.forEach(function(cl) {
        if (cl.group !== lastGroup) {
            if (lastGroup) html += '</optgroup>';
            html += '<optgroup label="' + cl.group + '">';
            lastGroup = cl.group;
        }
        const safe = cl.label.replace(/</g,'&lt;').replace(/>/g,'&gt;').replace(/"/g,'&quot;');
        html += '<option value="' + cl.url + '" data-label="' + safe + '">' + safe + '</option>';
    });
    if (lastGroup) html += '</optgroup>';
    return html;
}

let isFirst = <?= count($items) === 0 ? 'true' : 'false' ?>;
function addItem() {
    const lPop  = isFirst ? '<label style="font-size:.8rem;color:#888;">Popisek</label>' : '';
    const lUrl  = isFirst ? '<label style="font-size:.8rem;color:#888;">URL</label>' : '';
    const lCms  = isFirst ? '<label style="font-size:.8rem;color:#888;">Z CMS</label>' : '';
    const lTgt  = isFirst ? '<label style="font-size:.8rem;color:#888;">Target</label>' : '';
    const ptop  = isFirst ? 'padding-top:1.3rem;' : '';
    isFirst = false;
    const row = `<div class="nav-item-row" style="display:grid;grid-template-columns:1fr 1fr auto auto auto;gap:.5rem;align-items:end;margin-bottom:.5rem;padding:.7rem;background:#f8f9fa;border-radius:6px;">
        <div class="form-group" style="margin:0;">${lPop}<input type="text" name="item_label[]" class="form-control nav-label" placeholder="Popisek" required></div>
        <div class="form-group" style="margin:0;">${lUrl}<input type="text" name="item_url[]" class="form-control nav-url" placeholder="/stranka nebo https://..." required></div>
        <div class="form-group" style="margin:0;">${lCms}<select class="form-control cms-picker" onchange="applyCmsPick(this)" style="font-size:.8rem;min-width:120px;">${buildCmsSelect()}</select></div>
        <div class="form-group" style="margin:0;">${lTgt}<select name="item_target[]" class="form-control" style="font-size:.8rem;"><option value="_self">_self</option><option value="_blank">_blank</option></select></div>
        <div style="${ptop}"><button type="button" onclick="this.closest('.nav-item-row').remove()" class="btn btn-danger btn-sm">×</button></div>
    </div>`;
    document.getElementById('items-container').insertAdjacentHTML('beforeend', row);
}
<?php if (empty($items)): ?>addItem();<?php endif; ?>
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
