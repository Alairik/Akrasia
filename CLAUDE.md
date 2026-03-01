# Akrasia – poznámky pro Claude

## Stack
- PHP (bez frameworku) + MySQL (Wedos hosting)
- Vanilla CSS + JS
- Apache s mod_rewrite

## Hosting
- Produkční server: **zvelebil.online** (Wedos NoLimit)
- Web Akrasie běží na aliasu: **akrasia.zvelebil.online** → složka `/www/akrasia/`
- FTP přihlašovací údaje jsou uloženy v GitHub Secrets: `FTP_SERVER`, `FTP_USERNAME`, `FTP_PASSWORD`
- GitHub větev: `claude/akrasia-website-setup-etol2`

## Autodeploy

**Pokud není řečeno jinak, Claude má právo použít GitHub Actions autodeploy na server.**

- Workflow: `.github/workflows/deploy.yml`
- Spustí se automaticky při každém push na větev `claude/akrasia-website-setup-etol2`
- Nasazuje do `/www/akrasia/` přes FTP
- Excluduje: `.git`, `.github/`, `uploads/`, `Akrasia/` (brand assets), `CLAUDE.md`

Po prvním nasazení spusť `install.php` přes prohlížeč pro vytvoření DB tabulek, poté ho **smaž ze serveru** (nebo přidej do exclude v deploy.yml).

---

## ⚠️ KRITICKÉ: .htaccess

**`.htaccess` ovládá celý hlavní webový server**, na kterém běží i jiné projekty kromě Akrasie.

### Pravidla pro práci s `.htaccess`:

1. **Nikdy nemaž ani neměň existující záznamy.** Stávající pravidla mohou být potřebná pro jiné projekty nebo správné fungování serveru.

2. **Pouze přidávej nové záznamy** pro nové routy/funkce – na konec sekce, nebo s jasným komentářem.

3. **Vždy zabal nová pravidla do `<IfModule mod_rewrite.c>`**, aby server nespadl, pokud mod_rewrite není povolený.

4. **Průchodná pravidla pro složky** (`admin/`, `cms/`, `assets/`, `uploads/`, …) **přidávej, nikdy neodstraňuj.** Chybějící průchod pro složku projektu rozbije přístup k té části webu.

5. **Před jakoukoliv změnou `.htaccess` se zeptej**, zda je to nutné – nebo si přečti stávající soubor na serveru přes FTP/File Manager a porovnej s tím, co plánuješ změnit.

### Příčina tohoto pravidla
Při tvorbě Akrasia webu byl vytvořen nový `.htaccess` bez průchodného pravidla pro `/cms/`. To způsobilo, že přestal fungovat celý hlavní web `ztvelebil.online` i administrace CMS, protože `/cms/` bylo přesměrováno na `index.php` místo správné složky.

---

## CMS Architektura – pravidla pro Claude

### Vztah Akrasia ↔ CMS jádro

- Složka `admin/` v tomto repozitáři je **vlastní kopií CMS jádra** (`Alairik/CMS`)
- Každý projekt má svou kopii – nejsou sdílené instalace
- Cesta k administraci: `/akrasia/admin`

### Kdy přidat změnu do CMS jádra (`Alairik/CMS`)

**Vždy se zeptej uživatele**, než navrhuješ přidat změnu do jádra. Jádro měň pouze pokud:
- Změna je obecně užitečná pro všechny budoucí projekty
- Není projektově-specifická (konkrétní pole, integrace, design Akrasie)
- Nenarušuje ostatní projekty

### Co NESMÍ jít do jádra

- Formuláře, pole nebo integrace specifické pro Akrasii
- Design/CSS Akrasia brandu
- Akrasia-specifické konfigurace

### Postup při zakládání nového projektu

1. Zkopíruj CMS jádro (`Alairik/CMS`) do nové složky
2. Admin bude na `/<NovýProjekt>/admin`
3. Spusť `install.php` pro DB
4. Vytvoř projektový CLAUDE.md s pokyny

---

## Architektura projektu

### Adresářová struktura

