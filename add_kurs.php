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
    <div id="odstep" style="height: 0px"></div>
    <div id="container">
        <input type="text" id='title-input' placeholder='Nazwa..'>
        <div id='main-container'>

        </div>
        <button id="main-control" onclick='AddElementMainBtn()'>+ Dodaj</button><br>
        <div id="div-add-to-bd">
            <select id="kurs-type">
                <option value="" disabled selected>Wybierz typ</option>
                <option value="HTML">HTML</option>
                <option value="CSS">CSS</option>
                <option value="JS">JS</option>
            </select>
            <button id="add-to-bd-btn" onclick="sendToBd()">Zapisz kurs</button>
        </div>
        <div id="menu">
            <div data-type="h2">Dodaj H2</div>
            <div data-type="h3">Dodaj H3</div>
            <div data-type="textarea">Dodaj Textarea</div>
            <div data-type="image">Dodaj Zdjęcie</div>
            <div data-type="code">Edytor kodu</div>
            <div data-type="notatka">Notatka</div>
            <div data-type="quiz">Quiz</div>
        </div>

    </div>
    <script src='js/add_kurs.js'></script>
</body>
</html>