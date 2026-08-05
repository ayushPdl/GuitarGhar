<?php
require_once __DIR__ . '/includes/paths.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: ' . url('index.php'));
    exit();
}

session_destroy();
header('Location: ' . url('index.php'));
exit();