<?php
$id = isset($_GET['id']) ? (int) $_GET['id'] : 0;
$form = $id ? form_get($id) : null;
$pageTitle = $form ? 'Upravit formulář' : 'Nový formulář';
require_once __DIR__ . '/includes/header.php';

if (!$form && $id) { flash_set('error', 'Formulář nenalezen.'); redirect(ADMIN_URL . '/forms.php'); }

$errors = [];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!csrf_verify()) { $errors[] = 'Neplatný token.'; }

    $name           = trim($_POST['name'] ?? '');
    $slugInput      = trim($_POST['slug'] ?? '');
    $emailTo        = trim($_POST['email_to'] ?? '');
    $emailSubject   = trim($_POST['email_subject'] ?? '');
    $successMessage = trim($_POST['success_message'] ?? '');

    // Build fields from POST
    $fieldLabels = $_POST['field_label'] ?? [];
    $fieldTypes  = $_POST['field_type'] ?? [];
    $fieldReqs   = $_POST['field_required'] ?? [];
    $fields = [];
    foreach ($fieldLabels as $i => $label) {
        $label = trim($label);
        if (!$label) continue;
        $fields[] = [
            'label'    => $label,
            'type'     => $fieldTypes[$i] ?? 'text',
            'required' => in_array((string)$i, array_keys($fieldReqs)),
            'name'     => slug($label),
        ];
    }

    if (!$name) $errors[] = 'Název je povinný.';
    if (empty($fields)) $errors[] = 'Přidejte alespoň jedno pole.';

    if (empty($errors)) {
        $newId = form_save([
            'id'              => $id ?: null,
            'name'            => $name,
            'slug'            => $slugInput ?: slug($name),
            'fields'          => $fields,
            'email_to'        => $emailTo,
            'email_subject'   => $emailSubject ?: ('Nová zpráva: ' . $name),
            'success_message' => $successMessage ?: 'Děkujeme za zprávu!',
        ]);
        flash_set('success', 'Formulář byl uložen.');
        redirect(ADMIN_URL . '/form-edit.php?id=' . $newId);
    }
}

$values = [
    'name'            => $_POST['name']            ?? ($form['name']            ?? ''),
    'slug'            => $_POST['slug']            ?? ($form['slug']            ?? ''),
    'email_to'        => $_POST['email_to']        ?? ($form['email_to']        ?? ''),
    'email_subject'   => $_POST['email_subject']   ?? ($form['email_subject']   ?? ''),
    'success_message' => $_POST['success_message'] ?? ($form['success_message'] ?? 'Děkujeme za zprávu!'),
    'fields'          => $form['fields'] ?? [],
];
?>

<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1.5rem;">
    <h1><?= h($pageTitle) ?></h1>
    <div>
        <?php if ($form): ?>
            <a href="<?= ADMIN_URL ?>/form-submissions.php?form_id=<?= $form['id'] ?>" class="btn btn-secondary btn-sm">Odpovědi</a>
        <?php endif; ?>
        <a href="<?= ADMIN_URL ?>/forms.php" class="btn btn-secondary btn-sm">← Zpět</a>
    </div>
</div>

<?php if ($errors): ?>
    <div class="alert alert-error"><?= implode('<br>', array_map('h', $errors)) ?></div>
<?php endif; ?>

