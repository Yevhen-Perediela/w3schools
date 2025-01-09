<?php
// Plik: kontakt.php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// 1. Obsługa wysłania formularza
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name    = trim($_POST['name'] ?? '');
    $email   = trim($_POST['email'] ?? '');
    $message = trim($_POST['message'] ?? '');
    // NOWE: pobranie wartości z selecta (jeśli nic nie wpadnie, przyjmij 'Inne')
    $topic   = trim($_POST['topic'] ?? 'Inne');

    if ($name === '' || $email === '' || $message === '') {
        $error = "Wypełnij wszystkie pola!";
    } else {
        // Połączenie z bazą (PDO) — dostosuj do swoich danych
        $host = 'localhost';
        $db   = 'w3schools';
        $user = 'root';
        $pass = '';

        try {
            $dsn = "mysql:host=$host;dbname=$db;charset=utf8";
            $pdo = new PDO($dsn, $user, $pass, [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
            ]);

            // INSERT do tabeli contact_messages, pamiętaj o kolumnie `topic`
            $sql = "INSERT INTO contact (name, email, message, topic) 
                    VALUES (:name, :email, :message, :topic)";
            $stmt = $pdo->prepare($sql);
            $stmt->bindValue(':name',    $name,    PDO::PARAM_STR);
            $stmt->bindValue(':email',   $email,   PDO::PARAM_STR);
            $stmt->bindValue(':message', $message, PDO::PARAM_STR);
            $stmt->bindValue(':topic',   $topic,   PDO::PARAM_STR);
            $stmt->execute();

            $success = "Dziękujemy za kontakt! Twoja wiadomość została zapisana.";
        } catch (PDOException $e) {
            $error = "Błąd przy zapisie do bazy danych: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8" />
    <title>Formularz kontaktowy</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/contact.css">
</head>
<body class="contact-page">
    <?php include 'includes/header.php';?>
    <div id="stars" class="stars"></div>
    <div class="main-content">
        <div id="odstep"></div>
        <div class="contact-container">

            <!-- Komunikaty (błąd lub sukces) -->
            <?php if (isset($error)): ?>
                <div class="error">
                    <?= htmlspecialchars($error); ?>
                </div>
            <?php endif; ?>

            <?php if (isset($success)): ?>
                <div class="success">
                    <?= htmlspecialchars($success); ?>
                </div>
            <?php endif; ?>

            <!-- Formularz kontaktowy -->
            <form method="POST" action="">
                <label for="name">Imię:</label>
                <input type="text" id="name" name="name" 
                       placeholder="Twoje imię"
                       value="<?= isset($name) ? htmlspecialchars($name) : '' ?>">

                <label for="email">Email:</label>
                <input type="email" id="email" name="email" 
                       placeholder="Twój email"
                       value="<?= isset($email) ? htmlspecialchars($email) : '' ?>">

                <!-- Nowe pole SELECT -->
                <label for="topic">Temat wiadomości:</label>
                <select name="topic" id="topic">
                    <!--  Jedna z opcji to "Pomysł na nowy kurs" -->
                    <option value="Pomysł na nowy kurs"
                      <?= (!empty($topic) && $topic === 'Pomysł na nowy kurs') ? 'selected' : '' ?>>
                      Pomysł na nowy kurs
                    </option>
                    <option value="Zgłoszenie błędu"
                      <?= (!empty($topic) && $topic === 'Zgłoszenie błędu') ? 'selected' : '' ?>>
                      Zgłoszenie błędu
                    </option>
                    <option value="Problemy z logowaniem"
                      <?= (!empty($topic) && $topic === 'Problemy z logowaniem') ? 'selected' : '' ?>>
                      Problemy z logowaniem
                    </option>
                    <option value="Inne"
                      <?= (empty($topic) || $topic === 'Inne') ? 'selected' : '' ?>>
                      Inne
                    </option>
                </select>

                <label for="message">Treść wiadomości:</label>
                <textarea id="message" name="message" rows="5" 
                          placeholder="Treść wiadomości..."><?= isset($message) ? htmlspecialchars($message) : '' ?></textarea>

                <button type="submit">Wyślij</button>
            </form>
        </div>
    </div>
    <?php include 'includes/footer.php';?>
    <script src="js/stars.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            createStars();
        });
    </script>
</body>
</html>
