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
        <div id='element'>
            <textarea id="auto-resize-textarea" rows='1' placeholder='Wpisz text..' oninput="this.style.height = ''; this.style.height = this.scrollHeight + 'px';"></textarea>

        </div>
        <button id="main-control">+ Dodaj</button>
        <div id="menu">
            <div onclick="addElement('h1')">Dodaj H1</div>
            <div onclick="addElement('h2')">Dodaj H2</div>
            <div onclick="addElement('h3')">Dodaj H3</div>
            <div onclick="addElement('textarea')">Dodaj Textarea</div>
            <div onclick="addImage()">Dodaj Zdjęcie</div>
        </div>
    </div>
    <script src='js/add_kurs.js'></script>
</body>
</html>