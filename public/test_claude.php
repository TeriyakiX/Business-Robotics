<?php

$apiKey = 'Тут апи ключ';

$models = [
    'claude-haiku-4-5-20251001',
    'claude-sonnet-4-20250514',
    'claude-3-haiku-20240307',
];

foreach ($models as $model) {
    $ch = curl_init('http://72.56.25.171/v1/messages');
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode([
        'model' => $model,
        'max_tokens' => 10,
        'messages' => [['role' => 'user', 'content' => 'hi']]
    ]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'x-api-key: ' . $apiKey,
        'anthropic-version: 2023-06-01',
        'content-type: application/json',
    ]);

    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    $result = json_decode($response, true);

    echo '<p><b>' . $model . '</b>: HTTP ' . $httpCode;
    echo $httpCode === 200 ? ' ✅' : ' ❌ ' . ($result['error']['message'] ?? '');
    echo '</p>';
}
