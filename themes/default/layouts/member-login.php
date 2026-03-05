<?php defined('ZVELE_CMS') or die(); ?>

<section class="member-auth-section">
    <div class="member-auth-card">
        <div class="member-auth-logo">
            <img src="<?= asset('themes/default/assets/akrasia_logo_rect.png') ?>" alt="Akrasia" width="160">
        </div>

        <h1 class="member-auth-title">Přihlášení pro členy</h1>

        <?php if ($error ?? null): ?>
        <div class="member-auth-error">
            Nesprávný e-mail nebo heslo. Zkuste to prosím znovu.
        </div>
        <?php endif; ?>

        <form method="POST" action="<?= url('login') ?>" class="member-auth-form" novalidate>
            <?= csrf_field() ?>

            <div class="member-form-group">
                <label for="email">E-mail</label>
                <input type="email" id="email" name="email"
                       value="<?= e($_POST['email'] ?? '') ?>"
                       autocomplete="email" required>
            </div>

            <div class="member-form-group">
                <label for="password">Heslo</label>
                <input type="password" id="password" name="password"
                       autocomplete="current-password" required>
            </div>

            <button type="submit" class="member-auth-btn">Přihlásit se</button>
        </form>

        <p class="member-auth-footer">
            Ještě nemáte účet?
            <a href="<?= url('registrace') ?>">Registrujte se</a>
        </p>
    </div>
</section>
