<section class="page-hero" style="position:relative;overflow:hidden">
<?= deco_html([9,4]) ?>
    <div class="container">
        <nav class="breadcrumb" aria-label="Drobečková navigace">
            <a href="<?= SITE_URL ?>/">Domů</a>
            <span class="breadcrumb-sep" aria-hidden="true">›</span>
            <span>Zásady ochrany osobních údajů</span>
        </nav>
        <h1>Zásady ochrany osobních údajů</h1>
        <p>Informace o tom, jak zpracováváme vaše osobní údaje v souladu s GDPR.</p>
    </div>
</section>

<section class="page-content" style="position:relative;overflow:hidden">
<?= deco_html([1,11]) ?>
    <div class="container">
        <div class="page-content-body">

            <p style="color:var(--text-muted);font-size:var(--text-sm);">Poslední aktualizace: <?= date('j. n. Y') ?></p>

            <h2>1. Správce osobních údajů</h2>
            <p>
                Správcem vašich osobních údajů je:
            </p>
            <p>
                <strong>Akrasia, z.s.</strong><br>
                <!-- TODO: doplnit adresu sídla --><br>
                <!-- TODO: doplnit IČO --><br>
                <!-- TODO: doplnit kontaktní e-mail -->
            </p>

            <h2>2. Jaké osobní údaje zpracováváme</h2>
            <p>Zpracováváme pouze údaje, které nám sami poskytnete, nebo které vznikají při návštěvě webu:</p>
            <ul>
                <li><strong>Kontaktní formuláře:</strong> jméno, e-mailová adresa, obsah zprávy</li>
                <li><strong>Přihláška za člena:</strong> jméno, e-mailová adresa, volitelně další informace</li>
                <li><strong>Newsletter:</strong> e-mailová adresa</li>
                <li><strong>Technické údaje:</strong> IP adresa, typ prohlížeče (pro zajištění bezpečnosti webu)</li>
                <li><strong>Analytická data (se souhlasem):</strong> pseudonymizované informace o chování na webu sbírané prostřednictvím Google Analytics 4 a Microsoft Clarity</li>
                <li><strong>Marketingová data (se souhlasem):</strong> identifikátory pro reklamní sítě (Meta Pixel, TikTok Pixel) a interakce s e-maily (otevření, kliknutí)</li>
            </ul>

            <h2>3. Účely a právní základ zpracování</h2>
            <ul>
                <li><strong>Odpověď na vaši zprávu</strong> – oprávněný zájem (čl. 6 odst. 1 písm. f) GDPR)</li>
                <li><strong>Správa členství</strong> – plnění smlouvy (čl. 6 odst. 1 písm. b) GDPR)</li>
                <li><strong>Zasílání newsletteru</strong> – váš souhlas (čl. 6 odst. 1 písm. a) GDPR)</li>
                <li><strong>Bezpečnost webu</strong> – oprávněný zájem</li>
            </ul>

            <h2>4. Komu údaje předáváme</h2>
            <p>
                Vaše osobní údaje neprodáváme. Předáváme je pouze důvěryhodným zpracovatelům,
                kteří data zpracovávají výhradně dle našich pokynů a jsou smluvně zavázáni k mlčenlivosti:
            </p>
            <ul>
                <li><strong>Poskytovatel webhostingu</strong> – pro provoz webu (ČR / EU)</li>
                <li><strong>E-mailová platforma (Mailchimp / Brevo)</strong> – pro zasílání newsletterů a transakcních e-mailů</li>
                <li><strong>Google LLC (Google Analytics 4)</strong> – analytická data (USA; Standard Contractual Clauses), <em>pouze se souhlasem</em></li>
                <li><strong>Microsoft Corporation (Microsoft Clarity)</strong> – heatmapy a UX analýza (USA; Standard Contractual Clauses), <em>pouze se souhlasem</em></li>
                <li><strong>Meta Platforms, Inc. (Meta Pixel)</strong> – měření kampaní a remarketing (USA; Standard Contractual Clauses), <em>pouze se souhlasem</em></li>
                <li><strong>TikTok Technology Limited (TikTok Pixel)</strong> – měření kampaní (USA/Singapur; Standard Contractual Clauses), <em>pouze se souhlasem</em></li>
            </ul>

            <h2>5. Doba uchovávání údajů</h2>
            <ul>
                <li>Zprávy z kontaktního formuláře: 1 rok od vyřešení</li>
                <li>Členské údaje: po dobu členství + 3 roky po ukončení</li>
                <li>Newsletter: do odvolání souhlasu</li>
            </ul>

            <h2>6. Vaše práva</h2>
            <p>Máte právo:</p>
            <ul>
                <li>Požádat o přístup ke svým osobním údajům</li>
                <li>Požádat o opravu nebo výmaz svých údajů</li>
                <li>Vznést námitku proti zpracování</li>
                <li>Požádat o omezení zpracování</li>
                <li>Na přenositelnost údajů</li>
                <li>Odvolat souhlas (pokud je zpracování na souhlasu založeno)</li>
                <li>Podat stížnost u Úřadu pro ochranu osobních údajů (www.uoou.cz)</li>
            </ul>

            <h2 id="cookies">7. Cookies a sledovací technologie</h2>
            <p>
                Náš web používá cookies a podobné technologie. Níže uvádíme přehled
                všech kategorií. Svůj souhlas můžete kdykoli změnit kliknutím na
                <button onclick="CookieConsent.reset()" style="background:none;border:none;color:var(--navy);text-decoration:underline;cursor:pointer;font:inherit;padding:0;">„Nastavení cookies"</button>
                v patičce stránky.
            </p>

            <h3>Nezbytné cookies (vždy aktivní)</h3>
            <p>Zajišťují základní fungování webu. Souhlas se nevyžaduje.</p>
            <table style="width:100%;border-collapse:collapse;font-size:var(--text-sm)">
                <thead><tr style="background:var(--vanilla)">
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Název cookie</th>
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Účel</th>
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Platnost</th>
                </tr></thead>
                <tbody>
                <tr>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)"><code>cc_consent</code></td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Uložení vašeho výběru cookies</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">1 rok</td>
                </tr>
                <tr>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)"><code>PHPSESSID</code></td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Session prohlížeče (bezpečnost)</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Do zavření prohlížeče</td>
                </tr>
                </tbody>
            </table>

            <h3 style="margin-top:var(--space-6)">Analytické cookies (se souhlasem)</h3>
            <p>Pomáhají nám pochopit chování návštěvníků. Data jsou anonymizovaná nebo pseudonymizovaná.</p>
            <table style="width:100%;border-collapse:collapse;font-size:var(--text-sm)">
                <thead><tr style="background:var(--vanilla)">
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Služba</th>
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Správce</th>
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Účel</th>
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Více info</th>
                </tr></thead>
                <tbody>
                <tr>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Google Analytics 4</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Google LLC (USA)</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Návštěvnost, zdroje, chování uživatelů</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)"><a href="https://policies.google.com/privacy" target="_blank" rel="noopener">Zásady Google</a></td>
                </tr>
                <tr>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Microsoft Clarity</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Microsoft Corp. (USA)</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Heatmapy, anonymizované záznamy relací</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)"><a href="https://privacy.microsoft.com/cs-cz/privacystatement" target="_blank" rel="noopener">Zásady Microsoft</a></td>
                </tr>
                </tbody>
            </table>

            <h3 style="margin-top:var(--space-6)">Marketingové cookies (se souhlasem)</h3>
            <p>Slouží k měření efektivity kampaní a personalizaci reklamy. Data mohou být sdílena s reklamními sítěmi.</p>
            <table style="width:100%;border-collapse:collapse;font-size:var(--text-sm)">
                <thead><tr style="background:var(--vanilla)">
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Služba</th>
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Správce</th>
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Účel</th>
                    <th style="text-align:left;padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Více info</th>
                </tr></thead>
                <tbody>
                <tr>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Meta Pixel</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Meta Platforms, Inc. (USA)</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Konverze Facebook/Instagram, remarketing</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)"><a href="https://www.facebook.com/privacy/policy/" target="_blank" rel="noopener">Zásady Meta</a></td>
                </tr>
                <tr>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">TikTok Pixel</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">TikTok Technology Limited (USA/SG)</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)">Měření výkonu TikTok kampaní</td>
                    <td style="padding:.5rem .75rem;border:1px solid rgba(78,86,153,.15)"><a href="https://www.tiktok.com/legal/page/row/privacy-policy/en" target="_blank" rel="noopener">Zásady TikTok</a></td>
                </tr>
                </tbody>
            </table>

            <h3 id="emailing" style="margin-top:var(--space-6)">E-mailový marketing</h3>
            <p>
                Pokud odběr newsletteru povolíte, vaši e-mailovou adresu předáme naší e-mailové platformě
                (Mailchimp nebo Brevo). Tato platforma sleduje otevření e-mailů a kliknutí v nich pomocí
                pixelu v e-mailu. Toto sledování probíhá na základě vašeho souhlasu s odběrem newsletteru
                a kdykoli ho můžete odvolat odhlášením z odběru (odkaz je v každém e-mailu).
            </p>

            <h2>8. Kontakt</h2>
            <p>
                S dotazy týkajícími se ochrany osobních údajů nás kontaktujte na:
                <!-- TODO: doplnit e-mail -->
            </p>

        </div>
    </div>
</section>
