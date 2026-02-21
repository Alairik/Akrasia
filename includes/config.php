<?php
/**
 * CMS Configuration
 */

// Database
define('DB_HOST', 'localhost');
define('DB_NAME', 'd391762_cmstst');
define('DB_USER', 'a391762_cmstst');
define('DB_PASS', 'Jvyz23:kpgn4uYW');
define('DB_CHARSET', 'utf8mb4');

// Paths
define('BASE_PATH', dirname(__DIR__));
define('INCLUDES_PATH', BASE_PATH . '/includes');
define('UPLOADS_PATH', BASE_PATH . '/uploads');
define('ADMIN_PATH', BASE_PATH . '/admin');

// URL - adjust to your domain
define('SITE_URL', 'https://akrasia.zvelebil.online');
define('ADMIN_URL', SITE_URL . '/admin');
define('UPLOADS_URL', SITE_URL . '/uploads');

// Site
define('SITE_NAME', 'Akrasia');
define('SITE_TAGLINE', 'PROSTOR, KTERÝ ADHD ROZUMÍ.');
define('SITE_DESCRIPTION', 'Nezisková organizace zvyšující povědomí o ADHD a propojující lidi s ověřenými terapeuty.');
define('ARTICLES_PER_PAGE', 9);

// Social media – doplňte správné URL
define('SOCIAL_FACEBOOK',  'https://www.facebook.com/akrasia');
define('SOCIAL_INSTAGRAM', 'https://www.instagram.com/akrasia');
define('SOCIAL_LINKEDIN',  'https://www.linkedin.com/company/akrasia');
define('SOCIAL_YOUTUBE',   '');

// Google Sheets CSV URL for therapist directory
define('TERAPEUTI_CSV_URL', 'https://docs.google.com/spreadsheets/d/e/2PACX-1vTu1W8jU-XpCb5oJxK56TP4C5Fi5YnjusZrICL284wT-H48apuJsMNaTYexpWun4SvQ3qzo7TUP7ugB/pub?gid=0&single=true&output=csv');

// Tracking IDs – doplňte dle projektu, prázdný řetězec = tracker se nenačte
define('TRACKER_GA_ID',          '');   // Google Analytics 4, např. 'G-XXXXXXXXXX'
define('TRACKER_CLARITY_ID',     '');   // Microsoft Clarity, např. 'abcde12345'
define('TRACKER_META_PIXEL_ID',  '');   // Meta Pixel, např. '123456789012345'
define('TRACKER_TIKTOK_PIXEL_ID','');   // TikTok Pixel, např. 'CXXXXXXXXXXXXXXXXX'

// Session
define('SESSION_LIFETIME', 3600); // 1 hour

// Timezone
date_default_timezone_set('Europe/Prague');

// Error reporting (turn off in production)
ini_set('display_errors', 0);
error_reporting(E_ALL);
