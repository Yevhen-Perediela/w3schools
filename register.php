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
        $default_avatar = file_get_contents('https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg');
        
        $hashed_password = password_hash($password, PASSWORD_DEFAULT);
        
        $stmt = $conn->prepare("INSERT INTO users (username, lastname, email, password, image) VALUES (?, ?, ?, ?, ?)");
        $stmt->bind_param("sssss", $username, $lastname, $email, $hashed_password, $default_avatar);
        
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
        body {
            font-family: Arial, sans-serif;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
        }
        .error {
            color: red;
            margin-bottom: 10px;
        }
        input {
            width: 100%;
            padding: 8px;
            margin: 5px 0 15px 0;
            box-sizing: border-box;
        }
        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            border: none;
            cursor: pointer;
        }
        input[type="submit"]:hover {
            background-color: #45a049;
        }
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

    <form method="POST" action="login.php">
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
