<?php
require_once 'connect.php';

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

    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
    $stmt->bind_param("ss", $username, $email);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $errors[] = "Użytkownik z tą nazwą lub adresem e-mail już istnieje.";
    }

    if (empty($errors)) {
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("INSERT INTO users (username, lastname, email, password) VALUES (?, ?, ?, ?)");
        $stmt->bind_param("ssss", $username, $lastname, $email, $hashed_password);
        
        if ($stmt->execute()) {
            header("Location: login.php");
            exit();
        } else {
            $errors[] = "Błąd podczas rejestracji: " . $conn->error;
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rejestracja</title>
    <style>

    </style>
</head>
<body>
    <h2>Rejestracja</h2>
    
    <?php
    if (isset($errors)) {
        foreach ($errors as $error) {
            echo "<p class='error'>" . htmlspecialchars($error) . "</p>";
        }
    }
    ?>

    <form method="POST" action="">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="lastname">Nazwisko:</label>
        <input type="text" id="lastname" name="lastname" required>
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required>
        
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="confirm_password">Powtórz hasło:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        
        <input type="submit" value="Zarejestruj">
    </form>
    <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
</body>
</html>
