<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/add_kurs.css">
    <style>
        .image-container {
            display: flex; /* Umożliwia wyświetlanie obrazów obok siebie */
            justify-content: center; /* Wyśrodkowanie obrazów */
            margin: 10px 0; /* Margines dla obrazów */
        }
        .image-container img {
            max-width: 45%; /* Ustaw maksymalną szerokość obrazów */
            height: auto; /* Ustaw automatyczną wysokość */
            margin: 0 5px; /* Margines między obrazami */
        }
        .header-input {
            font-size: 24px; /* Domyślny rozmiar czcionki dla h2 */
        }
        .header-input.h3 {
            font-size: 18px; /* Rozmiar czcionki dla h3 */
        }
        .header-input.h1 {
            font-size: 32px; /* Rozmiar czcionki dla h1 */
        }
        .add-button {
            display: none; /* Ukryj przycisk */
            position: absolute;
            left: -50px; /* Pozycjonowanie przycisku */
            background-color: #3a3f47; /* Kolor tła przycisku */
            color: white; /* Kolor tekstu przycisku */
            border: none; /* Bez obramowania */
            padding: 5px; /* Padding dla przycisku */
            cursor: pointer; /* Kursor wskazujący */
        }
        .element-container {
            position: relative; /* Umożliwia pozycjonowanie przycisku w odniesieniu do elementu */
        }
    </style>
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
            <div onclick="addElement('h2')">Dodaj H2</div>
            <div onclick="addElement('h3')">Dodaj H3</div>
            <div onclick="addElement('textarea')">Dodaj Textarea</div>
            <div onclick="addImage()">Dodaj Zdjęcie</div>
        </div>
    </div>
    <script src='js/add_kurs.js'></script>
</body>
</html>