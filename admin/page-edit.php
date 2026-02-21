<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$page = $id ? page_get($id) : null;
$pageTitle = $page ? 'Upravit stránku' : 'Nová stránka';
require_once __DIR__ . '/includes/header.php';

if (!$page && $id) {
    flash_set('error', 'Stránka nenalezena.');
    redirect(ADMIN_URL . '/pages.php');
}

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { $errors[] = 'Neplatný token.'; }

    $title       = trim($_POST['title'] ?? '');
    $slugInput   = trim($_POST['slug'] ?? '');
    $content     = $_POST['content'] ?? '';
    $excerpt     = trim($_POST['excerpt'] ?? '');
    $metaTitle   = trim($_POST['meta_title'] ?? '');
    $metaDesc    = trim($_POST['meta_description'] ?? '');
    $status      = $_POST['status'] === 'published' ? 'published' : 'draft';
    $menuOrder   = (int) ($_POST['menu_order'] ?? 0);

    if (!$title) $errors[] = 'Název je povinný.';

    $slugFinal = $slugInput ?: slug($title);

    if (empty($errors)) {
        $newId = page_save([
            'id'               => $id ?: null,
            'title'            => $title,
            'slug'             => $slugFinal,
            'content'          => $content,
            'excerpt'          => $excerpt,
            'meta_title'       => $metaTitle,
            'meta_description' => $metaDesc,
            'status'           => $status,
            'menu_order'       => $menuOrder,
            'author_id'        => $currentUser['id'],
        ]);
        flash_set('success', 'Stránka byla uložena.');
        redirect(ADMIN_URL . '/page-edit.php?id=' . $newId);
    }
}

$values = [
    'title'            => $_POST['title']            ?? ($page['title']            ?? ''),
    'slug'             => $_POST['slug']             ?? ($page['slug']             ?? ''),
    'content'          => $_POST['content']          ?? ($page['content']          ?? ''),
    'excerpt'          => $_POST['excerpt']          ?? ($page['excerpt']          ?? ''),
    'meta_title'       => $_POST['meta_title']       ?? ($page['meta_title']       ?? ''),
    'meta_description' => $_POST['meta_description'] ?? ($page['meta_description'] ?? ''),
    'status'           => $_POST['status']           ?? ($page['status']           ?? 'draft'),
    'menu_order'       => $_POST['menu_order']       ?? ($page['menu_order']       ?? 0),
];
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1><?= h($pageTitle) ?></h1>
    <a href="<?= ADMIN_URL ?>/pages.php" class="btn btn-secondary btn-sm">← Zpět na stránky</a>
</div>

<?php if ($errors): ?>
    <div class="alert alert-error"><?= implode('<br>', array_map('h', $errors)) ?></div>
<?php endif; ?>

<form method="post">
    <?= csrf_field() ?>
    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;">
        <div>
            <div class="card">
                <div class="form-group">
                    <label>Název stránky *</label>
                    <input type="text" name="title" class="form-control" value="<?= h($values['title']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug (URL)</label>
                    <input type="text" name="slug" class="form-control" value="<?= h($values['slug']) ?>" placeholder="vygeneruje-se-automaticky">
                    <div class="form-hint">Ponechejte prázdné pro automatické generování z názvu.</div>
                </div>
                <div class="form-group">
                    <label>Obsah</label>
                    <div class="editor-tabs">
                        <button type="button" class="editor-tab active" data-tab="write">Psát</button>
                        <button type="button" class="editor-tab" data-tab="preview">Náhled</button>
                    </div>
                    <div id="pane-write" class="editor-pane active">
                        <textarea id="content" name="content" class="form-control" style="min-height:350px;border-radius:0 0 4px 4px;"><?= h($values['content']) ?></textarea>
                    </div>
                    <div id="pane-preview" class="editor-pane">
                        <div id="markdown-preview" class="markdown-preview"></div>
                    </div>
                    <div class="form-hint">Lze použít Markdown nebo HTML.</div>
                </div>
                <div class="form-group">
                    <label>Perex / Úryvek</label>
                    <textarea name="excerpt" class="form-control" rows="3"><?= h($values['excerpt']) ?></textarea>
                </div>
            </div>

            <div class="card">
                <h3 style="margin-bottom:1rem;font-size:1rem;">SEO</h3>
                <div class="form-group">
                    <label>Meta titulek</label>
                    <input type="text" name="meta_title" class="form-control" value="<?= h($values['meta_title']) ?>" placeholder="Ponechejte prázdné = název stránky">
                </div>
                <div class="form-group">
                    <label>Meta popis</label>
                    <textarea name="meta_description" class="form-control" rows="3"><?= h($values['meta_description']) ?></textarea>
                </div>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="form-group">
                    <label>Stav</label>
                    <select name="status" class="form-control">
                        <option value="draft"     <?= $values['status'] === 'draft'     ? 'selected' : '' ?>>Koncept</option>
                        <option value="published" <?= $values['status'] === 'published' ? 'selected' : '' ?>>Publikována</option>
                    </select>
                </div>
                <div class="form-group">
                    <label>Pořadí v menu</label>
                    <input type="number" name="menu_order" class="form-control" value="<?= (int) $values['menu_order'] ?>" min="0">
                    <div class="form-hint">Nižší číslo = dříve v seznamu.</div>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-block">Uložit stránku</button>
                </div>
                <?php if ($page && $values['status'] === 'published'): ?>
                    <div style="margin-top:.8rem;text-align:center;">
                        <a href="<?= SITE_URL ?>/<?= h($values['slug']) ?>" target="_blank" class="btn btn-secondary btn-sm">Zobrazit na webu ↗</a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</form>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
