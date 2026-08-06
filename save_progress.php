<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Not logged in']);
    exit;
}

include 'includes/db.php';

$user_id   = (int) $_SESSION['user_id'];
$lesson_id = isset($_POST['lesson_id']) ? trim($_POST['lesson_id']) : '';
$completed = isset($_POST['completed']) ? (int) $_POST['completed'] : 0;

if ($lesson_id === '' || !preg_match('/^[a-z]{3}-\d{2}$/', $lesson_id)) {
    echo json_encode(['success' => false, 'error' => 'Invalid lesson']);
    exit;
}

$check = $conn->prepare('SELECT id FROM lessons WHERE lesson_id = ? LIMIT 1');
$check->bind_param('s', $lesson_id);
$check->execute();
$checkResult = $check->get_result();
if (!$checkResult || $checkResult->num_rows === 0) {
    echo json_encode(['success' => false, 'error' => 'Lesson not found']);
    exit;
}
$check->close();

if ($completed === 1) {
    $sql = 'INSERT INTO lesson_progress (user_id, lesson_id, completed)
            VALUES (?, ?, 1)
            ON DUPLICATE KEY UPDATE completed = 1, completed_at = CURRENT_TIMESTAMP';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $user_id, $lesson_id);
} else {
    $sql = 'DELETE FROM lesson_progress WHERE user_id = ? AND lesson_id = ?';
    $stmt = $conn->prepare($sql);
    $stmt->bind_param('is', $user_id, $lesson_id);
}

if ($stmt->execute()) {
    echo json_encode(['success' => true, 'completed' => $completed]);
} else {
    echo json_encode(['success' => false, 'error' => 'Could not save progress']);
}

$stmt->close();
$conn->close();
