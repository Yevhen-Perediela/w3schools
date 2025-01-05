<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/add_kurs.css">  
</head>
<body>
    <?php
        include 'includes/header.php';
    ?>
    <div id="container">
        <input type="text" id='title-input' placeholder='Nazwa..'>
        <div id='main-container'>

        </div>
        <button id="main-control" onclick='AddElementMainBtn()'>+ Dodaj</button><br>
        <button id="add-to-bd-btn" onclick="sendToBd()">Zapisz kurs</button>
        <div id="menu">
            <div data-type="h2">Dodaj H2</div>
            <div data-type="h3">Dodaj H3</div>
            <div data-type="textarea">Dodaj Textarea</div>
            <div data-type="image">Dodaj Zdjęcie</div>
        </div>

    </div>
    <script src='js/add_kurs.js'></script>
</body>
</html>