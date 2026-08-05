<?php
session_start();
header('Content-Type: application/json');

include 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Please login first.'
    ]);
    exit();
}

$user_id = (int) $_SESSION['user_id'];

if ($_SERVER['REQUEST_METHOD'] !== 'POST' || !isset($_POST['build_id'])) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid request.'
    ]);
    exit();
}

$build_id = (int) $_POST['build_id'];

$sql  = 'DELETE FROM guitar_builds WHERE id = ? AND user_id = ?';
$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param($stmt, 'ii', $build_id, $user_id);

if (mysqli_stmt_execute($stmt)) {
    if (mysqli_stmt_affected_rows($stmt) > 0) {
        echo json_encode(['success' => true]);
    } else {
        echo json_encode([
            'success' => false,
            'message' => 'Design not found or permission denied.'
        ]);
    }
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Could not delete design. Please try again.'
    ]);
}
