# Akrasia – poznámky pro Claude

## Stack
- PHP (bez frameworku) + MySQL (Wedos hosting)
- Vanilla CSS + JS
- Apache s mod_rewrite

## Hosting
- Produkční server: **ztvelebil.online** (Wedos)
- Nasazení: manuálně přes FTP / Wedos File Manager
- GitHub větev: `claude/akrasia-website-setup-etol2`

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
