<?php
session_start();
require_once 'connect.php';

$stmt = $conn->prepare("SELECT username FROM users WHERE id = ?");
$stmt->bind_param("i", $_SESSION['user_id']);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

if (!isset($_SESSION['user_id']) || $user['username'] !== 'admin') {
    header("Location: login.php");
    exit();
}


if (isset($_POST['delete_user'])) {
    $user_id = $_POST['user_id'];
    if ($user_id != $_SESSION['user_id']) { 
        $stmt = $conn->prepare("DELETE FROM users WHERE id = ?");
        $stmt->bind_param("i", $user_id);
        $stmt->execute();
    }
}


if (isset($_POST['delete_course'])) {
    $course_id = $_POST['course_id'];
    $stmt = $conn->prepare("DELETE FROM kursy WHERE id = ?");
    $stmt->bind_param("i", $course_id);
    $stmt->execute();
}


$users_result = $conn->query("SELECT id, username, lastname, email FROM users");


$html_courses = $conn->query("SELECT * FROM kursy WHERE kurs_type = 'HTML' ORDER BY created_at DESC");
$css_courses = $conn->query("SELECT * FROM kursy WHERE kurs_type = 'CSS' ORDER BY created_at DESC");
$js_courses = $conn->query("SELECT * FROM kursy WHERE kurs_type = 'JS' ORDER BY created_at DESC");
?>

<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Panel Administratora</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="./styles/admin_panel.css">
    <link rel="stylesheet" href="./styles/stars.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
    
    <div class="stars" id="stars"></div>
    
    <main class="main-content">
        <div class="admin-panel-container">
            <h1>Panel Administratora</h1>
            
            <a href="add_kurs.php" class="add-course-btn">Dodaj nowy kurs</a>

            <div class="section">
                <h2>Lista użytkowników</h2>
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Nazwa użytkownika</th>
                            <th>Nazwisko</th>
                            <th>Email</th>
                            <th>Akcje</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($user = $users_result->fetch_assoc()): ?>
                            <tr>
                                <td><?php echo htmlspecialchars($user['id']); ?></td>
                                <td><?php echo htmlspecialchars($user['username']); ?></td>
                                <td><?php echo htmlspecialchars($user['lastname']); ?></td>
                                <td><?php echo htmlspecialchars($user['email']); ?></td>
                                <td>
                                    <?php if ($user['username'] !== 'admin'): ?>
                                        <form method="POST" style="display: inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć tego użytkownika?');">
                                            <input type="hidden" name="user_id" value="<?php echo $user['id']; ?>">
                                            <button type="submit" name="delete_user" class="delete-btn">Usuń</button>
                                        </form>
                                    <?php endif; ?>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>

            <div class="section">
                <h2>Kursy HTML</h2>
                <?php while ($course = $html_courses->fetch_assoc()): ?>
                    <div class="course-item">
                        <span class="course-title"><?php echo htmlspecialchars($course['title']); ?></span>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten kurs?');">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" name="delete_course" class="delete-btn">Usuń</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="section">
                <h2>Kursy CSS</h2>
                <?php while ($course = $css_courses->fetch_assoc()): ?>
                    <div class="course-item">
                        <span class="course-title"><?php echo htmlspecialchars($course['title']); ?></span>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten kurs?');">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" name="delete_course" class="delete-btn">Usuń</button>
                        </form>
                    </div>
                <?php endwhile; ?>
            </div>

            <div class="section">
                <h2>Kursy JavaScript</h2>
                <?php while ($course = $js_courses->fetch_assoc()): ?>
                    <div class="course-item">
                        <span class="course-title"><?php echo htmlspecialchars($course['title']); ?></span>
                        <form method="POST" style="display: inline;" onsubmit="return confirm('Czy na pewno chcesz usunąć ten kurs?');">
                            <input type="hidden" name="course_id" value="<?php echo $course['id']; ?>">
                            <button type="submit" name="delete_course" class="delete-btn">Usuń</button>
                        </form>
                    </div>
                <?php endwhile; ?>
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