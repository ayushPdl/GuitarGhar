<?php
session_start();
header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['error' => 'Please log in to use the recommender.']);
    exit;
}

$configPath = __DIR__ . '/includes/config.php';
$apiKey = '';

if (is_file($configPath)) {
    $config = include $configPath;
    if (is_array($config) && !empty($config['openrouter_api_key'])) {
        $apiKey = $config['openrouter_api_key'];
    }
}

if ($apiKey === '') {
    $apiKey = getenv('OPENROUTER_API_KEY') ?: '';
}

if ($apiKey === '' || strpos($apiKey, 'YOUR_KEY') !== false) {
    echo json_encode(['error' => 'API key is not configured. Copy includes/config.example.php to includes/config.php and add your key.']);
    exit;
}

$skill  = isset($_POST['skill']) ? trim($_POST['skill']) : '';
$genre  = isset($_POST['genre']) ? trim($_POST['genre']) : '';
$type   = isset($_POST['type']) ? trim($_POST['type']) : '';
$budget = isset($_POST['budget']) ? trim($_POST['budget']) : '';
$extra  = isset($_POST['extra']) ? trim($_POST['extra']) : '';

if ($skill === '' || $genre === '' || $type === '' || $budget === '') {
    echo json_encode(['error' => 'Please fill all fields']);
    exit;
}

$prompt = "Recommend a guitar for someone who is $skill level, likes $genre music, wants $type guitar, budget $budget NPR. Extra: $extra. Give a short, helpful answer in 2-3 sentences.";

$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
curl_setopt($ch, CURLOPT_TIMEOUT, 45);
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    'Authorization: Bearer ' . $apiKey,
    'Content-Type: application/json'
]);
curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
    'model' => 'openrouter/free',
    'messages' => [['role' => 'user', 'content' => $prompt]]
]));

$response = curl_exec($ch);
$httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

if ($response === false) {
    echo json_encode(['error' => 'Could not reach the AI service. Please try again.']);
    exit;
}

if ($httpCode == 200) {
    $result = json_decode($response, true);
    $aiResponse = $result['choices'][0]['message']['content'] ?? null;
    if (!$aiResponse) {
        echo json_encode(['error' => 'Sorry, no recommendation right now.']);
        exit;
    }

    if (is_file(__DIR__ . '/includes/db.php')) {
        include __DIR__ . '/includes/db.php';
        if (isset($conn) && $conn) {
            $uid = (int) $_SESSION['user_id'];
            $ins = mysqli_prepare(
                $conn,
                'INSERT INTO recommendations (user_id, skill_level, genre, guitar_type, budget, extra_note, result) VALUES (?, ?, ?, ?, ?, ?, ?)'
            );
            if ($ins) {
                mysqli_stmt_bind_param($ins, 'issssss', $uid, $skill, $genre, $type, $budget, $extra, $aiResponse);
                @mysqli_stmt_execute($ins);
                mysqli_stmt_close($ins);
            }
        }
    }

    echo json_encode(['result' => $aiResponse]);
} else {
    echo json_encode(['error' => 'AI is busy. Please try again.']);
}
