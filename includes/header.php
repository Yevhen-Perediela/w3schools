<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once 'connect.php';
?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="js/nav.js" defer></script>
</head>
<body>
    <div class="nav-wrapper">
        <nav class="top-nav">
            <div class="container">
                <div class="nav-content">
                    <button class="menu-toggle" aria-label="Menu">
                        <i class="fas fa-bars"></i>
                    </button>
                    
                    <a href="index.php"><div class="logo">
                        <img src="./assets/img/logo.png" alt="Logo">
                    </div></a>
                    
                    <div class="nav-links">
                        <a href="about.php">O nas</a>
                        <a href="terms.php">Regulamin</a>
                        <a href="contact.php">Kontakt</a>
                    </div>
                    
                    <?php include 'searchbar.php'; ?>
                    
                    <?php if(isset($_SESSION['user_id'])): ?>
                        <div class="auth-buttons">
                            <div class="user-info">
                                <?php if($_SESSION['username'] === 'admin'): ?>
                                    <div class="admin-links">
                                        
                                        <a href="admin_panel.php" class="user-profile-link">
                                            <?php
                                            $stmt = $conn->prepare("SELECT image FROM users WHERE id = ?");
                                            $stmt->bind_param("i", $_SESSION['user_id']);
                                            $stmt->execute();
                                            $result = $stmt->get_result();
                                            $user = $result->fetch_assoc();
                                            
                                            if ($user && $user['image']) {
                                                $imageData = base64_encode($user['image']);
                                                echo '<img src="data:image/jpeg;base64,'.$imageData.'" alt="Avatar" class="avatar">';
                                            }
                                            ?>
                                            <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                        </a>
                                        
                                    </div>
                                <?php else: ?>
                                    <a href="user_panel.php" class="user-profile-link">
                                    
                                        <?php
                                        $stmt = $conn->prepare("SELECT image FROM users WHERE id = ?");
                                        $stmt->bind_param("i", $_SESSION['user_id']);
                                        $stmt->execute();
                                        $result = $stmt->get_result();
                                        $user = $result->fetch_assoc();
                                        
                                        if ($user && $user['image']) {
                                            $imageData = base64_encode($user['image']);
                                            echo '<img src="data:image/jpeg;base64,'.$imageData.'" alt="Avatar" class="avatar">';
                                        }
                                        ?>
                                        <span class="username"><?php echo htmlspecialchars($_SESSION['username']); ?></span>
                                    </a>
                                <?php endif; ?>
                                <a href="logout.php"><button class="login">Wyloguj</button></a>
                            </div>
                        </div>
                    <?php else: ?>
                        <div class="auth-buttons">
                            <a href="register.php"><button class="sign-up">Zarejestruj się</button></a>
                            <a href="login.php"><button class="login">Zaloguj się</button></a>
                        </div>
                    <?php endif; ?>
                    
                    <label for="switch" class="switch">
                        <input id="switch" type="checkbox" onclick="themeChange()" />
                        <span class="slider"></span>
                        <span class="decoration"></span>
                    </label>
                </div>
            </div>
        </nav>

        <nav class="bottom-nav">
            <div class="container">
                <button class="tech-menu-toggle">
                    <span>Kursy</span>
                    <i class="fas fa-chevron-down"></i>
                </button>
                <ul class="tech-list">
                    <li><a id="HTML" href="kurs.php?type=HTML&lesson=HTML Lists">HTML (HyperText Markup Language)</a></li>
                    <li><a id="CSS" href="kurs.php?type=CSS&lesson=Selektory">CSS (Cascading Style Sheets)</a></li>
                    <li><a id="JS" href="kurs.php?type=JS&lesson=Loop (for, while)">JS (JavaScript)</a></li>
                    <li><a id="egzamin" href="egzamin_page.php?course=HTML">Egzaminy</a></li>
                </ul>
            </div>
        </nav>
    </div>
</body>
</html>

