</main><!-- /#main-content -->

<footer class="site-footer" aria-label="Patička webu">
    <div class="container">
        <div class="footer-grid">

            <!-- Sloupec 1: Brand -->
            <div class="footer-col footer-brand">
                <a href="<?= SITE_URL ?>/" aria-label="Akrasia – domovská stránka" style="text-decoration:none">
                    <span class="site-logo-text">Akrasia</span>
                </a>
                <p><?= h(SITE_TAGLINE) ?><br><br>Nezisková organizace propojující lidi s ADHD s ověřenými odborníky a komunitou.</p>
                <?php $hasSocial = SOCIAL_FACEBOOK || SOCIAL_INSTAGRAM || SOCIAL_LINKEDIN || SOCIAL_YOUTUBE; ?>
                <?php if ($hasSocial): ?>
                <div class="footer-social">
                    <?php if (SOCIAL_FACEBOOK): ?>
                    <a href="<?= h(SOCIAL_FACEBOOK) ?>" target="_blank" rel="noopener" aria-label="Facebook">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M18 2h-3a5 5 0 0 0-5 5v3H7v4h3v8h4v-8h3l1-4h-4V7a1 1 0 0 1 1-1h3z"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if (SOCIAL_INSTAGRAM): ?>
                    <a href="<?= h(SOCIAL_INSTAGRAM) ?>" target="_blank" rel="noopener" aria-label="Instagram">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" aria-hidden="true"><rect x="2" y="2" width="20" height="20" rx="5" ry="5"/><path d="M16 11.37A4 4 0 1 1 12.63 8 4 4 0 0 1 16 11.37z"/><line x1="17.5" y1="6.5" x2="17.51" y2="6.5"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if (SOCIAL_LINKEDIN): ?>
                    <a href="<?= h(SOCIAL_LINKEDIN) ?>" target="_blank" rel="noopener" aria-label="LinkedIn">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M16 8a6 6 0 0 1 6 6v7h-4v-7a2 2 0 0 0-2-2 2 2 0 0 0-2 2v7h-4v-7a6 6 0 0 1 6-6z"/><rect x="2" y="9" width="4" height="12"/><circle cx="4" cy="4" r="2"/></svg>
                    </a>
                    <?php endif; ?>
                    <?php if (SOCIAL_YOUTUBE): ?>
                    <a href="<?= h(SOCIAL_YOUTUBE) ?>" target="_blank" rel="noopener" aria-label="YouTube">
                        <svg width="18" height="18" viewBox="0 0 24 24" fill="currentColor" aria-hidden="true"><path d="M22.54 6.42a2.78 2.78 0 0 0-1.94-1.96C18.88 4 12 4 12 4s-6.88 0-8.6.46a2.78 2.78 0 0 0-1.94 1.96A29 29 0 0 0 1 12a29 29 0 0 0 .46 5.58a2.78 2.78 0 0 0 1.94 1.96C5.12 20 12 20 12 20s6.88 0 8.6-.46a2.78 2.78 0 0 0 1.94-1.96A29 29 0 0 0 23 12a29 29 0 0 0-.46-5.58z"/><polygon fill="#4e5699" points="9.75 15.02 15.5 12 9.75 8.98 9.75 15.02"/></svg>
                    </a>
                    <?php endif; ?>
                </div>
                <?php endif; ?>
            </div>

            <!-- Sloupec 2: Navigace -->
            <div class="footer-col">
                <h4>Navigace</h4>
                <ul>
                    <li><a href="<?= SITE_URL ?>/kdo-jsme">Kdo jsme</a></li>
                    <li><a href="<?= SITE_URL ?>/hledam-podporu">Hledám podporu</a></li>
                    <li><a href="<?= SITE_URL ?>/terapeuti">Adresář terapeutů</a></li>
                    <li><a href="<?= SITE_URL ?>/pro-firmy">Pro firmy</a></li>
                    <li><a href="<?= SITE_URL ?>/pro-skoly">Pro školy</a></li>
                    <li><a href="<?= SITE_URL ?>/zapojte-se">Zapojte se</a></li>
                    <li><a href="<?= SITE_URL ?>/blog">Blog</a></li>
                    <li><a href="<?= SITE_URL ?>/darujte">Darujte</a></li>
                </ul>
            </div>

            <!-- Sloupec 3: Právní + info -->
            <div class="footer-col">
                <h4>Informace</h4>
                <ul>
                    <li><a href="<?= SITE_URL ?>/gdpr">Zásady ochrany osobních údajů</a></li>
                    <li><a href="<?= SITE_URL ?>/vase-pribehy">Vaše příběhy</a></li>
                    <li><a href="<?= SITE_URL ?>/spolupracujeme">Partneři</a></li>
                    <li><a href="<?= SITE_URL ?>/stante-se-clenem">Staňte se členem</a></li>
                    <li><a href="<?= SITE_URL ?>/dobrovolnictvi">Dobrovolnictví</a></li>
                    <li><a href="<?= SITE_URL ?>/staz">Stáž</a></li>
                </ul>
            </div>

        </div>

        <div class="footer-bottom">
            <span>&copy; <?= date('Y') ?> Akrasia, z.s. Všechna práva vyhrazena.</span>
            <a href="<?= SITE_URL ?>/gdpr">Ochrana osobních údajů</a>
        </div>
    </div>
</footer>

<script src="<?= SITE_URL ?>/assets/js/main.js" defer></script>
<?php if (!empty($extraScript)): ?>
<script src="<?= SITE_URL ?>/assets/js/<?= h($extraScript) ?>" defer></script>
<?php endif; ?>

</body>
</html>
