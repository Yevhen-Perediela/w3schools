<?php
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    
    $conn = new mysqli('localhost', 'db_user', 'db_password', 'w3schools');

   
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    
    $sql = "SELECT * FROM users WHERE username = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

 
    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
      
        if (password_verify($password, $user['password'])) {
           
            session_start();
            $_SESSION['user_id'] = $user['id'];
            header("Location: dashboard.php"); 
            exit();
        } else {
            echo "Nieprawidłowe hasło.";
        }
    } else {
        echo "Użytkownik nie istnieje.";
    }

    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
</head>
<body>
    <h2>Logowanie</h2>
    <form method="POST" action="">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <input type="submit" value="Zaloguj">
    </form>
</body>
</html>