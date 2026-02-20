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
 *   $cf_center       – (bool) zda centrovat formulář (margin: auto), výchozí: false
 */
$_cf_id      = $cf_id ?? 'contact';
$_cf_subject = htmlspecialchars($cf_subject ?? 'Kontaktní formulář');
$_cf_btn     = htmlspecialchars($cf_btn ?? 'Odeslat');
$_cf_label   = htmlspecialchars($cf_msg_label ?? 'Zpráva');
$_cf_ph      = htmlspecialchars($cf_msg_placeholder ?? '');
$_cf_margin  = ($cf_center ?? false) ? 'margin:var(--space-6) auto 0;' : 'margin-top:var(--space-6);';

// Reset parametrů aby nedošlo k úniku do dalšího include
unset($cf_id, $cf_subject, $cf_btn, $cf_msg_label, $cf_msg_placeholder, $cf_center);
?>
<form class="contact-form" style="max-width:500px;<?= $_cf_margin ?>" onsubmit="return false;">
    <input type="hidden" name="subject" value="<?= $_cf_subject ?>">
    <div class="form-group">
        <label for="cf-<?= $_cf_id ?>-name">Jméno a příjmení</label>
        <input type="text" id="cf-<?= $_cf_id ?>-name" name="name"
               placeholder="Jana Nováková" autocomplete="name">
    </div>
    <div class="form-group">
        <label for="cf-<?= $_cf_id ?>-email">E-mail</label>
        <input type="email" id="cf-<?= $_cf_id ?>-email" name="email"
               placeholder="jana@example.cz" autocomplete="email">
    </div>
    <div class="form-group">
        <label for="cf-<?= $_cf_id ?>-message"><?= $_cf_label ?></label>
        <textarea id="cf-<?= $_cf_id ?>-message" name="message"
                  placeholder="<?= $_cf_ph ?>"></textarea>
    </div>
    <button type="submit" class="btn btn-primary"><?= $_cf_btn ?></button>
    <p style="font-size:var(--text-sm);color:var(--text-muted);margin-top:var(--space-3);">
        Odesláním formuláře souhlasíte se zpracováním osobních údajů dle našich
        <a href="<?= SITE_URL ?>/gdpr">zásad ochrany osobních údajů</a>.
    </p>
</form>
