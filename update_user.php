<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Nie jesteś zalogowany']);
    exit();
}

if (isset($_POST['new_username'])) {
    $new_username = trim($_POST['new_username']);
    $user_id = $_SESSION['user_id'];
    
  
    if (preg_match('/\s/', $new_username)) {
        echo json_encode(['success' => false, 'error' => 'Nazwa użytkownika nie może zawierać spacji']);
        exit();
    }
    
  
    $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
    $stmt->bind_param("si", $new_username, $user_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows > 0) {
        echo json_encode(['success' => false, 'error' => 'Ta nazwa użytkownika jest już zajęta']);
        exit();
    }
    
  
    $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
    $stmt->bind_param("si", $new_username, $user_id);
    
    if ($stmt->execute()) {
        $_SESSION['username'] = $new_username;
        echo json_encode([
            'success' => true, 
            'new_username' => $new_username
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'error' => 'Błąd podczas aktualizacji'
        ]);
    }
} 