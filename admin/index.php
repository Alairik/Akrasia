<?php
$pageTitle = 'Dashboard';
require_once __DIR__ . '/includes/header.php';
auth_require();

$totalArticles     = articles_count();
$publishedArticles = articles_count('published');
$draftArticles     = articles_count('draft');
$totalPages        = pages_count();
$publishedPages    = pages_count('published');
$totalMedia        = media_count();
$totalForms        = count(forms_list());
$unreadMessages    = messages_unread_count();
$recentArticles    = articles_list(5, 0);
?>

<h1>Dashboard</h1>

<div class="stats-grid">
    <div class="stat-card">
        <div class="stat-number"><?= $publishedPages ?></div>
        <div class="stat-label"><a href="<?= ADMIN_URL ?>/pages.php">Stránky</a></div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $publishedArticles ?></div>
        <div class="stat-label"><a href="<?= ADMIN_URL ?>/articles.php">Blog příspěvky</a></div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $draftArticles ?></div>
        <div class="stat-label">Koncepty</div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $totalMedia ?></div>
        <div class="stat-label"><a href="<?= ADMIN_URL ?>/media.php">Soubory v médiích</a></div>
    </div>
    <div class="stat-card">
        <div class="stat-number"><?= $totalForms ?></div>
        <div class="stat-label"><a href="<?= ADMIN_URL ?>/forms.php">Formuláře</a></div>
    </div>
    <div class="stat-card" style="<?= $unreadMessages ? 'border:2px solid #e63946;' : '' ?>">
        <div class="stat-number" style="<?= $unreadMessages ? 'color:#e63946;' : '' ?>"><?= $unreadMessages ?></div>
        <div class="stat-label"><a href="<?= ADMIN_URL ?>/messages.php">Nové zprávy</a></div>
    </div>
</div>

<div style="display:grid;grid-template-columns:1fr 1fr;gap:1.5rem;">
    <div class="card">
        <div class="card-header">
            <h2>Poslední příspěvky</h2>
            <a href="<?= ADMIN_URL ?>/article-edit.php" class="btn btn-primary btn-sm">+ Nový</a>
        </div>
        <?php if (empty($recentArticles)): ?>
            <p class="empty-state">Žádné příspěvky. <a href="<?= ADMIN_URL ?>/article-edit.php">Vytvořte první!</a></p>
        <?php else: ?>
            <table class="table">
                <thead>
                    <tr><th>Titulek</th><th>Stav</th><th>Datum</th></tr>
                </thead>
                <tbody>
                    <?php foreach ($recentArticles as $article): ?>
                    <tr>
                        <td><a href="<?= ADMIN_URL ?>/article-edit.php?id=<?= $article['id'] ?>"><?= h($article['title']) ?></a></td>
                        <td><span class="badge badge-<?= $article['status'] === 'published' ? 'success' : 'warning' ?>"><?= $article['status'] === 'published' ? 'Pub.' : 'Konc.' ?></span></td>
                        <td style="font-size:.8rem;color:#888;"><?= date('j.n.Y', strtotime($article['created_at'])) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>

    <div class="card">
        <div class="card-header">
            <h2>Rychlé akce</h2>
        </div>
        <div style="display:flex;flex-direction:column;gap:.5rem;">
            <a href="<?= ADMIN_URL ?>/page-edit.php" class="btn btn-secondary">+ Nová stránka</a>
            <a href="<?= ADMIN_URL ?>/article-edit.php" class="btn btn-secondary">+ Nový příspěvek</a>
            <a href="<?= ADMIN_URL ?>/media.php" class="btn btn-secondary">+ Nahrát médium</a>
            <a href="<?= ADMIN_URL ?>/forms.php" class="btn btn-secondary">Spravovat formuláře</a>
            <a href="<?= ADMIN_URL ?>/navigations.php" class="btn btn-secondary">Spravovat navigace</a>
            <?php if (auth_is_admin()): ?>
            <a href="<?= ADMIN_URL ?>/settings.php" class="btn btn-secondary">Nastavení webu</a>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php require_once __DIR__ . '/includes/footer.php'; ?>
