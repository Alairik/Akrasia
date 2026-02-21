<?php
$pageTitle = 'Média';
require_once __DIR__ . '/includes/header.php';

// Handle upload
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_FILES['file'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/media.php'); }

    $uploaded = media_upload($_FILES['file'], $currentUser['id']);
    if ($uploaded) {
        flash_set('success', 'Soubor „' . h($uploaded['original_name']) . '" byl nahrán.');
    } else {
        flash_set('error', 'Nahrání selhalo. Povolené typy: obrázky, PDF, video, audio, ZIP. Max 20 MB.');
    }
    redirect(ADMIN_URL . '/media.php');
}

// Handle delete
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete_id'])) {
    if (!csrf_verify()) { flash_set('error', 'Neplatný token.'); redirect(ADMIN_URL . '/media.php'); }
    media_delete((int) $_POST['delete_id']);
    flash_set('success', 'Soubor byl smazán.');
    redirect(ADMIN_URL . '/media.php');
}

$perPage = 24;
$currentPageNum = max(1, (int) ($_GET['p'] ?? 1));
$typeFilter = $_GET['type'] ?? null;
$total = media_count($typeFilter);
$pag = paginate($total, $perPage, $currentPageNum);
$items = media_list($perPage, $pag['offset'], $typeFilter);
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1>Média</h1>
    <button onclick="document.getElementById('upload-card').scrollIntoView()" class="btn btn-primary btn-sm">+ Nahrát soubor</button>
</div>

<div class="card" id="upload-card">
    <h3 style="margin-bottom:1rem;font-size:1rem;">Nahrát nový soubor</h3>
    <form method="post" enctype="multipart/form-data">
        <?= csrf_field() ?>
        <div style="display:flex;gap:1rem;align-items:flex-end;">
            <div class="form-group" style="flex:1;margin:0;">
                <label>Soubor (max 20 MB)</label>
                <input type="file" name="file" class="form-control" required accept="image/*,.pdf,.mp4,.webm,.mp3,.ogg,.zip,.txt">
            </div>
            <button type="submit" class="btn btn-primary">Nahrát</button>
        </div>
        <div class="form-hint" style="margin-top:.5rem;">Povoleno: obrázky (jpg, png, gif, webp, svg), PDF, video (mp4, webm), audio (mp3, ogg), ZIP, TXT</div>
    </form>
</div>

<div class="card">
    <div class="card-header">
        <h2>Soubory (<?= $total ?>)</h2>
        <div>
            <a href="<?= ADMIN_URL ?>/media.php" class="btn btn-sm <?= !$typeFilter ? 'btn-primary' : 'btn-secondary' ?>">Vše</a>
            <a href="<?= ADMIN_URL ?>/media.php?type=image" class="btn btn-sm <?= $typeFilter === 'image' ? 'btn-primary' : 'btn-secondary' ?>">Obrázky</a>
            <a href="<?= ADMIN_URL ?>/media.php?type=application" class="btn btn-sm <?= $typeFilter === 'application' ? 'btn-primary' : 'btn-secondary' ?>">Dokumenty</a>
        </div>
    </div>

    <?php if (empty($items)): ?>
        <p class="empty-state">Žádné soubory.</p>
    <?php else: ?>
        <div style="display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:1rem;margin-bottom:1rem;">
            <?php foreach ($items as $item): ?>
            <div style="border:1px solid #eee;border-radius:6px;overflow:hidden;background:#fafafa;">
                <?php if (media_is_image($item['mime_type'])): ?>
                    <img src="<?= h(media_url($item['path'])) ?>" alt="<?= h($item['original_name']) ?>"
                         style="width:100%;height:120px;object-fit:cover;display:block;" loading="lazy">
                <?php else: ?>
                    <div style="width:100%;height:120px;display:flex;align-items:center;justify-content:center;background:#e9ecef;font-size:2rem;">
                        <?= str_starts_with($item['mime_type'], 'video/') ? '🎬' : (str_starts_with($item['mime_type'], 'audio/') ? '🎵' : '📄') ?>
                    </div>
                <?php endif; ?>
                <div style="padding:.5rem;">
                    <div style="font-size:.8rem;font-weight:600;overflow:hidden;text-overflow:ellipsis;white-space:nowrap;" title="<?= h($item['original_name']) ?>">
                        <?= h($item['original_name']) ?>
                    </div>
                    <div style="font-size:.75rem;color:#888;margin:.2rem 0;"><?= media_format_size((int)$item['size']) ?></div>
                    <div style="display:flex;gap:.3rem;">
                        <a href="<?= h(media_url($item['path'])) ?>" target="_blank" class="btn btn-secondary btn-sm" style="flex:1;text-align:center;">↗</a>
                        <button onclick="copyUrl(this,'<?= h(media_url($item['path'])) ?>')" class="btn btn-secondary btn-sm" title="Kopírovat URL">📋</button>
                        <form method="post" onsubmit="return confirm('Smazat soubor?')" style="display:inline;">
                            <?= csrf_field() ?>
                            <input type="hidden" name="delete_id" value="<?= $item['id'] ?>">
                            <button type="submit" class="btn btn-danger btn-sm">×</button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if ($pag['total_pages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $pag['total_pages']; $i++): ?>
                <?php $q = $typeFilter ? '&type=' . urlencode($typeFilter) : ''; ?>
                <a href="?p=<?= $i ?><?= $q ?>" class="<?= $i === $pag['current_page'] ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<script>
function copyUrl(btn, url) {
    navigator.clipboard.writeText(url).then(() => {
        const orig = btn.textContent;
        btn.textContent = '✓';
        setTimeout(() => btn.textContent = orig, 1500);
    });
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
