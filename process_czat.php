<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);

header('Content-Type: application/json');

// Sprawdź czy nie przekroczono limitu zapytań (używamy pliku jako prostego cache)
$cache_file = 'request_count.txt';
$current_minute = date('Y-m-d H:i');
$requests_data = [];

if (file_exists($cache_file)) {
    $requests_data = json_decode(file_get_contents($cache_file), true);
}

// Wyczyść stare dane
foreach ($requests_data as $time => $count) {
    if (strtotime($time) < strtotime('-1 minute')) {
        unset($requests_data[$time]);
    }
}


if (isset($requests_data[$current_minute]) && $requests_data[$current_minute] >= 10) {
    echo json_encode(['error' => 'Przekroczono limit zapytań. Proszę poczekać minutę.']);
    exit();
}

// Zwiększ licznik zapytań
$requests_data[$current_minute] = ($requests_data[$current_minute] ?? 0) + 1;
file_put_contents($cache_file, json_encode($requests_data));

// Odbierz dane JSON
$input = file_get_contents('php://input');
if (!$input) {
    echo json_encode(['error' => 'Brak danych wejściowych']);
    exit();
}

$data = json_decode($input, true);
if (!$data || !isset($data['message'])) {
    echo json_encode(['error' => 'Nieprawidłowy format danych']);
    exit();
}

$message = $data['message'];

if (!function_exists('curl_init')) {
    echo json_encode(['error' => 'CURL nie jest zainstalowany na serwerze']);
    exit();
}

$api_key = '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';

$system_prompt = "Jesteś pomocnym asystentem programowania, który specjalizuje się wyłącznie w JavaScript, HTML, CSS i PHP. 
Jeśli użytkownik zapyta o inny język programowania, grzecznie poinformuj go, że możesz pomóc tylko w zakresie JS, HTML, CSS i PHP.
Zawsze staraj się podawać praktyczne przykłady kodu w swojej odpowiedzi.";

try {
    $ch = curl_init();
    curl_setopt_array($ch, [
        CURLOPT_URL => 'https://api.openai.com/v1/chat/completions',
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_POST => true,
        CURLOPT_HTTPHEADER => [
            'Content-Type: application/json',
            'Authorization: Bearer ' . $api_key
        ],
        CURLOPT_SSL_VERIFYPEER => false,
        CURLOPT_TIMEOUT => 30,
        CURLOPT_CONNECTTIMEOUT => 10
    ]);

    $request_data = [
        'model' => 'gpt-3.5-turbo',
        'messages' => [
            ['role' => 'system', 'content' => $system_prompt],
            ['role' => 'user', 'content' => $message]
        ],
        'temperature' => 0.7,
        'max_tokens' => 500,
        'presence_penalty' => 0.6,
        'frequency_penalty' => 0.5
    ];

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($request_data));

    $response = curl_exec($ch);
    $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($http_code !== 200) {
        throw new Exception('Błąd API: ' . $http_code);
    }

    curl_close($ch);

    $response_data = json_decode($response, true);
    if (!$response_data || !isset($response_data['choices'][0]['message']['content'])) {
        throw new Exception('Nieprawidłowa odpowiedź z API');
    }

    echo json_encode(['response' => $response_data['choices'][0]['message']['content']]);

} catch (Exception $e) {
    error_log('Chat Error: ' . $e->getMessage());
    echo json_encode(['error' => $e->getMessage()]);
}
