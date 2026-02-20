<?php
/**
 * Cookie Banner – GDPR
 * Zahrnout těsně před </body> (výstupy footer.php).
 * Zobrazení řídí cookie-consent.js přes hidden atribut.
 */
?>

<!-- ============================================================
     GDPR Cookie Banner
     ============================================================ -->
<div id="cookie-banner" class="cookie-banner" hidden
     role="dialog" aria-modal="true" aria-label="Nastavení cookies"
     aria-live="polite">
    <div class="cookie-banner__inner">

        <div class="cookie-banner__text">
            <strong>Tento web používá cookies</strong>
            <p>
                Používáme analytické (Google Analytics, Microsoft Clarity)
                a marketingové (Meta Pixel, TikTok) cookies a nástroje
                e-mailového marketingu.
                Nezbytné cookies jsou vždy aktivní.
                <a href="<?= SITE_URL ?>/gdpr#cookies">Více informací</a>
            </p>
        </div>

        <div class="cookie-banner__actions">
            <button id="cc-accept-all"  class="btn btn--cookie-primary">Přijmout vše</button>
            <button id="cc-reject-all"  class="btn btn--cookie-outline">Odmítnout vše</button>
            <button id="cc-settings"    class="btn btn--cookie-ghost">Nastavit</button>
        </div>

    </div>
</div>

<!-- ============================================================
     GDPR Cookie Panel (granulární nastavení)
     ============================================================ -->
<div id="cookie-panel" class="cookie-panel" hidden
     role="dialog" aria-modal="true" aria-label="Podrobné nastavení cookies">

    <div id="cookie-panel-overlay" class="cookie-panel__overlay"></div>

    <div class="cookie-panel__box" role="document">

        <div class="cookie-panel__header">
            <h2 class="cookie-panel__title">Nastavení cookies</h2>
            <button id="cc-panel-close" class="cookie-panel__close" aria-label="Zavřít panel">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none"
                     stroke="currentColor" stroke-width="2.5" aria-hidden="true">
                    <line x1="18" y1="6" x2="6" y2="18"/>
                    <line x1="6" y1="6" x2="18" y2="18"/>
                </svg>
            </button>
        </div>

        <div class="cookie-panel__body">

            <p class="cookie-panel__intro">
                Můžete si vybrat, které kategorie cookies povolíte.
                Svůj výběr můžete kdykoli změnit prostřednictvím odkazu v patičce stránky.
            </p>

            <!-- Nezbytné (locked) -->
            <div class="cookie-cat">
                <div class="cookie-cat__head">
                    <span class="cookie-cat__name">Nezbytné</span>
                    <span class="cookie-badge cookie-badge--locked" aria-label="Vždy aktivní">Vždy aktivní</span>
                </div>
                <p class="cookie-cat__desc">
                    Zajišťují základní fungování webu – správa relace (session),
                    bezpečnost, uchování nastavení cookies. Bez těchto cookies
                    web nemůže správně fungovat a nelze je vypnout.
                </p>
                <ul class="cookie-providers">
                    <li><strong>cc_consent</strong> – uložení vašeho výběru cookies (1 rok)</li>
                    <li><strong>PHPSESSID</strong> – session prohlížeče (do zavření)</li>
                </ul>
            </div>

            <!-- Analytické -->
            <div class="cookie-cat">
                <div class="cookie-cat__head">
                    <span class="cookie-cat__name">Analytické</span>
                    <label class="cookie-switch" aria-label="Povolit analytické cookies">
                        <input type="checkbox" id="cc-toggle-analytics">
                        <span class="cookie-switch__track" aria-hidden="true"></span>
                    </label>
                </div>
                <p class="cookie-cat__desc">
                    Pomáhají nám pochopit, jak návštěvníci web používají –
                    odkud přicházejí, které stránky navštěvují a jak se pohybují.
                    Data jsou anonymizovaná nebo pseudonymizovaná.
                </p>
                <ul class="cookie-providers">
                    <li>
                        <strong>Google Analytics 4 (Google LLC)</strong> –
                        měření návštěvnosti, zdrojů a chování uživatelů.
                        Server: USA (Standard Contractual Clauses).
                        <a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Zásady ochrany</a>
                    </li>
                    <li>
                        <strong>Microsoft Clarity (Microsoft Corp.)</strong> –
                        heatmapy a anonymizované záznamy relací pro UX analýzu.
                        Server: USA (Standard Contractual Clauses).
                        <a href="https://privacy.microsoft.com/cs-cz/privacystatement" target="_blank" rel="noopener">Zásady ochrany</a>
                    </li>
                </ul>
            </div>

            <!-- Marketingové -->
            <div class="cookie-cat">
                <div class="cookie-cat__head">
                    <span class="cookie-cat__name">Marketingové</span>
                    <label class="cookie-switch" aria-label="Povolit marketingové cookies">
                        <input type="checkbox" id="cc-toggle-marketing">
                        <span class="cookie-switch__track" aria-hidden="true"></span>
                    </label>
                </div>
                <p class="cookie-cat__desc">
                    Umožňují měřit výkon marketingových kampaní,
                    cílit reklamu na relevantní publikum a optimalizovat
                    zasílání e-mailů. Data mohou být sdílena s třetími stranami
                    (reklamní sítě Meta, TikTok, e-mailové platformy).
                </p>
                <ul class="cookie-providers">
                    <li>
                        <strong>Meta Pixel – Facebook/Instagram (Meta Platforms, Inc.)</strong> –
                        měření konverzí, remarketing a tvorba podobných publik.
                        Server: USA (Standard Contractual Clauses).
                        <a href="https://www.facebook.com/privacy/policy/" target="_blank" rel="noopener">Zásady ochrany</a>
                    </li>
                    <li>
                        <strong>TikTok Pixel (TikTok Technology Limited)</strong> –
                        měření výkonu kampaní na TikToku, behaviorální reklama.
                        Server: USA/Singapur (Standard Contractual Clauses).
                        <a href="https://www.tiktok.com/legal/page/row/privacy-policy/en" target="_blank" rel="noopener">Zásady ochrany</a>
                    </li>
                    <li>
                        <strong>E-mailový marketing (Mailchimp / Brevo)</strong> –
                        sledování otevření e-mailů a kliknutí v newsletterech
                        pro optimalizaci komunikace.
                        <a href="<?= SITE_URL ?>/gdpr#emailing">Více informací</a>
                    </li>
                </ul>
            </div>

        </div><!-- /.cookie-panel__body -->

        <div class="cookie-panel__footer">
            <button id="cc-save" class="btn btn--cookie-primary">Uložit nastavení</button>
            <button id="cc-accept-all-panel" class="btn btn--cookie-outline"
                    onclick="document.getElementById('cc-toggle-analytics').checked=true;
                             document.getElementById('cc-toggle-marketing').checked=true;
                             document.getElementById('cc-save').click();">
                Přijmout vše
            </button>
        </div>

    </div><!-- /.cookie-panel__box -->
</div>
