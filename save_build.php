<?php
session_start();
include "includes/db.php";

header("Content-Type: application/json");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "success" => false,
        "message" => "Please login first."
    ]);
    exit();
}

$user_id = $_SESSION["user_id"];

$shape       = $_POST["shape"];
$color       = $_POST["color"];
$body_wood   = $_POST["body_wood"];
$neck_wood   = $_POST["neck_wood"];
$fingerboard = $_POST["fingerboard"];
$pickups     = $_POST["pickups"];
$bridge      = $_POST["bridge"];
$hardware    = $_POST["hardware"];

$sql = "INSERT INTO guitar_builds
(user_id, shape, color, body_wood, neck_wood, fingerboard, pickups, bridge, hardware)
VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?)";

$stmt = mysqli_prepare($conn, $sql);

mysqli_stmt_bind_param(
    $stmt,
    "issssssss",
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
    echo json_encode(["success" => true]);
} else {
    echo json_encode([
        "success" => false,
        "message" => mysqli_error($conn)
    ]);
}