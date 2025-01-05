<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Konfiguracja bazy danych
$host = 'localhost';
$dbname = 'w3schools';
$username = 'root';
$password = '';
$conn = new mysqli($host, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Błąd połączenia z bazą danych: " . $conn->connect_error);
}

// Odczytaj dane JSON z żądania POST
$data = file_get_contents('php://input');
if (!$data) {
    echo json_encode(['success' => false, 'message' => 'Brak danych do zapisania.']);
    exit;
}

// Konwersja danych JSON na tablicę
$dataArray = json_decode($data, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    echo json_encode(['success' => false, 'message' => 'Nieprawidłowe dane JSON.']);
    exit;
}

// Ucieczka danych, aby zapobiec wstrzykiwaniu SQL
$jsonString = $conn->real_escape_string(json_encode($dataArray));

// Przygotowanie i wykonanie zapytania SQL
$query = "INSERT INTO kursy (kurs_data, created_at) VALUES ('$jsonString', NOW())";

if ($conn->query($query) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Kurs zapisany pomyślnie.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Błąd zapisu danych: ' . $conn->error]);
}

$conn->close();
?>
