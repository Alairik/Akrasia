<?php defined('ZVELE_CMS') or die(); ?>

<section class="member-auth-section">
    <div class="member-auth-card">
        <div class="member-auth-logo">
            <img src="<?= asset('themes/default/assets/akrasia_logo_rect.png') ?>" alt="Akrasia" width="160">
        </div>

        <h1 class="member-auth-title">Registrace</h1>
        <p class="member-auth-subtitle">Vytvořte si bezplatný účet pro přístup k materiálům.</p>

        <?php if (!empty($error)): ?>
        <div class="member-auth-error"><?= e($error) ?></div>
        <?php endif; ?>

        <form method="POST" action="<?= url('registrace') ?>" class="member-auth-form" novalidate>
            <?= csrf_field() ?>

            <div class="member-form-group">
                <label for="name">Jméno a příjmení</label>
                <input type="text" id="name" name="name"
                       value="<?= e(($old ?? [])['name'] ?? '') ?>"
                       autocomplete="name" required>
            </div>

            <div class="member-form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="<?= e(($old ?? [])['email'] ?? '') ?>"
                       autocomplete="email" required>
            </div>

            <div class="member-form-group">
                <label for="password">Heslo <small>(min. 8 znaků)</small></label>
                <input type="password" id="password" name="password"
                       autocomplete="new-password" required minlength="8">
            </div>

            <div class="member-form-group">
                <label for="password_confirm">Heslo znovu</label>
                <input type="password" id="password_confirm" name="password_confirm"
                       autocomplete="new-password" required minlength="8">
            </div>

            <button type="submit" class="member-auth-btn">Registrovat se</button>
        </form>

        <p class="member-auth-footer">
            Už máte účet?
            <a href="<?= url('login') ?>">Přihlaste se</a>
        </p>
    </div>
</section>
