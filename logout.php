<?php
session_start();
session_destroy();
header("Location: /Demo/index.php");
exit();
?>