/*!
 * Cookie Consent Manager – GDPR baseline
 * Kategorie: necessary | analytics | marketing
 * Cookie: "cc_consent" (JSON), platnost 1 rok
 *
 * Použití (v header.php):
 *   CookieConsent.init({ gaId: '...', clarityId: '...', metaPixelId: '...', tiktokPixelId: '...' });
 *
 * Reset souhlasu (např. odkaz v patičce):
 *   CookieConsent.reset();
 */
(function () {
    'use strict';

    var COOKIE_NAME = 'cc_consent';
    var COOKIE_DAYS = 365;

    /* ------------------------------------------------------------------ */
    /* Cookie helpers                                                        */
    /* ------------------------------------------------------------------ */

    function readConsent() {
        var match = document.cookie.match(new RegExp('(?:^|; )' + COOKIE_NAME + '=([^;]*)'));
        if (!match) return null;
        try { return JSON.parse(decodeURIComponent(match[1])); } catch (e) { return null; }
    }

    function writeConsent(obj) {
        var val     = encodeURIComponent(JSON.stringify(obj));
        var maxAge  = COOKIE_DAYS * 24 * 3600;
        var secure  = location.protocol === 'https:' ? '; Secure' : '';
        document.cookie = COOKIE_NAME + '=' + val
            + '; max-age=' + maxAge
            + '; path=/'
            + '; SameSite=Lax'
            + secure;
    }

    /* ------------------------------------------------------------------ */
    /* Script loaders                                                        */
    /* ------------------------------------------------------------------ */

    function loadScript(src, id) {
        if (id && document.getElementById(id)) return;
        var s = document.createElement('script');
        s.src   = src;
        s.async = true;
        if (id) s.id = id;
        document.head.appendChild(s);
    }

    function loadInlineScript(code, id) {
        if (id && document.getElementById(id)) return;
        var s = document.createElement('script');
        s.text = code;
        if (id) s.id = id;
        document.head.appendChild(s);
    }

    /* ------------------------------------------------------------------ */
    /* Tracker activation                                                   */
    /* ------------------------------------------------------------------ */

    function activateAnalytics(cfg) {
        // Google Analytics 4
        if (cfg.gaId) {
            loadScript(
                'https://www.googletagmanager.com/gtag/js?id=' + cfg.gaId,
                'cc-gtag-js'
            );
            loadInlineScript(
                'window.dataLayer=window.dataLayer||[];'
                + 'function gtag(){dataLayer.push(arguments);}'
                + 'gtag("js",new Date());'
                + 'gtag("config","' + cfg.gaId + '");',
                'cc-gtag-init'
            );
        }
        // Microsoft Clarity
        if (cfg.clarityId) {
            loadInlineScript(
                '(function(c,l,a,r,i,t,y){'
                + 'c[a]=c[a]||function(){(c[a].q=c[a].q||[]).push(arguments)};'
                + 't=l.createElement(r);t.async=1;'
                + 't.src="https://www.clarity.ms/tag/"+i;'
                + 'y=l.getElementsByTagName(r)[0];y.parentNode.insertBefore(t,y)'
                + '})(window,document,"clarity","script","' + cfg.clarityId + '");',
                'cc-clarity-init'
            );
        }
    }

    function activateMarketing(cfg) {
        // Meta Pixel
        if (cfg.metaPixelId) {
            loadInlineScript(
                '!function(f,b,e,v,n,t,s){'
                + 'if(f.fbq)return;n=f.fbq=function(){'
                + 'n.callMethod?n.callMethod.apply(n,arguments):n.queue.push(arguments)};'
                + 'if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version="2.0";'
                + 'n.queue=[];t=b.createElement(e);t.async=!0;t.src=v;'
                + 's=b.getElementsByTagName(e)[0];s.parentNode.insertBefore(t,s)}'
                + '(window,document,"script","https://connect.facebook.net/en_US/fbevents.js");'
                + 'fbq("init","' + cfg.metaPixelId + '");'
                + 'fbq("track","PageView");',
                'cc-meta-pixel-init'
            );
        }
        // TikTok Pixel
        if (cfg.tiktokPixelId) {
            loadInlineScript(
                '!function(w,d,t){'
                + 'w.TiktokAnalyticsObject=t;'
                + 'var ttq=w[t]=w[t]||[];'
                + 'ttq.methods=["page","track","identify","instances","debug","on","off","once","ready","alias","group","enableCookie","disableCookie"];'
                + 'ttq.setAndDefer=function(t,e){t[e]=function(){t.push([e].concat(Array.prototype.slice.call(arguments,0)))}};'
                + 'for(var i=0;i<ttq.methods.length;i++)ttq.setAndDefer(ttq,ttq.methods[i]);'
                + 'ttq.instance=function(t){for(var e=ttq._i[t]||[],n=0;n<ttq.methods.length;n++)ttq.setAndDefer(e,ttq.methods[n]);return e};'
                + 'ttq.load=function(e,n){'
                + 'var i="https://analytics.tiktok.com/i18n/pixel/events.js";'
                + 'ttq._i=ttq._i||{};ttq._i[e]=[];ttq._i[e]._u=i;'
                + 'ttq._t=ttq._t||{};ttq._t[e]=+new Date;'
                + 'ttq._o=ttq._o||{};ttq._o[e]=n||{};'
                + 'var o=document.createElement("script");'
                + 'o.type="text/javascript";o.async=!0;o.src=i+"?sdkid="+e+"&lib="+t;'
                + 'var a=document.getElementsByTagName("script")[0];a.parentNode.insertBefore(o,a)};'
                + 'ttq.load("' + cfg.tiktokPixelId + '");ttq.page()'
                + '}(window,document,"ttq");',
                'cc-tiktok-pixel-init'
            );
        }
    }

    /* ------------------------------------------------------------------ */
    /* Banner UI helpers                                                     */
    /* ------------------------------------------------------------------ */

    function show(id) {
        var el = document.getElementById(id);
        if (el) el.removeAttribute('hidden');
    }

    function hide(id) {
        var el = document.getElementById(id);
        if (el) el.setAttribute('hidden', '');
    }

    /* ------------------------------------------------------------------ */
    /* Apply & persist consent                                              */
    /* ------------------------------------------------------------------ */

    function applyConsent(consent, cfg) {
        writeConsent(consent);
        if (consent.analytics) activateAnalytics(cfg);
        if (consent.marketing) activateMarketing(cfg);
        hide('cookie-banner');
        hide('cookie-panel');
        // Informuj ostatní scripty
        try {
            document.dispatchEvent(new CustomEvent('cc:consent', { detail: consent }));
        } catch (e) {}
    }

    /* ------------------------------------------------------------------ */
    /* Public API                                                           */
    /* ------------------------------------------------------------------ */

    window.CookieConsent = {

        init: function (cfg) {
            cfg = cfg || {};
            var existing = readConsent();

            // Vracející se návštěvník – aplikuj bez banneru (server-side už načetl skripty)
            if (existing) {
                if (existing.analytics) activateAnalytics(cfg);
                if (existing.marketing) activateMarketing(cfg);
                return;
            }

            // První návštěva – zobraz banner po načtení DOMu
            function onReady() {
                show('cookie-banner');

                var btnAll    = document.getElementById('cc-accept-all');
                var btnNone   = document.getElementById('cc-reject-all');
                var btnDetail = document.getElementById('cc-settings');
                var btnSave   = document.getElementById('cc-save');
                var btnClose  = document.getElementById('cc-panel-close');

                if (btnAll) {
                    btnAll.addEventListener('click', function () {
                        applyConsent({ necessary: true, analytics: true, marketing: true }, cfg);
                    });
                }
                if (btnNone) {
                    btnNone.addEventListener('click', function () {
                        applyConsent({ necessary: true, analytics: false, marketing: false }, cfg);
                    });
                }
                if (btnDetail) {
                    btnDetail.addEventListener('click', function () {
                        show('cookie-panel');
                    });
                }
                if (btnSave) {
                    btnSave.addEventListener('click', function () {
                        var chkA = document.getElementById('cc-toggle-analytics');
                        var chkM = document.getElementById('cc-toggle-marketing');
                        applyConsent({
                            necessary: true,
                            analytics: chkA ? chkA.checked : false,
                            marketing: chkM ? chkM.checked : false
                        }, cfg);
                    });
                }
                if (btnClose) {
                    btnClose.addEventListener('click', function () {
                        hide('cookie-panel');
                    });
                }
                // Zavřít panel kliknutím na overlay
                var overlay = document.getElementById('cookie-panel-overlay');
                if (overlay) {
                    overlay.addEventListener('click', function () {
                        hide('cookie-panel');
                    });
                }
            }

            if (document.readyState === 'loading') {
                document.addEventListener('DOMContentLoaded', onReady);
            } else {
                onReady();
            }
        },

        getConsent: function () {
            return readConsent();
        },

        // Smaže souhlas a načte stránku znovu – použij jako onclick pro "Změnit nastavení cookies"
        reset: function () {
            document.cookie = COOKIE_NAME + '=; max-age=0; path=/; SameSite=Lax';
            location.reload();
        }
    };

}());
