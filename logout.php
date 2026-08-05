<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: /guitarghar/index.php');
    exit();
}

session_destroy();
header('Location: /guitarghar/index.php');
exit();
