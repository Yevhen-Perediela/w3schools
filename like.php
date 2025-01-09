<?php
session_start();
require_once 'connect.php';

header('Content-Type: application/json');

if (!isset($_SESSION['user_id'])) {
    echo json_encode(['success' => false, 'error' => 'Nie jesteś zalogowany']);
    exit();
}

// Odbierz dane JSON
$input = json_decode(file_get_contents('php://input'), true);

if (isset($input['course_id'])) {
    $course_id = (int)$input['course_id'];
    $user_id = $_SESSION['user_id'];
    
   
    $stmt = $conn->prepare("SELECT id FROM kursy WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    if ($stmt->get_result()->num_rows === 0) {
        echo json_encode(['success' => false, 'error' => 'Kurs nie istnieje']);
        exit();
    }
    
    
    $stmt = $conn->prepare("SELECT id FROM course_likes WHERE course_id = ? AND user_id = ?");
    $stmt->bind_param("ii", $course_id, $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    
    if ($result->num_rows > 0) {
       
        $stmt = $conn->prepare("DELETE FROM course_likes WHERE course_id = ? AND user_id = ?");
        $stmt->bind_param("ii", $course_id, $user_id);
        $stmt->execute();
        $action = 'unliked';
    } else {
        
        $stmt = $conn->prepare("INSERT INTO course_likes (course_id, user_id) VALUES (?, ?)");
        $stmt->bind_param("ii", $course_id, $user_id);
        $stmt->execute();
        $action = 'liked';
    }
    
   
    $stmt = $conn->prepare("SELECT COUNT(*) as likes FROM course_likes WHERE course_id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
    $likes_count = $stmt->get_result()->fetch_assoc()['likes'];
    
    echo json_encode([
        'success' => true,
        'action' => $action,
        'likes' => $likes_count
    ]);
} else {
    echo json_encode(['success' => false, 'error' => 'Brak ID kursu']);
}
