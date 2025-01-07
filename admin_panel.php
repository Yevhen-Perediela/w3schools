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
    <style>
        body {
            font-family: Arial, sans-serif;
            max-width: 1200px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f5f5f5;
        }
        .section {
            background-color: white;
            padding: 20px;
            margin-bottom: 20px;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
        }
        .add-course-btn {
            display: inline-block;
            background-color: #4CAF50;
            color: white;
            padding: 12px 24px;
            text-decoration: none;
            border-radius: 4px;
            margin-bottom: 20px;
            font-weight: bold;
        }
        .add-course-btn:hover {
            background-color: #45a049;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 10px;
        }
        th, td {
            padding: 12px;
            text-align: left;
            border-bottom: 1px solid #ddd;
        }
        th {
            background-color: #f8f9fa;
            font-weight: bold;
        }
        .delete-btn {
            background-color: #dc3545;
            color: white;
            border: none;
            padding: 6px 12px;
            border-radius: 4px;
            cursor: pointer;
        }
        .delete-btn:hover {
            background-color: #c82333;
        }
        .course-type {
            font-size: 24px;
            color: #333;
            margin-bottom: 15px;
            padding-bottom: 10px;
            border-bottom: 2px solid #4CAF50;
        }
        .course-item {
            padding: 10px;
            margin: 5px 0;
            background-color: #f8f9fa;
            border-radius: 4px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .course-title {
            font-weight: bold;
            color: #333;
        }
    </style>
</head>
<body>
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

    <a href="index.php" style="display: inline-block; margin-top: 20px; color: #4CAF50; text-decoration: none;">
        Powrót do panelu głównego
    </a>
</body>
</html>