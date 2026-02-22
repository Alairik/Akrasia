<?php defined('ZVELE_CMS') or die(); ?>

<!-- Cookie banner (zobrazí se první návštěvníkům přes CookieConsent.init) -->
<div id="cookie-banner" class="cookie-banner" hidden role="dialog" aria-label="Cookie souhlas" aria-modal="true">
    <div class="cookie-banner__inner">
        <div class="cookie-banner__text">
            <strong><?= e(setting('cookie_banner_title', 'Tato stránka používá cookies')) ?></strong>
            <p><?= e(setting('cookie_banner_text', 'Používáme cookies pro analýzu návštěvnosti a zlepšení funkčnosti webu. Vyberte si, co přijmete.')) ?></p>
        </div>
        <div class="cookie-banner__actions">
            <button type="button" class="btn btn-primary" id="cc-accept-all">
                <?= e(setting('cookie_banner_accept', 'Přijmout vše')) ?>
            </button>
            <button type="button" class="btn btn--cookie-outline" id="cc-reject-all">
                <?= e(setting('cookie_banner_reject', 'Odmítnout vše')) ?>
            </button>
            <button type="button" class="btn btn--cookie-ghost" id="cc-settings">
                <?= e(setting('cookie_banner_settings', 'Nastavit předvolby')) ?>
            </button>
        </div>
    </div>
</div>

<!-- Cookie settings panel (modál) -->
<div id="cookie-panel" class="cookie-panel" hidden role="dialog" aria-modal="true" aria-label="Nastavení cookies">
    <div id="cookie-panel-overlay" class="cookie-panel__overlay"></div>
    <div class="cookie-panel__box">

        <div class="cookie-panel__header">
            <h2 class="cookie-panel__title">Nastavení cookies</h2>
            <button type="button" class="cookie-panel__close" id="cc-panel-close" aria-label="Zavřít">
                <svg width="20" height="20" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2.5" stroke-linecap="round"><path d="M18 6L6 18M6 6l12 12"/></svg>
            </button>
        </div>

        <div class="cookie-panel__body">
            <p class="cookie-panel__intro">Zvolte, které kategorie cookies chcete povolit. Nezbytné cookies jsou vždy aktivní.</p>

            <!-- Nezbytné (vždy zapnuto) -->
            <div class="cookie-cat">
                <div class="cookie-cat__head">
                    <span class="cookie-cat__name">Nezbytné cookies</span>
                    <span class="cookie-badge--locked">Vždy aktivní</span>
                </div>
                <p class="cookie-cat__desc">Zajišťují základní fungování webu — přihlášení, formuláře, zabezpečení.</p>
            </div>

            <!-- Analytické -->
            <div class="cookie-cat">
                <div class="cookie-cat__head">
                    <span class="cookie-cat__name">Analytické cookies</span>
                    <label class="cookie-switch" aria-label="Analytické cookies">
                        <input type="checkbox" id="cc-toggle-analytics" value="analytics">
                        <span class="cookie-switch__track"></span>
                    </label>
                </div>
                <p class="cookie-cat__desc">Pomáhají nám pochopit, jak návštěvníci web používají (Google Analytics, Clarity).</p>
            </div>

            <!-- Marketingové -->
            <div class="cookie-cat">
                <div class="cookie-cat__head">
                    <span class="cookie-cat__name">Marketingové cookies</span>
                    <label class="cookie-switch" aria-label="Marketingové cookies">
                        <input type="checkbox" id="cc-toggle-marketing" value="marketing">
                        <span class="cookie-switch__track"></span>
                    </label>
                </div>
                <p class="cookie-cat__desc">Slouží k personalizaci reklam a měření kampaní (Meta Pixel, TikTok).</p>
            </div>
        </div>

        <div class="cookie-panel__footer">
            <button type="button" class="btn btn-primary" id="cc-save">Uložit předvolby</button>
            <button type="button" class="btn btn--cookie-outline" id="cc-reject-all-panel">Odmítnout vše</button>
        </div>

    </div>
</div>
