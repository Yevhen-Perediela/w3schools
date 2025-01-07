
<!DOCTYPE html>
<html lang="pl">
<head>
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <script src="./js/nav.js" defer></script>
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
                    <div class="auth-buttons">
                        <button class="sign-up">Zarejestruj się</button>
                        <button class="login">Zaloguj się</button>
                    </div>
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
                    <li><a id="HTML" href="kurs.php?type=HTML&lesson=Zmienne">HTML (HyperText Markup Language)</a></li>
                    <li><a id="CSS" href="kurs.php?type=CSS&lesson=Selektory">CSS (Cascading Style Sheets)</a></li>
                    <li><a id="JS" href="kurs.php?type=JS&lesson=Loop (for, while)">JS (JavaScript)</a></li>
                    <li><a id="egzamin" href="egzamin_page.php">Egzaminy</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div id="odstep"></div>
</body>
</html>