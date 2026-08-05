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

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["build_id"])) {
    $build_id = intval($_POST["build_id"]);

    // Ensure the build belongs to the logged-in user
    $sql = "DELETE FROM guitar_builds WHERE id = ? AND user_id = ?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ii", $build_id, $user_id);

    if (mysqli_stmt_execute($stmt)) {
        if (mysqli_stmt_affected_rows($stmt) > 0) {
            echo json_encode(["success" => true]);
        } else {
            echo json_encode([
                "success" => false,
                "message" => "Design not found or permission denied."
            ]);
        }
    } else {
        echo json_encode([
            "success" => false,
            "message" => mysqli_error($conn)
        ]);
    }
} else {
    echo json_encode([
        "success" => false,
        "message" => "Invalid request."
    ]);
}

