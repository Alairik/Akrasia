<?php
/**
 * Znovupoužitelný kontaktní formulář
 *
 * Parametry (nastavit před require):
 *   $cf_id           – unikátní prefix ID vstupů, aby nekolidovaly na stránce (výchozí: 'contact')
 *   $cf_subject      – hodnota skrytého pole subject (výchozí: 'Kontaktní formulář')
 *   $cf_btn          – popisek tlačítka odeslat (výchozí: 'Odeslat')
 *   $cf_msg_label    – label zprávy / textarea (výchozí: 'Zpráva')
 *   $cf_msg_placeholder – placeholder textarey (výchozí: '')
 */
$_cf_id      = $cf_id ?? 'contact';
$_cf_subject = htmlspecialchars($cf_subject ?? 'Kontaktní formulář');
$_cf_btn     = htmlspecialchars($cf_btn ?? 'Odeslat');
$_cf_label   = htmlspecialchars($cf_msg_label ?? 'Zpráva');
$_cf_ph      = htmlspecialchars($cf_msg_placeholder ?? '');

// Reset parametrů aby nedošlo k úniku do dalšího include
unset($cf_id, $cf_subject, $cf_btn, $cf_msg_label, $cf_msg_placeholder, $cf_center);
?>
<form id="cf-<?= $_cf_id ?>-form" class="contact-form" style="margin-top:var(--space-6);">
    <input type="hidden" name="subject" value="<?= $_cf_subject ?>">
    <!-- honeypot – nevyplňujte toto pole -->
    <input type="text" name="website" style="display:none;" tabindex="-1" autocomplete="off">
    <div class="form-group">
        <label for="cf-<?= $_cf_id ?>-name">Jméno a příjmení</label>
        <input type="text" id="cf-<?= $_cf_id ?>-name" name="name"
               placeholder="Jana Nováková" autocomplete="name" required>
    </div>
    <div class="form-group">
        <label for="cf-<?= $_cf_id ?>-email">E-mail</label>
        <input type="email" id="cf-<?= $_cf_id ?>-email" name="email"
               placeholder="jana@example.cz" autocomplete="email" required>
    </div>
    <div class="form-group">
        <label for="cf-<?= $_cf_id ?>-message"><?= $_cf_label ?></label>
        <textarea id="cf-<?= $_cf_id ?>-message" name="message"
                  placeholder="<?= $_cf_ph ?>" required></textarea>
    </div>
    <p class="cf-error" id="cf-<?= $_cf_id ?>-error" style="display:none;color:#c0392b;margin-bottom:var(--space-3);font-size:var(--text-sm);"></p>
    <button type="submit" class="btn btn-primary"><?= $_cf_btn ?></button>
    <p style="font-size:var(--text-sm);color:var(--text-muted);margin-top:var(--space-3);">
        Odesláním formuláře souhlasíte se zpracováním osobních údajů dle našich
        <a href="<?= SITE_URL ?>/gdpr">zásad ochrany osobních údajů</a>.
    </p>
</form>
<div id="cf-<?= $_cf_id ?>-success" style="display:none;background:var(--mint,#d4f1ee);color:var(--navy,#1a1a2e);padding:var(--space-5,1.25rem) var(--space-6,1.5rem);border-radius:var(--radius,8px);margin-top:var(--space-6,1.5rem);">
    <strong>Zpráva odeslána!</strong> Ozveme se vám co nejdříve na zadaný e-mail.
</div>
<script>
(function () {
    var form    = document.getElementById('cf-<?= $_cf_id ?>-form');
    var success = document.getElementById('cf-<?= $_cf_id ?>-success');
    var errEl   = document.getElementById('cf-<?= $_cf_id ?>-error');
    var btn     = form.querySelector('[type="submit"]');
    var btnText = btn.textContent;

    form.addEventListener('submit', function (e) {
        e.preventDefault();
        errEl.style.display = 'none';
        btn.disabled = true;
        btn.textContent = 'Odesílám\u2026';

        fetch('<?= SITE_URL ?>/api/contact.php', {
            method: 'POST',
            body: new FormData(form)
        })
        .then(function (r) { return r.json(); })
        .then(function (json) {
            if (json.success) {
                form.style.display = 'none';
                success.style.display = 'block';
            } else {
                errEl.textContent = json.error || 'Nastala chyba. Zkuste to prosím znovu.';
                errEl.style.display = 'block';
                btn.disabled = false;
                btn.textContent = btnText;
            }
        })
        .catch(function () {
            errEl.textContent = 'Nastala síťová chyba. Zkuste to prosím znovu.';
            errEl.style.display = 'block';
            btn.disabled = false;
            btn.textContent = btnText;
        });
    });
}());
</script>
