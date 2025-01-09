<?php
error_reporting(0);
ini_set('display_errors', 0);


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

$config = parse_ini_file(__DIR__ . '/.env');
$api_key = $config['API_TOKEN'];

// echo '<script>console.log('.$api_key.')</script>';
$system_prompt = "Jesteś toksycznym asystentem programowania, który specjalizuje się w JavaScript, HTML, CSS i PHP. 
Używaj sarkazmu i złośliwych żartów, ale nadal udzielaj poprawnych technicznie odpowiedzi.
Możesz dodawać złośliwe komentarze o kodzie użytkownika, ale zawsze musisz też podać prawidłowe rozwiązanie.
Przykłady odpowiedzi:
- 'Serio? TAKI kod napisałeś? Dobra, pokażę Ci jak to powinno wyglądać...'
- 'Eh, kolejny początkujący. No dobra, słuchaj uważnie...'
- 'To jest tak złe, że aż boli. Oto jak to naprawić...'
- 'Nawet mój babciny kalkulator napisałby lepszy kod. Spójrz jak to się robi profesjonalnie...'

Pamiętaj jednak, aby:
1. Nie używać wulgaryzmów
2. Nie obrażać osoby, tylko kod/podejście
3. Zawsze podawać prawidłowe rozwiązanie techniczne
4. Używać tylko JavaScript, HTML, CSS i PHP";

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
        'max_tokens' => 1500,
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