<form method="post" id="form-builder">
    <?= csrf_field() ?>
    <div style="display:grid;grid-template-columns:1fr 300px;gap:1.5rem;">
        <div>
            <div class="card">
                <div class="form-group">
                    <label>Název formuláře *</label>
                    <input type="text" name="name" class="form-control" value="<?= h($values['name']) ?>" required>
                </div>
                <div class="form-group">
                    <label>Slug</label>
                    <input type="text" name="slug" class="form-control" value="<?= h($values['slug']) ?>" placeholder="vygeneruje-se-automaticky">
                </div>
            </div>

            <div class="card">
                <div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:1rem;">
                    <h3 style="font-size:1rem;margin:0;">Pole formuláře</h3>
                    <button type="button" onclick="addField()" class="btn btn-secondary btn-sm">+ Přidat pole</button>
                </div>
                <div id="fields-container">
                    <?php foreach ($values['fields'] as $i => $field): ?>
                    <div class="field-row" style="display:grid;grid-template-columns:1fr auto 80px auto;gap:.5rem;align-items:end;margin-bottom:.8rem;padding:.8rem;background:#f8f9fa;border-radius:6px;">
                        <div class="form-group" style="margin:0;">
                            <label style="font-size:.8rem;">Popisek</label>
                            <input type="text" name="field_label[]" class="form-control" value="<?= h($field['label']) ?>" required>
                        </div>
                        <div class="form-group" style="margin:0;">
                            <label style="font-size:.8rem;">Typ</label>
                            <select name="field_type[]" class="form-control">
                                <?php foreach (['text'=>'Text','email'=>'E-mail','tel'=>'Telefon','textarea'=>'Textarea','select'=>'Výběr','checkbox'=>'Checkbox','number'=>'Číslo','date'=>'Datum'] as $val => $lbl): ?>
                                    <option value="<?= $val ?>" <?= ($field['type'] ?? 'text') === $val ? 'selected' : '' ?>><?= $lbl ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="form-group" style="margin:0;text-align:center;">
                            <label style="font-size:.8rem;">Povinné</label>
                            <input type="checkbox" name="field_required[<?= $i ?>]" value="1" <?= !empty($field['required']) ? 'checked' : '' ?> style="display:block;margin:.5rem auto 0;">
                        </div>
                        <div style="padding-top:1.3rem;">
                            <button type="button" onclick="this.closest('.field-row').remove()" class="btn btn-danger btn-sm">×</button>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
                <?php if (empty($values['fields'])): ?>
                <script>document.addEventListener('DOMContentLoaded', addField);</script>
                <?php endif; ?>
            </div>
        </div>

        <div>
            <div class="card">
                <div class="form-group">
                    <label>E-mail příjemce</label>
                    <input type="email" name="email_to" class="form-control" value="<?= h($values['email_to']) ?>" placeholder="volitelné">
                    <div class="form-hint">Kam přijde notifikace o odeslání.</div>
                </div>
                <div class="form-group">
                    <label>Předmět e-mailu</label>
                    <input type="text" name="email_subject" class="form-control" value="<?= h($values['email_subject']) ?>">
                </div>
                <div class="form-group">
                    <label>Zpráva po odeslání</label>
                    <textarea name="success_message" class="form-control" rows="3"><?= h($values['success_message']) ?></textarea>
                </div>
                <div class="form-actions">
                    <button type="submit" class="btn btn-primary btn-block">Uložit formulář</button>
                </div>
            </div>

            <?php if ($form): ?>
            <div class="card">
                <h3 style="font-size:.9rem;margin-bottom:.5rem;">Shortcode</h3>
                <code style="display:block;background:#f0f2f5;padding:.5rem;border-radius:4px;font-size:.85rem;">[form slug="<?= h($form['slug']) ?>"]</code>
                <div class="form-hint" style="margin-top:.3rem;">Vložte do obsahu stránky.</div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</form>

<script>
let fieldIdx = <?= count($values['fields']) ?>;
function addField() {
    const i = fieldIdx++;
    const types = ['text','email','tel','textarea','select','checkbox','number','date'];
    const labels = ['Text','E-mail','Telefon','Textarea','Výběr','Checkbox','Číslo','Datum'];
    const opts = types.map((v,j) => `<option value="${v}">${labels[j]}</option>`).join('');
    const row = `<div class="field-row" style="display:grid;grid-template-columns:1fr auto 80px auto;gap:.5rem;align-items:end;margin-bottom:.8rem;padding:.8rem;background:#f8f9fa;border-radius:6px;">
        <div class="form-group" style="margin:0;"><label style="font-size:.8rem;">Popisek</label>
            <input type="text" name="field_label[]" class="form-control" required placeholder="Např. Jméno"></div>
        <div class="form-group" style="margin:0;"><label style="font-size:.8rem;">Typ</label>
            <select name="field_type[]" class="form-control">${opts}</select></div>
        <div class="form-group" style="margin:0;text-align:center;"><label style="font-size:.8rem;">Povinné</label>
            <input type="checkbox" name="field_required[${i}]" value="1" style="display:block;margin:.5rem auto 0;"></div>
        <div style="padding-top:1.3rem;"><button type="button" onclick="this.closest('.field-row').remove()" class="btn btn-danger btn-sm">×</button></div>
    </div>`;
    document.getElementById('fields-container').insertAdjacentHTML('beforeend', row);
}
</script>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
