<?php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

if (isset($_SESSION['username']) && $_SESSION['username'] === 'admin') {
    header("Location: index.php");
    exit();
}

require_once 'connect.php';

$user_id = $_SESSION['user_id'];
$stmt = $conn->prepare("SELECT username, lastname, email, image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user_data = $result->fetch_assoc();

// Używamy danych z bazy
$username = $user_data['username'];
$lastname = $user_data['lastname'];
$email = $user_data['email'];

// Aktualizujemy sesję
$_SESSION['username'] = $username;
$_SESSION['lastname'] = $lastname;
$_SESSION['email'] = $email;

$message = '';
$errors = [];
var_dump($username);

$stmt = $conn->prepare("SELECT username, lastname, email, image FROM users WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (isset($_POST['update_username'])) {
    $new_username = trim($_POST['new_username']);
    
    if (preg_match('/\s/', $new_username)) {
        $errors[] = "Nazwa użytkownika nie może zawierać spacji.";
    } else {
        $stmt = $conn->prepare("SELECT id FROM users WHERE username = ? AND id != ?");
        $stmt->bind_param("si", $new_username, $user_id);
        $stmt->execute();
        if ($stmt->get_result()->num_rows > 0) {
            $errors[] = "Ta nazwa użytkownika jest już zajęta.";
        } else {
            $stmt = $conn->prepare("UPDATE users SET username = ? WHERE id = ?");
            $stmt->bind_param("si", $new_username, $user_id);
            $stmt->execute();
            $message = "Nazwa użytkownika została zaktualizowana.";
            $user['username'] = $new_username;
        }
    }
}

if (isset($_POST['update_password'])) {
    $old_password = $_POST['old_password'];
    $new_password = $_POST['new_password'];
    $confirm_password = $_POST['confirm_password'];

    $stmt = $conn->prepare("SELECT password FROM users WHERE id = ?");
    $stmt->bind_param("i", $user_id);
    $stmt->execute();
    $result = $stmt->get_result();
    $current_password = $result->fetch_assoc()['password'];

    if (!password_verify($old_password, $current_password)) {
        $errors[] = "Aktualne hasło jest nieprawidłowe.";
    } elseif ($new_password !== $confirm_password) {
        $errors[] = "Nowe hasła nie są identyczne.";
    } elseif (strlen($new_password) < 8 || !preg_match('/[A-Z]/', $new_password) || preg_match('/\s/', $new_password)) {
        $errors[] = "Nowe hasło musi mieć co najmniej 8 znaków, jedną dużą literę i nie może zawierać spacji.";
    } else {
        $hashed_password = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $conn->prepare("UPDATE users SET password = ? WHERE id = ?");
        $stmt->bind_param("si", $hashed_password, $user_id);
        $stmt->execute();
        $message = "Hasło zostało zmienione.";
    }
}

if (isset($_FILES['profile_image']) && $_FILES['profile_image']['error'] !== 4) {
    $file = $_FILES['profile_image'];
    $allowed_types = ['image/jpeg', 'image/png', 'image/gif'];
    $max_size = 5 * 1024 * 1024; // 5MB

    if (!in_array($file['type'], $allowed_types)) {
        $errors[] = "Dozwolone są tylko pliki JPG, PNG i GIF.";
    } elseif ($file['size'] > $max_size) {
        $errors[] = "Maksymalny rozmiar pliku to 5MB.";
    } elseif ($file['error'] === 0) {
        $image_data = file_get_contents($file['tmp_name']);
        $stmt = $conn->prepare("UPDATE users SET image = ? WHERE id = ?");
        $stmt->bind_param("si", $image_data, $user_id);
        if ($stmt->execute()) {
            $message = "Zdjęcie profilowe zostało zaktualizowane.";
            $user['image'] = $image_data;
        } else {
            $errors[] = "Wystąpił błąd podczas aktualizacji zdjęcia.";
        }
    }
}
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel użytkownika</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="./styles/user_panel.css">
    <link rel="stylesheet" href="./styles/stars.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
    
    <div class="stars" id="stars"></div>
    
    <main class="main-content">
        <div class="user-panel-container">
            <h2>Panel użytkownika</h2>
            
            <?php if (!empty($errors)): ?>
                <?php foreach ($errors as $error): ?>
                    <p class="error"><?php echo htmlspecialchars($error); ?></p>
                <?php endforeach; ?>
            <?php endif; ?>

            <?php if ($message): ?>
                <p class="success"><?php echo htmlspecialchars($message); ?></p>
            <?php endif; ?>

            <div class="form-section">
                <h3>Twoje dane</h3>
                <div class="user-info">
                    <p><strong>Nazwa użytkownika:</strong> <?php echo htmlspecialchars($username); ?></p>
                    <p><strong>Nazwisko:</strong> <?php echo htmlspecialchars($lastname); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                </div>
            </div>

            <div class="form-section">
                <h3>Zmiana nazwy użytkownika</h3>
                <form method="POST">
                    <label for="new_username">Nowa nazwa użytkownika:</label>
                    <input type="text" id="new_username" name="new_username" 
                           value="<?php echo htmlspecialchars($username); ?>" required>
                    <input type="submit" name="update_username" value="Zmień nazwę użytkownika">
                </form>
            </div>

            <div class="form-section">
                <h3>Zdjęcie profilowe</h3>
                <div class="avatar-section">
                    <?php
                    $stmt = $conn->prepare("SELECT image FROM users WHERE id = ?");
                    $stmt->bind_param("i", $_SESSION['user_id']);
                    $stmt->execute();
                    $result = $stmt->get_result();
                    $user = $result->fetch_assoc();
                    
                    if ($user && $user['image']) {
                        $imageData = base64_encode($user['image']);
                        echo '<img src="data:image/jpeg;base64,'.$imageData.'" alt="Avatar" class="avatar-preview">';
                        echo '<form action="delete_avatar.php" method="POST" class="delete-avatar-form">';
                        echo '<button type="submit" class="delete-avatar-btn">Usuń zdjęcie profilowe</button>';
                        echo '</form>';
                    } else {
                        echo '<img src="assets/img/user.png" alt="Default Avatar" class="avatar-preview">';
                    }
                    ?>
                </div>
                <form method="POST" enctype="multipart/form-data">
                    <input type="file" name="profile_image" accept="image/*" required>
                    <input type="submit" value="Zmień zdjęcie">
                </form>
            </div>

            <div class="form-section">
                <h3>Zmiana hasła</h3>
                <form method="POST">
                    <label for="old_password">Aktualne hasło:</label>
                    <input type="password" id="old_password" name="old_password" required
                           placeholder="Wprowadź aktualne hasło">
                    
                    <label for="new_password">Nowe hasło:</label>
                    <input type="password" id="new_password" name="new_password" required
                           placeholder="Wprowadź nowe hasło">
                    
                    <label for="confirm_password">Powtórz nowe hasło:</label>
                    <input type="password" id="confirm_password" name="confirm_password" required
                           placeholder="Powtórz nowe hasło">
                    
                    <input type="submit" name="update_password" value="Zmień hasło">
                </form>
            </div>

            <a href="index.php" class="back-button">
                Powrót do strony głównej
            </a>
        </div>
    </main>

    <script src="./js/stars.js"></script>
    <script>
        createStars();
    </script>
</body>
</html>
