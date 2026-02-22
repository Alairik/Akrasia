<?php
/**
 * Akrasia — ZveleCMS Configuration
 */

// Environment
define('ENVIRONMENT', 'prod');

// Database
define('DB_HOST', 'md393.wedos.net');
define('DB_NAME', 'd391762_akrasia');
define('DB_USER', 'a391762_akrasia');
define('DB_PASS', 'cm!u8:kC-FftkT7');
define('DB_CHARSET', 'utf8mb4');

// Site
define('SITE_URL', 'https://akrasia.zvelebil.online');
define('SITE_NAME', 'Akrasia');
// Web base path — subdomain is mapped to /akrasia/ subfolder on WEDOS hosting.
// Empty string if the subdomain maps directly to document root.
define('SITE_BASE', '/akrasia');

// Paths
define('ROOT_PATH', __DIR__);
define('CORE_PATH', ROOT_PATH . '/core');
define('ADMIN_PATH', ROOT_PATH . '/admin');
define('THEME_PATH', ROOT_PATH . '/themes/default');
define('UPLOAD_PATH', ROOT_PATH . '/uploads');
define('CACHE_PATH', ROOT_PATH . '/cache');
define('CONTENT_PATH', ROOT_PATH . '/content');
