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

    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: "Segoe UI", Tahoma, sans-serif;
            background: linear-gradient(45deg, #606c88, #3f4c6b);
            /* alternatywnie: background: linear-gradient(120deg, #ff9a9e, #fad0c4); */
        }

        .contact-container {
            max-width: 600px;
            margin: 150px auto; 
            background-color: #fff; 
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 8px 24px rgba(0, 0, 0, 0.2);
        }

        h2 {
            margin-top: 0;
            font-size: 24px;
            text-align: center;
            color: #333;
            margin-bottom: 20px;
        }

        label {
            display: block;
            font-weight: 600;
            margin-bottom: 6px;
            color: #333;
        }

        input[type="text"],
        input[type="email"],
        textarea,
        select {
            width: 100%;
            padding: 12px;
            border: 1px solid #ccc;
            border-radius: 5px;
            margin-bottom: 15px;
            font-size: 14px;
            transition: 0.3s border-color ease;
        }
        input[type="text"]:focus,
        input[type="email"]:focus,
        textarea:focus,
        select:focus {
            border-color: #6a5acd; /* zmienia kolor obramowania po focusie */
            outline: none;
        }

        button {
            background-color: #6a5acd;
            color: #fff;
            padding: 12px 24px;
            font-size: 16px;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            transition: 0.3s background-color ease;
        }
        button:hover {
            background-color: #5741c1;
        }

        .error {
            color: #ff3333;
            background: #ffe5e5;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }
        .success {
            color: #2e7d32;
            background: #e3f2fd;
            padding: 10px;
            margin-bottom: 10px;
            border-radius: 5px;
        }

        @media (max-width: 640px) {
            .contact-container {
                margin: 20px;
                padding: 20px;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php';?>

    <div class="contact-container">
        <h2>Formularz kontaktowy</h2>

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
    <?php include 'includes/footer.php';?>
</body>
</html>
