<?php
$formId = (int) ($_GET['form_id'] ?? 0);
if (!$formId) { flash_set('error', 'Formulář nenalezen.'); redirect(ADMIN_URL . '/forms.php'); }

$form = form_get($formId);
$pageTitle = 'Odpovědi: ' . ($form['name'] ?? '');
require_once __DIR__ . '/includes/header.php';

if (!$form) { flash_set('error', 'Formulář nenalezen.'); redirect(ADMIN_URL . '/forms.php'); }

$perPage = 30;
$currentPageNum = max(1, (int) ($_GET['p'] ?? 1));
$total = form_submissions_count($formId);
$pag = paginate($total, $perPage, $currentPageNum);
$submissions = form_submissions_list($formId, $perPage, $pag['offset']);

$fieldDefs = $form['fields'];
$fieldLabels = array_column($fieldDefs, 'label', 'name');
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1>Odpovědi: <?= h($form['name']) ?></h1>
    <a href="<?= ADMIN_URL ?>/forms.php" class="btn btn-secondary btn-sm">← Zpět na formuláře</a>
</div>

<div class="card">
    <div class="card-header">
        <h2>Celkem odpovědí: <?= $total ?></h2>
    </div>

    <?php if (empty($submissions)): ?>
        <p class="empty-state">Zatím žádné odpovědi.</p>
    <?php else: ?>
        <?php foreach ($submissions as $sub): ?>
        <div style="border:1px solid #eee;border-radius:6px;padding:1rem;margin-bottom:.8rem;">
            <div style="display:flex;justify-content:space-between;margin-bottom:.5rem;">
                <span style="font-size:.8rem;color:#888;">#<?= $sub['id'] ?> · <?= format_date($sub['created_at']) ?> · IP: <?= h($sub['ip'] ?? '—') ?></span>
            </div>
            <dl style="display:grid;grid-template-columns:auto 1fr;gap:.2rem .8rem;">
                <?php foreach ($sub['data'] as $key => $val): ?>
                    <dt style="font-weight:600;font-size:.9rem;color:#555;"><?= h($fieldLabels[$key] ?? $key) ?>:</dt>
                    <dd style="font-size:.9rem;word-break:break-word;"><?= nl2br(h((string)$val)) ?></dd>
                <?php endforeach; ?>
            </dl>
        </div>
        <?php endforeach; ?>

        <?php if ($pag['total_pages'] > 1): ?>
        <div class="pagination">
            <?php for ($i = 1; $i <= $pag['total_pages']; $i++): ?>
                <a href="?form_id=<?= $formId ?>&p=<?= $i ?>" class="<?= $i === $pag['current_page'] ? 'active' : '' ?>"><?= $i ?></a>
            <?php endfor; ?>
        </div>
        <?php endif; ?>
    <?php endif; ?>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
