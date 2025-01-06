<?php
session_start();
require_once 'connect.php';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];
    
    $stmt = $conn->prepare("SELECT * FROM users WHERE username = ?");
    $stmt->bind_param("s", $username);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $user = $result->fetch_assoc();
        
        if (password_verify($password, $user['password'])) {
           
            $_SESSION['user_id'] = $user['id'];
         
            header("Location: index.php");
            exit();
        } else {
            $error = "Nieprawidłowe hasło.";
        }
    } else {
        $error = "Użytkownik nie istnieje.";
    }
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
    
    <?php if (isset($error)): ?>
        <p class="error"><?php echo htmlspecialchars($error); ?></p>
    <?php endif; ?>

    <form method="POST" action="index.php">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required>
        
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>
        
        <input type="submit" value="Zaloguj">
    </form>
    <p>Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
</body>
</html>
