<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
require_once 'connect.php';

$errors = [];
$success = false;

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    try {
        $username = trim($_POST['username']);
        $lastname = trim($_POST['lastname']);
        $email = trim($_POST['email']);
        $password = $_POST['password'];
        $confirm_password = $_POST['confirm_password'];

        if($username == 'Piotrek' || $username == 'piotrek') {
            $errors[] = 'Piotrek jest zbanowany ;)';
        }

    
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

   
        $check_stmt = $conn->prepare("SELECT * FROM users WHERE username = ? OR email = ?");
        if (!$check_stmt) {
            throw new Exception("Błąd przygotowania zapytania: " . $conn->error);
        }
        
        $check_stmt->bind_param("ss", $username, $email);
        $check_stmt->execute();
        $result = $check_stmt->get_result();

        if ($result->num_rows > 0) {
            $errors[] = "Użytkownik z tą nazwą lub adresem e-mail już istnieje.";
        }

        if (empty($errors)) {
        
            $default_avatar = file_get_contents('https://static.vecteezy.com/system/resources/previews/009/292/244/non_2x/default-avatar-icon-of-social-media-user-vector.jpg');
            
            // Hashowanie hasła
            $hashed_password = password_hash($password, PASSWORD_DEFAULT);
            
         
            $insert_stmt = $conn->prepare("INSERT INTO users (username, lastname, email, password, image) VALUES (?, ?, ?, ?, ?)");
            if (!$insert_stmt) {
                throw new Exception("Błąd przygotowania zapytania INSERT: " . $conn->error);
            }
            
            $insert_stmt->bind_param("sssss", $username, $lastname, $email, $hashed_password, $default_avatar);
            
            if (!$insert_stmt->execute()) {
                throw new Exception("Błąd wykonania zapytania INSERT: " . $insert_stmt->error);
            }
            
            if ($insert_stmt->affected_rows > 0) {
                $success = true;
                header("Location: login.php?registered=true");
                exit();
            } else {
                throw new Exception("Nie udało się dodać użytkownika");
            }
        }
    } catch (Exception $e) {
        $errors[] = "Błąd podczas rejestracji: " . $e->getMessage();
        error_log("Błąd rejestracji: " . $e->getMessage());
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
    
    <?php if (!empty($errors)): ?>
        <?php foreach ($errors as $error): ?>
            <div class="error"><?php echo htmlspecialchars($error); ?></div>
        <?php endforeach; ?>
    <?php endif; ?>

    <form method="POST" action="">
        <label for="username">Nazwa użytkownika:</label>
        <input type="text" id="username" name="username" required 
               value="<?php echo isset($_POST['username']) ? htmlspecialchars($_POST['username']) : ''; ?>">
        
        <label for="lastname">Nazwisko:</label>
        <input type="text" id="lastname" name="lastname" required
               value="<?php echo isset($_POST['lastname']) ? htmlspecialchars($_POST['lastname']) : ''; ?>">
        
        <label for="email">E-mail:</label>
        <input type="email" id="email" name="email" required
               value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>">
        
        <label for="password">Hasło:</label>
        <input type="password" id="password" name="password" required>
        
        <label for="confirm_password">Powtórz hasło:</label>
        <input type="password" id="confirm_password" name="confirm_password" required>
        
        <input type="submit" value="Zarejestruj">
    </form>
    <p>Masz już konto? <a href="login.php">Zaloguj się</a></p>
</body>
</html>
