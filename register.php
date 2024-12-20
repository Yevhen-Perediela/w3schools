<?php

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = trim($_POST['username']);
    $lastname = trim($_POST['lastname']);
    $email = trim($_POST['email']);
    $password = $_POST['password'];
    $confirm_password = $_POST['confirm_password'];


    $errors = [];

   
    if (strlen($password) < 8 || !preg_match('/[A-Z]/', $password) || preg_match('/\s/', $password)) {
        $errors[] = "Hasło musi mieć co najmniej 8 znaków, jedną dużą literę i nie może zawierać spacji.";
    }


    if ($password !== $confirm_password) {
        $errors[] = "Hasła nie są identyczne.";
    }
    if (preg_match('/\s/', $username)) {
        $errors[] = "Nazwa użytkownika nie może zawierać spacji.";
    }

    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errors[] = "Nieprawidłowy adres e-mail.";
    }


    $conn = new mysqli('localhost', 'db_user', 'db_password', 'w3schools');

 
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

 
    $sql = "SELECT * FROM users WHERE username = ? OR email = ? LIMIT 1";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Użytkownik z tą nazwą lub adresem e-mail już istnieje.";
    }
    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $sql = "INSERT INTO users (username, lastname, email, password) VALUES (?, ?, ?, ?)";
        $stmt = $conn->prepare($sql);
        $stmt->bind_param("ssss", $username, $lastname, $email, $hashed_password);
        $stmt->execute();

        echo "Rejestracja zakończona sukcesem!";
        $stmt->close();
        $conn->close();
        exit();
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
    <title>Rejestracja</title>
</head>
<body>
    <h2>Rejestracja</h2>
    <form method="POST" action="">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required>
        <br>
        <label for="lastname">Nazwisko:</label>
        <input type="text" id="lastname" name="lastname" required>
        <br>
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        <br>
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>
        <br>
        <label for="confirm_password">Powtórz hasło:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        <br>
        <input type="submit" value="Zarejestruj">
    </form>
</body>
</html>