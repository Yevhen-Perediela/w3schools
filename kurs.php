<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'connect.php';
$type = isset($_GET['type']) ? $_GET['type'] : '';
$title = isset($_GET['lesson']) ? $_GET['lesson'] : '';

if (empty($type) || empty($title)) {
    die('Brak wymaganych parametrów.');
}

$sql_all_courses = "SELECT * FROM kursy WHERE kurs_type = '$type'";
$sql_one_course = "SELECT * FROM kursy WHERE kurs_type = '$type' AND title = '$title'";

$result_all = mysqli_query($conn, $sql_all_courses);
$result_one = mysqli_query($conn, $sql_one_course);

$kurs_data = '';
if (mysqli_num_rows($result_one) > 0) {
    $row = mysqli_fetch_assoc($result_one);
    $kurs_data = $row['kurs_data'];
}

$kurs_data_array = json_decode($kurs_data, true);
if (json_last_error() !== JSON_ERROR_NONE) {
    die('Błąd dekodowania JSON: ' . json_last_error_msg());
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kurs</title>
    <link rel="stylesheet" href="styles/kurs.css">
    <!-- Highlight.js -->
    <!-- Zamiast obecnego linku do domyślnego motywu, wstaw poniższy -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/agate.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div id="main-wrapper">
        <div id="left-side">
        <?php
        if (mysqli_num_rows($result_all) > 0) {
            while ($row = mysqli_fetch_assoc($result_all)) {
                echo '<a href="kurs.php?type=' . htmlspecialchars($type) . '&lesson=' . htmlspecialchars($row["title"]) . '"><div class="item-menu">' . htmlspecialchars($row["title"]) . '</div></a>';
            }
        } else {
            echo "Brak kursów.";
        }
        ?>
        </div>
        <div id="main-container">
            <h1><?php echo htmlspecialchars($title); ?></h1>
        </div>
    </div>

    <script>
        document.addEventListener("DOMContentLoaded", () => hljs.highlightAll());

        function loadFromJSON(jsonData) {
            if (!Array.isArray(jsonData)) {
                console.error('Dane JSON muszą być tablicą:', jsonData);
                console.log(typeof jsonData)
                return;
            }

            jsonData.forEach(item => {
                const container = document.getElementById('main-container');

                switch (item.type) {
                    case 'h3':
                    case 'h2':
                        const header = document.createElement(item.type);
                        header.className = item.styles?.class || '';
                        header.textContent = item.content;
                        container.appendChild(header);
                        break;
                    case 'textarea':
                        const p = document.createElement('p');
                        p.textContent = item.content;
                        container.appendChild(p);
                        break
                    case 'code':
                        const pre = document.createElement('pre');
                        const code = document.createElement('code');
                        code.className = 'language-html';
                        code.textContent = item.complete_kod;
                        pre.appendChild(code);
                        container.appendChild(pre);
                        hljs.highlightElement(code);
                        break;

                    case 'notatka':
                        const noteDiv = document.createElement('div');
                        noteDiv.className = 'notatka-div';
                        const noteText = document.createElement('p');
                        noteText.textContent = item.content;
                        noteDiv.appendChild(noteText);
                        container.appendChild(noteDiv);
                        break;

                    case 'quiz':
                        const quizDiv = document.createElement('div');
                        quizDiv.className = 'quiz-container';

                        const question = document.createElement('h4');
                        question.textContent = item.question;
                        quizDiv.appendChild(question);

                        item.answers.forEach(answer => {
                            const answerDiv = document.createElement('div');
                            answerDiv.className = 'quiz-answer';

                            const checkbox = document.createElement('input');
                            checkbox.type = 'checkbox';
                            checkbox.disabled = true;
                            checkbox.checked = answer.correct;

                            const label = document.createElement('label');
                            label.textContent = answer.text;

                            answerDiv.appendChild(checkbox);
                            answerDiv.appendChild(label);
                            quizDiv.appendChild(answerDiv);
                        });

                        container.appendChild(quizDiv);
                        break;

                    case 'image-container':
                        const imageContainer = document.createElement('div');
                        imageContainer.className = 'image-container';
                        for(let i = 0; i<item.images.length; i+=2){
                            img = item.images[i]
                            
                            const image = document.createElement('img');
                            image.src = img.src;
                            image.style.width = img.width;
                            image.style.height = img.height;
                            imageContainer.appendChild(image); // Add image to body
                            document.getElementById('main-container').appendChild(imageContainer)
                        };
                    }
            });
        }

        const kursData = <?php echo $kurs_data_array; ?>;
        console.log(kursData);

        loadFromJSON(kursData);
    </script>
</body>
</html>
