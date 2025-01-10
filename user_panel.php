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


$username = $user_data['username'];
$lastname = $user_data['lastname'];
$email = $user_data['email'];

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


$stmt = $conn->prepare("
    SELECT k.id, k.title, k.kurs_type 
    FROM kursy k 
    INNER JOIN course_likes cl ON k.id = cl.course_id 
    WHERE cl.user_id = ?
    ORDER BY cl.created_at DESC
");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$liked_courses = $stmt->get_result()->fetch_all(MYSQLI_ASSOC);

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
    
    <link rel="stylesheet" href="./styles/user_panel.css">
    <link rel="stylesheet" href="./styles/header.css">
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
                    <p><strong>Nazwa użytkownika:</strong> <span class="username"><?php echo htmlspecialchars($username); ?></span></p>
                    <p><strong>Nazwisko:</strong> <?php echo htmlspecialchars($lastname); ?></p>
                    <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
                </div>
            </div>

            <div class="form-section">
                <h3>Zmiana nazwy użytkownika</h3>
                <form method="POST" name="update_username">
                    <label for="new_username">Nowa nazwa użytkownika:</label>
                    <input type="text" id="new_username" name="new_username" 
                           value="<?php echo htmlspecialchars($username); ?>" required>
                    <input type="submit" value="Zmień nazwę użytkownika">
                </form>
            </div>

            <div class="form-section">
                <h3>Zdjęcie profilowe</h3>
                <?php if (!empty($user['image'])): ?>
                    <img src="data:image/jpeg;base64,<?php echo base64_encode($user['image']); ?>" 
                         class="profile-image" alt="Zdjęcie profilowe">
                <?php endif; ?>
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

            <div class="form-section">
                <h3>Polubione kursy</h3>
                <?php if (!empty($liked_courses)): ?>
                    <div class="liked-courses">
                        <?php foreach ($liked_courses as $course): ?>
                            <div class="course-item">
                                <a href="kurs.php?type=<?php echo urlencode($course['kurs_type']); ?>&lesson=<?php echo urlencode($course['title']); ?>">
                                    <span class="course-title"><?php echo htmlspecialchars($course['title']); ?></span>
                                    <span class="course-type"><?php echo htmlspecialchars($course['kurs_type']); ?></span>
                                </a>
                                <button class="unlike-button" data-course-id="<?php echo $course['id']; ?>">
                                    ❌
                                </button>
                            </div>
                        <?php endforeach; ?>
                    </div>
                <?php else: ?>
                    <p class="no-courses">Nie polubiłeś jeszcze żadnych kursów.</p>
                <?php endif; ?>
            </div>

            <div class="form-section" id="historia">
                <h3>Historia przegladania</h3>
                <a href="" id="a-hist1" href=""><p id="hist1"></p></a>
                <a href='' id="a-hist2" href=""><p id="hist2"></p></a>
                <a href= '' id="a-hist3" href=""><p id="hist3"></p></a>
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

    <script>
    document.getElementById('hist1').textContent = localStorage.getItem('hist1') 
    document.getElementById('hist2').textContent = localStorage.getItem('hist2') 
    document.getElementById('hist3').textContent = localStorage.getItem('hist3') 

    document.getElementById('a-hist1').href = 'kurs.php?type='+localStorage.getItem('a-hist1')+'&lesson='+localStorage.getItem('hist1')
    document.getElementById('a-hist2').href = 'kurs.php?type='+localStorage.getItem('a-hist2')+'&lesson='+localStorage.getItem('hist2')
    document.getElementById('a-hist3').href = 'kurs.php?type='+localStorage.getItem('a-hist3')+'&lesson='+localStorage.getItem('hist3')


    document.addEventListener('DOMContentLoaded', function() {
       
        const usernameForm = document.querySelector('form[name="update_username"]');
        usernameForm.addEventListener('submit', async function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            try {
                const response = await fetch('update_user.php', {
                    method: 'POST',
                    body: formData
                });
                
                const data = await response.json();
                if (data.success) {
                   
                    document.querySelector('.user-info .username').textContent = data.new_username;
                    showMessage('success', 'Nazwa użytkownika została zaktualizowana.');
                } else {
                    showMessage('error', data.error);
                }
            } catch (error) {
                showMessage('error', 'Wystąpił błąd podczas aktualizacji.');
            }
        });
        
        function showMessage(type, message) {
            const messageDiv = document.createElement('div');
            messageDiv.className = type;
            messageDiv.textContent = message;
            
          
            document.querySelectorAll('.success, .error').forEach(el => el.remove());
            
          
            usernameForm.insertAdjacentElement('beforebegin', messageDiv);
           
            setTimeout(() => messageDiv.remove(), 3000);
        }
    });
    </script>

    <script>
    document.querySelectorAll('.unlike-button').forEach(button => {
        button.addEventListener('click', async function() {
            const courseId = this.dataset.courseId;
            try {
                const response = await fetch('like.php', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({ 
                        course_id: parseInt(courseId) 
                    })
                });
                
                const data = await response.json();
                if (data.success && data.action === 'unliked') {
                    
                    this.closest('.course-item').remove();
                    
                   
                    const likedCourses = document.querySelector('.liked-courses');
                    if (likedCourses.children.length === 0) {
                        likedCourses.innerHTML = '<p class="no-courses">Nie polubiłeś jeszcze żadnych kursów.</p>';
                    }
                    
                    showMessage('success', 'Usunięto z polubionych.');
                }
            } catch (error) {
                console.error('Błąd:', error);
                showMessage('error', 'Wystąpił błąd podczas usuwania polubienia.');
            }
        });
    });
    </script>
</body>
</html>
