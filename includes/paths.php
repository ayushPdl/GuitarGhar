<?php
/**
 * URL helper - always follows where the app is actually served.
 *
 * Live (domain root):  /index.php, /css/style.css
 * Local XAMPP folder:  /guitarghar/index.php  (auto-detected from SCRIPT_NAME)
 *
 * Do NOT set base_path to /guitarghar on live hosting - that breaks CSS/images.
 * Auto-detect from SCRIPT_NAME is the source of truth. A config base_path is
 * only used when it matches the detected folder (local convenience).
 */
if (!defined('GG_BASE_PATH')) {
    $script = isset($_SERVER['SCRIPT_NAME']) ? str_replace('\\', '/', $_SERVER['SCRIPT_NAME']) : '';
    $dir = $script !== '' ? str_replace('\\', '/', dirname($script)) : '';

    if ($dir === '/' || $dir === '\\' || $dir === '.' || $dir === '') {
        $detected = '';
    } else {
        $detected = rtrim($dir, '/');
        if ($detected === '/' || $detected === '\\' || $detected === '.') {
            $detected = '';
        }
    }

    $ggBase = $detected;

    // Optional config: never override a root install with a subfolder path.
    // (Uploading local config.php with base_path=/guitarghar was breaking live.)
    $cfgFile = __DIR__ . '/config.php';
    if (is_file($cfgFile)) {
        ob_start();
        $cfg = include $cfgFile;
        ob_end_clean();
        if (is_array($cfg) && array_key_exists('base_path', $cfg)) {
            $configured = rtrim(str_replace('\\', '/', (string) $cfg['base_path']), '/');
            if ($configured === '/' || $configured === '\\' || $configured === '.') {
                $configured = '';
            }

            if ($detected === '') {
                // Site is at domain root - ignore any /guitarghar from config
                $ggBase = '';
            } elseif ($configured === $detected || $configured === '') {
                $ggBase = $detected;
            } else {
                // Prefer real request path over a mismatched config
                $ggBase = $detected;
            }
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