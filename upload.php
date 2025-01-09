<?php
ini_set('display_errors', 1);
error_reporting(E_ALL);

// Ustawienie folderu, w którym będą przechowywane obrazy
$uploadDirectory = 'uploads/';

// Ustawienia, które umożliwiają upload plików
if (!file_exists($uploadDirectory)) {
    mkdir($uploadDirectory, 0777, true); // Tworzenie folderu, jeśli nie istnieje
}

// Sprawdzanie, czy plik został przesłany
if (isset($_FILES['image']) && $_FILES['image']['error'] === UPLOAD_ERR_OK) {
    $uploadedFile = $_FILES['image'];
    $fileName = basename($uploadedFile['name']);
    $fileTmpPath = $uploadedFile['tmp_name'];
    $fileType = mime_content_type($fileTmpPath);

    // Sprawdzanie typu pliku (możesz dodać więcej typów)
    if (strpos($fileType, 'image') === false) {
        echo json_encode(['success' => false, 'message' => 'Tylko obrazy są dozwolone']);
        exit;
    }

    // Tworzenie unikalnej nazwy dla pliku
    $newFileName = uniqid() . '_' . $fileName;
    $destinationPath = $uploadDirectory . $newFileName;

    // Przeniesienie pliku do folderu docelowego
    if (move_uploaded_file($fileTmpPath, $destinationPath)) {
        // Zwracamy URL do obrazu, który można wyświetlić na stronie
        echo json_encode(['success' => true, 'imageUrl' => $destinationPath]);
    } else {
        echo json_encode(['success' => false, 'message' => 'Błąd przesyłania pliku']);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Brak przesłanego pliku']);
}
?>
