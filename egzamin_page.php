<?php 
    include_once 'includes/header.php'
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egzaminy</title>
    <link rel="stylesheet" href="styles/egzamin_page.css">
    <link rel="stylesheet" href="styles/kurs.css">
</head>
<body>
<div id="main-wrapper">
        <div id="left-side">
            <a href="kurs.php"><div class="item-menu">HTML</div></a>
            <a href="kurs.php"><div class="item-menu">CSS</div></a>
            <a href="kurs.php"><div class="item-menu">JavaScript</div></a>
            <a href="download.php">Download</a>
        </div>
        <div id="main-container">
            <svg  id="connections" width="100%" height="100%" style="position: absolute; top: 0; right: 0; z-index: 0;"></svg>
            <div>
                <img src="assets/img/pdf.png">
                <p>Egzamin 1</p>
            </div>
            <div>
                <img src="assets/img/pdf.png">
                <p>Egzamin 2</p>
            </div>
            <div>
                <img src="assets/img/pdf.png">
                <p>Egzamin 3</p>
            </div>
            <div>
                <img src="assets/img/pdf.png">
                <p>Egzamin 4</p>
            </div>
        </div>

    </div>
    <script src="js/egzamin_page.js"></script>
</body>
</html>