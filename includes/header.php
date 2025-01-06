
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
                        <a href="#">O nas</a>
                        <a href="#">Regulamin</a>
                        <a href="#">Kontakt</a>
                    </div>
                    
                    <div class="search-bar">
                        <input type="text" placeholder="Szukaj...">
                        <button type="submit">
                            <i class="fas fa-search"></i>
                        </button>
                    </div>

                    <div class="auth-buttons">
                        <button class="sign-up">Zarejestruj się</button>
                        <button class="login">Zaloguj się</button>
                    </div>
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
                    <li><a href="kurs.php?type=HTML&lesson=Zmienne">HTML (HyperText Markup Language)</a></li>
                    <li><a href="kurs.php?type=CSS&lesson=Selektory">CSS (Cascading Style Sheets)</a></li>
                    <li><a href="kurs.php?type=JS&lesson=Loop (for, while)">JS (JavaScript)</a></li>
                </ul>
            </div>
        </nav>
    </div>
    <div id="odstep"></div>
</body>
</html>