```
/                        ← kořen webu (= /www/akrasia/ na serveru)
├── index.php            ← front controller, definuje všechny routy
├── config.php           ← DB přihlašovací údaje + konstanty cest (v gitu!)
├── config.example.php   ← šablona konfigurace bez hesel
├── install.php          ← jednorázový instalátor DB tabulek
├── seed.php             ← naplní DB obsahem (stránky, menu, formuláře)
│
├── core/                ← ZveleCMS jádro (PHP třídy)
│   ├── Auth.php         ← session, login, role
│   ├── Cache.php        ← file-based cache
│   ├── Database.php     ← PDO wrapper (singleton)
│   ├── Form.php         ← zpracování formulářů, honeypot, rate limiting
│   ├── Media.php        ← upload, resize obrázků
│   ├── Router.php       ← lightweight router (get/post/dispatch/notFound)
│   ├── SEO.php          ← meta tagy, og:, twitter:, sitemap
│   ├── Security.php     ← CSRF, sanitizace
│   ├── Sitemap.php      ← XML sitemap generátor
│   ├── Template.php     ← renderování PHP šablon, předávání proměnných
│   ├── bootstrap.php    ← inicializace (session, autoload, helpers)
│   └── helpers.php      ← globální pomocné funkce vč. deco_html()
│
├── admin/               ← administrace (kopie CMS jádra)
│   ├── index.php        ← admin router + auth guard
│   ├── controllers/     ← Dashboard, Form, Media, Menu, Page, Post,
│   │                       Redirect, Settings, User
│   ├── views/           ← PHP šablony adminu
│   └── assets/          ← admin.css, admin.js
│
├── themes/default/      ← Akrasia frontend theme
│   ├── theme.json       ← barvy, fonty, layout config
│   ├── assets/
│   │   ├── style.css    ← celý frontend CSS (vanilla, BEM-like)
│   │   ├── app.js       ← vanilla JS (menu, cookie banner, animace)
│   │   ├── akrasia-logo.svg
│   │   └── akrasia-prvek.svg
│   ├── layouts/
│   │   ├── base.php     ← HTML obálka (head, header, footer, cookie banner)
│   │   ├── page.php     ← layout pro CMS stránky (block renderer)
│   │   ├── blog.php     ← listing blogových příspěvků
│   │   └── post.php     ← detail blogového příspěvku
│   ├── partials/
│   │   ├── header.php   ← navigace (z DB menu), hamburger
│   │   ├── footer.php   ← patička s menu a logem
│   │   ├── cookie-banner.php
│   │   ├── breadcrumbs.php
│   │   ├── pagination.php
│   │   └── post-card.php
│   └── blocks/          ← renderovatelné bloky (volané z page.php)
│       ├── hero.php          ← hlavní hero s tlačítky
│       ├── page-hero.php     ← hero pro podstránky
│       ├── stats.php         ← statistiky (čísla s popiskem)
│       ├── features.php      ← ikony/karty funkcí
│       ├── cta.php           ← call-to-action pruh
│       ├── faq.php           ← accordion FAQ
│       ├── gallery.php       ← fotogalerie
│       ├── image-text.php    ← obrázek vedle textu
│       ├── junction.php      ← rozcestník (2–3 karty s linkem)
│       ├── contact-form.php  ← kontaktní formulář (AJAX nebo klasický POST)
│       ├── testimonials.php  ← recenze/citáty
│       ├── text.php          ← prostý HTML text blok
│       ├── video.php         ← embed videa
│       └── terapeuti.php     ← adresář terapeutů (z API)
│
├── api/                 ← jednoduché JSON endpointy
│   ├── articles.php     ← výpis článků
│   ├── contact.php      ← zpracování kontaktního formuláře
│   └── terapeuti.php    ← výpis terapeutů
│
├── includes/            ← starší helper funkce (legacy, postupně nahrazovat core/)
└── .github/workflows/
    └── deploy.yml       ← FTP autodeploy workflow
```

### Databázové tabulky

| Tabulka | Popis |
|---|---|
| `zvele_pages` | CMS stránky (slug, title, blocks JSON, status, sort_order) |
| `zvele_posts` | Blogové příspěvky |
| `zvele_media` | Nahrané soubory/obrázky |
| `zvele_forms` | Definice formulářů (fields JSON) |
| `zvele_form_submissions` | Odeslané formuláře |
| `zvele_users` | Uživatelé adminu |
| `zvele_menus` | Menu (location + items JSON) |
| `zvele_settings` | Nastavení webu (key-value) |
| `zvele_redirects` | 301/302 přesměrování |
| `zvele_consent_log` | GDPR cookie souhlas log |

### Routy (index.php)

| Metoda | Cesta | Popis |
|---|---|---|
| GET | `/` | Homepage (slug `homepage` z DB) |
| GET | `/blog` | Listing blogových příspěvků |
| GET | `/blog/{slug}` | Detail příspěvku |
| POST | `/form/submit` | Zpracování kontaktních formulářů |
| GET | `/{slug}` | Libovolná CMS stránka dle slugu |

### Design tokens (theme.json)

