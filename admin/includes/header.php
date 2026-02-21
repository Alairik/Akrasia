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
                <a href="<?= ADMIN_URL ?>/">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="7" height="7"/><rect x="14" y="3" width="7" height="7"/><rect x="3" y="14" width="7" height="7"/><rect x="14" y="14" width="7" height="7"/></svg>
                    <span>Nástěnka</span>
                </a>
            </li>
            <li class="<?= in_array($currentPage, ['pages', 'page-edit']) ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/pages.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z"/><polyline points="14 2 14 8 20 8"/><line x1="16" y1="13" x2="8" y2="13"/><line x1="16" y1="17" x2="8" y2="17"/><line x1="10" y1="9" x2="8" y2="9"/></svg>
                    <span>Stránky</span>
                </a>
            </li>
            <li class="<?= in_array($currentPage, ['articles', 'article-edit', 'categories', 'tags']) ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/articles.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M12 20h9"/><path d="M16.5 3.5a2.121 2.121 0 0 1 3 3L7 19l-4 1 1-4L16.5 3.5z"/></svg>
                    <span>Blog</span>
                </a>
            </li>
            <li class="<?= $currentPage === 'media' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/media.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="3" y="3" width="18" height="18" rx="2"/><circle cx="8.5" cy="8.5" r="1.5"/><polyline points="21 15 16 10 5 21"/></svg>
                    <span>Média</span>
                </a>
            </li>
            <li class="<?= $currentPage === 'messages' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/messages.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M21 15a2 2 0 0 1-2 2H7l-4 4V5a2 2 0 0 1 2-2h14a2 2 0 0 1 2 2z"/></svg>
                    <span>Zprávy<?php $__unread = messages_unread_count(); if ($__unread): ?> <em class="badge-dot"><?= $__unread ?></em><?php endif; ?></span>
                </a>
            </li>
            <li class="<?= in_array($currentPage, ['forms', 'form-edit', 'form-submissions']) ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/forms.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><rect x="9" y="2" width="6" height="4" rx="1"/><path d="M16 4h2a2 2 0 0 1 2 2v14a2 2 0 0 1-2 2H6a2 2 0 0 1-2-2V6a2 2 0 0 1 2-2h2"/><line x1="12" y1="11" x2="12" y2="17"/><line x1="9" y1="14" x2="15" y2="14"/></svg>
                    <span>Formuláře</span>
                </a>
            </li>
            <li class="<?= in_array($currentPage, ['navigations', 'navigation-edit']) ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/navigations.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><line x1="3" y1="6" x2="21" y2="6"/><line x1="3" y1="12" x2="21" y2="12"/><line x1="3" y1="18" x2="15" y2="18"/></svg>
                    <span>Navigace</span>
                </a>
            </li>
            <li class="<?= $currentPage === 'redirects' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/redirects.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M5 12h14"/><path d="M15 6l6 6-6 6"/></svg>
                    <span>Přesměrování</span>
                </a>
            </li>
            <?php if (auth_is_admin()): ?>
            <li class="<?= $currentPage === 'settings' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/settings.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><circle cx="12" cy="12" r="3"/><path d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1-2.83 2.83l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-4 0v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83-2.83l.06-.06A1.65 1.65 0 0 0 4.68 15a1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1 0-4h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 2.83-2.83l.06.06A1.65 1.65 0 0 0 9 4.68a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 4 0v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 2.83l-.06.06A1.65 1.65 0 0 0 19.4 9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 0 4h-.09a1.65 1.65 0 0 0-1.51 1z"/></svg>
                    <span>Nastavení</span>
                </a>
            </li>
            <li class="<?= $currentPage === 'users' ? 'active' : '' ?>">
                <a href="<?= ADMIN_URL ?>/users.php">
                    <svg width="18" height="18" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M20 21v-2a4 4 0 0 0-4-4H8a4 4 0 0 0-4 4v2"/><circle cx="12" cy="7" r="4"/></svg>
                    <span>Uživatelé</span>
                </a>
            </li>
            <?php endif; ?>
        </ul>
        <div class="sidebar-footer">
            <a href="<?= SITE_URL ?>/" target="_blank">
                <svg width="14" height="14" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M18 13v6a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2V8a2 2 0 0 1 2-2h6"/><polyline points="15 3 21 3 21 9"/><line x1="10" y1="14" x2="21" y2="3"/></svg>
                Zobrazit web
            </a>
        </div>
    </nav>
    <div class="admin-main">
        <header class="topbar">
            <h1><?= h($pageTitle ?? 'Administrace') ?></h1>
            <div class="topbar-user">
                <span><?= h($currentUser['username']) ?></span>
                <a href="<?= ADMIN_URL ?>/logout.php">Odhlásit</a>
            </div>
        </header>
        <main class="content">
            <?php foreach (flash_get() as $flash): ?>
                <div class="alert alert-<?= h($flash['type']) ?>"><?= h($flash['message']) ?></div>
            <?php endforeach; ?>
<?php endif; ?>
