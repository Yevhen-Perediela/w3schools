<?php
session_start();
error_reporting(E_ALL);
ini_set('display_errors', 1);
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
            $_SESSION['username'] = $user['username'];
            $_SESSION['lastname'] = $user['lastname'];
            $_SESSION['email'] = $user['email'];
            
          
            header("Location: index.php");
            exit();
        } else {
            $error = "Nieprawidłowe hasło.";
        }
    } else {
        $error = "Użytkownik nie istnieje.";
    }
}
include_once 'includes/header.php'
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logowanie</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="./styles/login.css">
    <link rel="stylesheet" href="./styles/stars.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
    
    <div class="stars" id="stars"></div>
    
    <main class="main-content">
        <div class="login-container">
            <h2>Logowanie</h2>
            
            <?php if (isset($error)): ?>
                <div class="error"><?php echo htmlspecialchars($error); ?></div>
            <?php endif; ?>

            <form method="POST" action="">
                <div class="form-group">
                    <label for="username">Nazwa użytkownika:</label>
                    <input type="text" id="username" name="username" required 
                           placeholder="Wprowadź nazwę użytkownika">
                </div>
                
                <div class="form-group">
                    <label for="password">Hasło:</label>
                    <input type="password" id="password" name="password" required
                           placeholder="Wprowadź hasło">
                </div>
                
                <input type="submit" value="Zaloguj" class="full-width">
            </form>
            <p class="register-link">Nie masz konta? <a href="register.php">Zarejestruj się</a></p>
        </div>
    </main>

    <?php include './includes/footer.php'; ?>

    <script src="./js/stars.js"></script>
    <script>
        createStars();
    </script>
</body>
</html>
