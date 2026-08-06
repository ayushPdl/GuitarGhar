<?php
/**
 * URL helper
 *
 * LIVE HOSTING: files sit directly in htdocs / public_html (NO subfolder).
 *   Use:  'base_path' => ''   in includes/config.php
 *   URLs become: /index.php, /css/style.css, /login.php
 *
 * LOCAL XAMPP only (optional): if the project is in htdocs/guitarghar
 *   Use:  'base_path' => '/guitarghar'
 *
 * If base_path is not set in config, we auto-detect from SCRIPT_NAME.
 */
if (!defined('GG_BASE_PATH')) {
    $ggBase = null;

    $cfgFile = __DIR__ . '/config.php';
    if (is_file($cfgFile)) {
        ob_start();
        $cfg = include $cfgFile;
        ob_end_clean();
        if (is_array($cfg) && array_key_exists('base_path', $cfg)) {
            $ggBase = (string) $cfg['base_path'];
        }
    }

    // Auto-detect only when config does not define base_path
    if ($ggBase === null) {
        $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
        $dir = $script !== '' ? str_replace('\\', '/', dirname($script)) : '';
        if ($dir === '/' || $dir === '\\' || $dir === '.' || $dir === '') {
            $ggBase = ''; // live / domain root
        } else {
            $ggBase = rtrim($dir, '/'); // e.g. local /guitarghar
        }
    }

    if ($ggBase === '/' || $ggBase === '\\' || $ggBase === '.') {
        $ggBase = '';
    }

    define('GG_BASE_PATH', rtrim(str_replace('\\', '/', (string) $ggBase), '/'));
}

if (!function_exists('url')) {
    function url($path = '') {
        $path = ltrim((string) $path, '/');
        $base = GG_BASE_PATH;
        if ($path === '') {
            return $base === '' ? '/' : $base . '/';
        }
        return ($base === '' ? '' : $base) . '/' . $path;
    }
}