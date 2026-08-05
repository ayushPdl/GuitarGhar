<?php
/**
 * Application URL helper.
 * Auto-detects the install folder from the current script path.
 * - Hosted at domain root  -> /index.php, /css/style.css
 * - Local /guitarghar      -> /guitarghar/index.php
 * Optional override in includes/config.php: 'base_path' => '' or '/guitarghar'
 */
if (!defined('GG_BASE_PATH')) {
    $ggBase = null;

    $cfgFile = __DIR__ . '/config.php';
    if (is_file($cfgFile)) {
        $cfg = include $cfgFile;
        if (is_array($cfg) && array_key_exists('base_path', $cfg)) {
            $ggBase = (string) $cfg['base_path'];
        }
    }

    if ($ggBase === null) {
        $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
        $dir = $script !== '' ? str_replace('\\', '/', dirname($script)) : '';
        if ($dir === '/' || $dir === '.' || $dir === '\\' || $dir === '') {
            $ggBase = '';
        } else {
            $ggBase = rtrim($dir, '/');
        }
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