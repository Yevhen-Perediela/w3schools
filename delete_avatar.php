<?php
session_start();
require_once 'connect.php';

if (!isset($_SESSION['user_id'])) {
    header('Location: login.php');
    exit();
}

try {
    $stmt = $conn->prepare("UPDATE users SET image = NULL WHERE id = ?");
    $stmt->bind_param("i", $_SESSION['user_id']);
    
    if ($stmt->execute()) {
        $_SESSION['success_message'] = "Zdjęcie profilowe zostało usunięte.";
    } else {
        $_SESSION['error_message'] = "Wystąpił błąd podczas usuwania zdjęcia.";
    }
} catch (Exception $e) {
    $_SESSION['error_message'] = "Wystąpił błąd podczas usuwania zdjęcia.";
}

header('Location: user_panel.php');
exit(); 