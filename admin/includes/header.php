<?php
require_once dirname(__DIR__, 2) . '/includes/config.php';
require_once INCLUDES_PATH . '/db.php';
require_once INCLUDES_PATH . '/auth.php';
require_once INCLUDES_PATH . '/helpers.php';
require_once INCLUDES_PATH . '/articles.php';
require_once INCLUDES_PATH . '/categories.php';
require_once INCLUDES_PATH . '/messages.php';
require_once INCLUDES_PATH . '/pages.php';
require_once INCLUDES_PATH . '/media.php';
require_once INCLUDES_PATH . '/forms.php';
require_once INCLUDES_PATH . '/navigations.php';
require_once INCLUDES_PATH . '/settings.php';
require_once INCLUDES_PATH . '/redirects.php';

auth_start_session();
auth_require();

$currentUser = auth_user();
$currentPage = basename($_SERVER['SCRIPT_NAME'], '.php');
?>
<!DOCTYPE html>
<html lang="cs">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= h($pageTitle ?? 'Administrace') ?> — <?= h(SITE_NAME) ?></title>
    <link rel="stylesheet" href="<?= ADMIN_URL ?>/assets/style.css">
</head>
<body>
<?php if ($currentUser): ?>
<div class="admin-layout">
    <nav class="sidebar">
        <div class="sidebar-brand">
            <a href="<?= ADMIN_URL ?>/"><?= h(SITE_NAME) ?></a>
        </div>
        <ul class="sidebar-nav">
            <li class="<?= $currentPage === 'index' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/">Dashboard</a>
            </li>

            <li class="sidebar-section-label">Obsah</li>

            <li class="<?= in_array($currentPage, ['pages', 'page-edit']) ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/pages.php">Stránky</a>
            </li>
            <li class="<?= in_array($currentPage, ['articles', 'article-edit', 'categories', 'tags']) ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/articles.php">Blog</a>
            </li>
            <li class="<?= $currentPage === 'media' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/media.php">Média</a>
            </li>

            <li class="sidebar-section-label">Komunikace</li>

            <li class="<?= $currentPage === 'messages' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/messages.php">
                    Zprávy
                    <?php $__unread = messages_unread_count(); if ($__unread): ?>
                        <span style="background:#e63946;color:#fff;border-radius:10px;padding:0 6px;font-size:.7rem;margin-left:4px;font-weight:700;"><?= $__unread ?></span>
                    <?php endif; ?>
                </a>
            </li>
            <li class="<?= in_array($currentPage, ['forms', 'form-edit', 'form-submissions']) ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/forms.php">Formuláře</a>
            </li>

            <li class="sidebar-section-label">Struktura</li>

            <li class="<?= in_array($currentPage, ['navigations', 'navigation-edit']) ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/navigations.php">Navigace</a>
            </li>
            <li class="<?= $currentPage === 'redirects' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/redirects.php">Přesměrování</a>
            </li>

            <?php if (auth_is_admin()): ?>
            <li class="sidebar-section-label">Systém</li>

            <li class="<?= $currentPage === 'settings' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/settings.php">Nastavení</a>
            </li>
            <li class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/users.php">Uživatelé</a>
            </li>
            <?php endif; ?>
        </ul>
        <div class="sidebar-footer">
            <span><?= h($currentUser['username']) ?> (<?= h($currentUser['role']) ?>)</span>
            <a href="<?= ADMIN_URL ?>/logout.php">Odhlásit</a>
            <a href="<?= SITE_URL ?>/" target="_blank">Web</a>
        </div>
    </nav>
    <main class="content">
        <?php foreach (flash_get() as $flash): ?>
            <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
        <?php endforeach; ?>
<?php endif; ?>