| Token | Hodnota |
|---|---|
| primary | `#4e5699` (fialovo-modrá) |
| primary-dark | `#3a4175` |
| primary-light | `#eef0f8` |
| accent | `#c9a84c` (zlatá) |
| text | `#1a1d2e` |
| background | `#ffffff` |
| heading font | Outfit |
| body font | Inter |
| max-width | 1200px |

---

## Session Handoff – stav k 2026-03-01

### Co je hotovo (Akrasia 1.0)

**Celý web akrasia.zvelebil.online běží v produkci.** Release snapshot je uložen na větvi `claude/akrasia-1.0-etol2`.

#### Frontend (theme)
- [x] Kompletní homepage se všemi sekcemi: hero, stats (ADHD v číslech), features (hodnoty + co děláme), junction (rozcestník), CTA, FAQ, testimonials
- [x] Dekorativní SVG prvky (`akrasia-prvek.svg`) jako pozadí sekcí
- [x] Header s responzivní navigací a hamburger menu
- [x] Footer s menu a logem
- [x] Cookie banner (GDPR)
- [x] Skip-nav odkaz pro přístupnost
- [x] Responzivní design (mobile-first)

#### CMS & Admin
- [x] ZveleCMS plně funkční: správa stránek, blogových příspěvků, médií, menu, formulářů, nastavení, uživatelů, přesměrování
- [x] Block editor v adminu pro Akrasia-specifické bloky (s ID při načtení z DB)
- [x] Administrace dostupná na `/akrasia/admin`

#### Kontaktní formuláře
- [x] 3 formuláře v DB: `kontakt`, `hledam-podporu`, `pro-firmy`
- [x] Zpracování přes `core/Form.php` (honeypot, rate limiting, CSRF)
- [x] E-maily odesílány na nakonfigurovanou adresu

#### Infrastruktura
- [x] GitHub Actions FTP autodeploy (při každém push na vývojovou větev)
- [x] `dangerous-clean-slate: true` – server je vždy čistý obraz gitu
- [x] `config.php` s produkčními credentials je v gitu (vědomé rozhodnutí)
- [x] `install.php` + `seed.php` pro inicializaci DB

#### Pomocné funkce
- [x] `deco_html()` helper v `core/helpers.php` – generuje dekorativní SVG HTML
- [x] `Router`, `Database`, `Template`, `Auth`, `SEO`, `Cache`, `Security`, `Form`, `Media`, `Sitemap` – vše funkční

### Stránky v databázi (seed.php)

| Slug | Popis |
|---|---|
| `homepage` | Domovská stránka |
| `terapeuti` | Adresář terapeutů |
| `blog` | Blog (listing) |
| `kontakt` | Kontaktní stránka |
| `hledam-podporu` | Pro lidi hledající podporu |
| `pro-firmy` | B2B sekce |

### Poslední commity

```
0df7b9c fix: přidat deco_html() do core/helpers.php
23bba83 feat: HP – chybějící sekce + dekorativní tvary obnoveny
a506306 fix: admin block editor – přidat ID bloků při načtení z DB
24c5e05 feat: kontaktní formuláře + admin block editor pro Akrasia bloky
e2a6daa fix: skip-nav class + homepage rozšíření o chybějící sekce
b6ef8ef fix: menu, font Bely Display, cookie banner
f0a793a fix: RewriteBase /akrasia/ + SITE_BASE prefix stripping
8d1fe15 feat: celý frontend přesunut do ZveleCMS + Akrasia design
```

### Známé limitace / tech debt

- `includes/` obsahuje starší helper funkce (legacy z původního projektu) – postupně nahrazovat `core/` třídami
- `config.php` obsahuje DB heslo přímo v gitu – zvážit přechod na env proměnné nebo `.gitignore` s ručním uploadem
- `api/terapeuti.php` a `includes/terapeuti.php` – adresář terapeutů je připraven strukturálně, ale data jsou zatím statická/mockovaná
- `seed.php` a `install.php` jsou na serveru dostupné (měly by být smazány nebo přidány do `.htaccess` deny)

### Možné next steps (neurčeno pořadí)

- [ ] Reálná data terapeutů (databáze nebo API integrace)
- [ ] Blog – první obsah (články o ADHD)
- [ ] SEO optimalizace (meta, og:, sitemap.xml)
- [ ] Galerie / fotky pro jednotlivé sekce
- [ ] Vícejazyčnost (cs/en)?
- [ ] Newsletter integrace
- [ ] Analytika (GA4 nebo Plausible)
- [ ] Smazání install.php a seed.php ze serveru (přidat do deploy exclude)
