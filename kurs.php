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
    <link rel="stylesheet" href="styles/index.css">
    <link rel="stylesheet" href="styles/kurs.css">
    
    <!-- Highlight.js -->
    <!-- Zamiast obecnego linku do domyślnego motywu, wstaw poniższy -->
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/styles/agate.min.css">
    <script src="https://cdnjs.cloudflare.com/ajax/libs/highlight.js/11.8.0/highlight.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <?php include 'includes/header.php'; ?>
    <div id="odstep"></div>
    <div id="stars" class="stars"></div>
    <div id="main-wrapper">
        <div id="left-side">
        <?php
        if (mysqli_num_rows($result_all) > 0) {
            $lesson = $_GET['lesson'];
            while ($row = mysqli_fetch_assoc($result_all)) {
                if($lesson == $row["title"]){
                    $class_css = 'active-page';
                }else{
                    $class_css = 'close-page';
                }
                echo '<a href="kurs.php?type=' . $type . '&lesson=' . $row["title"] . '"><div class="item-menu '.$class_css.'">' . htmlspecialchars($row["title"]) . '</div></a>';
            }
        } else {
            echo "Brak kursów.";
        }
        ?>
        </div>
        <div id="main-container">
            <h1><?php echo htmlspecialchars($title); ?></h1>
            <div class="like-container">
                <button class="like-button">
                    <i class="fas fa-heart"></i>
                    Polub kurs
                </button>
                <div class="likes-count">
                    2137 wszystkich polubień
                </div>
            </div>
        </div>
    </div>
    <?php include 'includes/footer.php'; ?>
    <script src="js/stars.js"></script>
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
                        const btn = document.createElement('button')
                        btn.textContent = 'Uruchom kod'
                        code.className = 'language-html';
                        code.textContent = item.preview;
                        pre.appendChild(code);
                        pre.appendChild(btn)
                        container.appendChild(pre);
                        hljs.highlightElement(code);
                        btn.addEventListener('click', () => {
                            const kod = encodeURIComponent(item.complete_kod);
                            console.log(kod);
                            localStorage.setItem('userCode', item.complete_kod)
                            window.location.href = 'edytor.php'
                           
                        });


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
                            checkbox.type = 'radio';
                            answerDiv.setAttribute('correct', answer.correct);
                            checkbox.disabled = true;
                            // checkbox.checked = answer.correct;
                            

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
                            imageContainer.appendChild(image); 
                            document.getElementById('main-container').appendChild(imageContainer)
                        };
                    }
            });
        }

        const kursData = <?php echo $kurs_data_array; ?>;
        console.log(kursData);

        loadFromJSON(kursData);

        quiz_cont = document.querySelectorAll('.quiz-container')
        quiz_cont.forEach(container => {
            const nazwa = container.querySelector('h4').textContent;
            var i = localStorage.getItem(nazwa)
            if(i){
                checkbox = container.querySelectorAll('.quiz-answer')
                var isCorrect = checkbox[i].getAttribute('correct');
                if (isCorrect == 'true') {
                    checkbox[i].classList.add('correct');
                    checkbox[i].querySelector('input[type="radio"]').checked = true;
                } else {
                    checkbox[i].classList.add('incorrect');
                    checkbox[i].querySelector('input[type="radio"]').checked = true;
                }
            }
        })

        checkboxes = document.querySelectorAll('.quiz-answer')
        checkboxes.forEach((checkbox, index) => {
            const quizDiv = checkbox.closest('.quiz-container');
            const allAnswers = quizDiv.querySelectorAll('.quiz-answer');
            const nazwa = quizDiv.querySelector('h4').textContent;
            const radio = checkbox.querySelector('input[type="radio"]');
            
            checkbox.addEventListener('click', () => {
                allAnswers.forEach(answer => {
                    answer.classList.remove('correct', 'incorrect');
                    answer.querySelector('input[type="radio"]').checked = false;
                });
                
                localStorage.setItem(nazwa, index)
                radio.checked = true;
                
                var isCorrect = checkbox.getAttribute('correct');
                if (isCorrect == 'true') {
                    checkbox.classList.add('correct');
                } else {
                    checkbox.classList.add('incorrect');
                }
            });
        });

        const params = new URLSearchParams(window.location.search);
        const type = params.get('type');

        document.getElementById(type).style.backgroundColor='#222536'

        
        // var left_menu_link = document.querySelectorAll('.left-menu-link')

        // left_menu_link.forEach(item => {
        //     if(item.textContent == )
        // })
        
        document.addEventListener('DOMContentLoaded', function() {
            createStars();
        });
    </script>
</body>
</html>
