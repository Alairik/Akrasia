<?php defined('ZVELE_CMS') or die(); ?>

<?php
$db          = Database::getInstance();
$footerMenu  = $db->fetchOne("SELECT items FROM zvele_menus WHERE location = 'footer'");
$footerItems = $footerMenu ? (json_decode($footerMenu['items'], true) ?? []) : [];
$navMenu     = $db->fetchOne("SELECT items FROM zvele_menus WHERE location = 'main'");
$navItems    = $navMenu ? (json_decode($navMenu['items'], true) ?? []) : [];
$siteName    = setting('site_name', 'Akrasia');
$logoId      = setting('site_logo_id');
$fb  = setting('social_facebook', '');
$ig  = setting('social_instagram', '');
$li  = setting('social_linkedin', '');
$yt  = setting('social_youtube', '');
?>

<footer class="site-footer" aria-label="Patička webu">
    <div class="container">
        <div class="footer-grid">

            <div class="footer-col footer-brand">
                <a href="<?= url('/') ?>" aria-label="<?= e($siteName) ?>" style="text-decoration:none">
                    <?php if ($logoId): ?>
                        <img src="<?= e(Template::mediaUrl((int)$logoId)) ?>" alt="<?= e($siteName) ?>" class="footer-logo" loading="lazy">
                    <?php else: ?>
                        <img src="<?= asset('assets/brand/akrasia_logo_rect_FULL_Svetle.png') ?>"
                             alt="<?= e($siteName) ?> – prostor, který ADHD rozumí"
                             class="footer-logo" loading="lazy">
                    <?php endif; ?>
                </a>
                <p><?= e(setting('site_description', 'Nezisková organizace propojující lidi s ADHD s ověřenými odborníky a komunitou.')) ?></p>
                <?php if ($fb || $ig || $li || $yt): ?>
                <div class="footer-social">
                    <?php if ($fb): ?>
                    <a href="<?= e($fb) ?>" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($ig): ?>
                    <a href="<?= e($ig) ?>" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($li): ?>
                    <a href="<?= e($li) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if ($yt): ?>
                    <a href="<?= e($yt) ?>" target="_blank" rel="noopener" aria-label="YouTube">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-1.96C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58a2.78 2.78 0 0 0 1.94 1.96C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon fill="#fff" points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <div class="footer-col">
                <h4>Navigace</h4>
                <ul>
                    <?php foreach ($navItems as $item): ?>
                    <li><a href="<?= e($item['url'] ?? '#') ?>"><?= e($item['label'] ?? '') ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="footer-col">
                <h4>Informace</h4>
                <ul>
                    <?php foreach ($footerItems as $item): ?>
                    <li><a href="<?= e($item['url'] ?? '#') ?>"><?= e($item['label'] ?? '') ?></a></li>
                    <?php endforeach; ?>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> <?= e($siteName) ?>, z.s. Všechna práva vyhrazena.</span>
            <a href="<?= url('/gdpr') ?>">Ochrana osobních údajů</a>
            <button class="footer-cookie-btn" onclick="CookieConsent.reset()" aria-label="Změnit nastavení cookies" type="button">
                Nastavení cookies
            </button>
        </div>
    </div>
</footer>
