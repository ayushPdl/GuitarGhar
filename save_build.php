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

$allowed_shapes = ['strat', 'lespaul', 'sg', 'acoustic'];
$allowed_body   = ['Alder', 'Mahogany', 'Basswood', 'Ash', 'Maple', 'Spruce'];
$allowed_neck   = ['Maple', 'Mahogany', 'Rosewood'];
$allowed_fb     = ['Rosewood', 'Maple', 'Ebony'];
$allowed_pu     = ['SSS', 'HSS', 'HH', 'P90', 'None'];
$allowed_bridge = ['Synchronized Tremolo', 'Hardtail', 'Tune-O-Matic', 'Floyd Rose', 'Acoustic Bridge'];
$allowed_hw     = ['Chrome', 'Gold', 'Black', 'Nickel'];

$shape       = isset($_POST['shape']) ? trim($_POST['shape']) : '';
$color       = isset($_POST['color']) ? trim($_POST['color']) : '';
$body_wood   = isset($_POST['body_wood']) ? trim($_POST['body_wood']) : '';
$neck_wood   = isset($_POST['neck_wood']) ? trim($_POST['neck_wood']) : '';
$fingerboard = isset($_POST['fingerboard']) ? trim($_POST['fingerboard']) : '';
$pickups     = isset($_POST['pickups']) ? trim($_POST['pickups']) : '';
$bridge      = isset($_POST['bridge']) ? trim($_POST['bridge']) : '';
$hardware    = isset($_POST['hardware']) ? trim($_POST['hardware']) : '';

if (
    !in_array($shape, $allowed_shapes, true) ||
    !in_array($body_wood, $allowed_body, true) ||
    !in_array($neck_wood, $allowed_neck, true) ||
    !in_array($fingerboard, $allowed_fb, true) ||
    !in_array($pickups, $allowed_pu, true) ||
    !in_array($bridge, $allowed_bridge, true) ||
    !in_array($hardware, $allowed_hw, true) ||
    !preg_match('/^#[0-9A-Fa-f]{6}$/', $color)
) {
    echo json_encode([
        'success' => false,
        'message' => 'Invalid build options. Please check your selections.'
    ]);
    exit();
}

$sql = 'INSERT INTO guitar_builds
(user_id, shape, color, body_wood, neck_wood, fingerboard, pickups, bridge, hardware)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)';

$stmt = mysqli_prepare($conn, $sql);
mysqli_stmt_bind_param(
    $stmt,
    'issssssss',
    $user_id,
    $shape,
    $color,
    $body_wood,
    $neck_wood,
    $fingerboard,
    $pickups,
    $bridge,
    $hardware
);

if (mysqli_stmt_execute($stmt)) {
    echo json_encode(['success' => true]);
} else {
    echo json_encode([
        'success' => false,
        'message' => 'Could not save your build. Please try again.'
    ]);
}
