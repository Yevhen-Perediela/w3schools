<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>O nas</title>
    <link rel="stylesheet" href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap">
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/about.css">
</head>
<body>
    <?php include 'includes/header.php' ?>
    <div id="stars" class="stars"></div>
    <div id="odstep"></div>
    <div class="containerAbout">
        <div class="sidebar">
            <h3>O nas</h3>
            <a href="about.php">O W3Schools</a>
            <a href="terms.php">Regulamin</a>
            <a href="privacy.php">Polityka Prywatności</a>
        </div>

        <div class="mainContentAbout">
            <h1>O W3Schools</h1>
            <div class="info-box">
                Największa platforma dla programistów w internecie.<br>
                3 miliardy wyświetleń stron rocznie.<br>
                70 milionów użytkowników miesięcznie.
            </div>

            <h2>Czym jest W3Schools?</h2>
            <p>Tworzymy uproszczone i interaktywne doświadczenia edukacyjne.</p>
            <p>Nauka programowania powinna być łatwa do zrozumienia i dostępna dla każdego, wszędzie!</p>

            <ul>
                <li><a href="kurs.php">Kurs HTML</a></li>
                <li><a href="kurs.php">Kurs CSS</a></li>
                <li><a href="kurs.php">Kurs JavaScript</a></li>
                <li>Kurs PHP <small>w przygotowaniu...</small></li>
                <li>Kurs SQL <small>w przygotowaniu...</small></li>
                <li>Kurs Python <small>w przygotowaniu...</small></li>
            </ul>
        </div>
    </div>
    <?php include 'includes/footer.php' ?>
    <script src="js/stars.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            createStars();
        });
    </script>
</body>
</html>
