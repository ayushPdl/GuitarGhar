<?php
session_start();
header('Content-Type: application/json');

// Your OpenRouter API key - PUT YOUR REAL KEY HERE
$apiKey = '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'; // CHANGE THIS TO YOUR REAL KEY

// Get the form data
$skill = $_POST['skill'] ?? '';
$genre = $_POST['genre'] ?? '';
$type = $_POST['type'] ?? '';
$budget = $_POST['budget'] ?? '';
$extra = $_POST['extra'] ?? '';

// Make sure all fields are filled
if (empty($skill) || empty($genre) || empty($type) || empty($budget)) {
    echo json_encode(['error' => 'Please fill all fields']);
    exit;
}

// Simple prompt for the AI
$prompt = "Recommend a guitar for someone who is $skill level, likes $genre music, wants $type guitar, budget $budget NPR. Extra: $extra. Give a short, helpful answer in 2-3 sentences.";

// Call OpenRouter API
$ch = curl_init('https://openrouter.ai/api/v1/chat/completions');
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
curl_setopt($ch, CURLOPT_POST, true);
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

// Send response back
if ($httpCode == 200) {
    $result = json_decode($response, true);
    $aiResponse = $result['choices'][0]['message']['content'] ?? 'Sorry, no recommendation right now.';
    echo json_encode(['result' => $aiResponse]);
} else {
    echo json_encode(['error' => 'AI is busy. Please try again.']);
}
?>