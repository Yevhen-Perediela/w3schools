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

// Pobranie danych z JSON-a
$title = isset($dataArray['title']) ? $conn->real_escape_string($dataArray['title']) : null;
$courseData = isset($dataArray['courseData']) ? $conn->real_escape_string(json_encode($dataArray['courseData'])) : null;

if (!$title || !$courseData) {
    echo json_encode(['success' => false, 'message' => 'Brak wymaganych danych.']);
    exit;
}

// Przygotowanie i wykonanie zapytania SQL
$query = "INSERT INTO kursy (title, kurs_data, created_at) VALUES ('$title', '$courseData', NOW())";

if ($conn->query($query) === TRUE) {
    echo json_encode(['success' => true, 'message' => 'Kurs zapisany pomyślnie.']);
} else {
    echo json_encode(['success' => false, 'message' => 'Błąd zapisu danych: ' . $conn->error]);
}

$conn->close();
?>
