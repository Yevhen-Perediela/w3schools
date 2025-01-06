<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    require_once 'connect.php';
    $type = $_GET['type'];
    $title = $_GET['lesson'];

    $sql_all_courses = "SELECT * FROM kursy WHERE kurs_type = '$type' ";
    $sql_one_course = "SELECT * FROM kursy WHERE kurs_type = '$type' AND title = '$title'";

    $result = $conn -> query($sql_all_courses);
    $result_one_course = $conn -> query($sql_one_course);
    // echo $result;
    // Pobierz dane kursu, jeśli są dostępne
  
    $kurs_data = '';
    if ($result_one_course->num_rows > 0) {
        while ($row = $result_one_course->fetch_assoc()) {
            // echo $row['title'];
            $kurs_data = $row['kurs_data'];
        }
    }

    // Jeżeli kurs_data jest JSON-em, przekształcamy ją na tablicę w PHP
    $kurs_data_array = json_decode($kurs_data, true); // Przekształcamy na tablicę


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="styles/kurs.css">
</head>
<body>
    <?php
        include 'includes/header.php';
    ?>
    <div id="main-wrapper">
        <div id="left-side">
        <?php
            // Check if there are any results and echo the data if available
            if ($result->num_rows > 0) {
                // $result = $conn -> query($sql);
                while ($row = $result->fetch_assoc()) {
                    echo '<a href="kurs.php?type='.$type.'&lesson='.$row["title"].'"><div class="item-menu">'.$row["title"].'</div></a>';
                }
            } else {
                echo "Brak";
            }
        ?>
        </div>
        <div id="main-container">
            <h1><?php echo $title; ?></h1>
        </div>
    </div>

    <script>

        function addElement(type, where, content){
            var item = document.createElement(type)
            item.textContent =content
            document.getElementById('main-container').appendChild(item)
        }

        function loadFromJSON(jsonData) {
    console.log("Raw JSON Data:", jsonData);  // Log the raw JSON data

    try {
        // JSON.parse może nie być potrzebne, jeśli dane są już poprawną tablicą w formacie obiektu
        const data = Array.isArray(jsonData) ? jsonData : JSON.parse(jsonData);  // Parse if not already an array

        console.log(typeof data); // Log parsed data to ensure it's valid

        if (Array.isArray(data) && data.length > 0) {
            data.forEach(item => {
                console.log(item);  // Log each element to see the contents

                // Handle different types of elements
                if (item.type === 'textarea') {
                    document.createElement
                    addElement('p', 'end', item.content);
                } else if (item.type === 'h2' || item.type === 'h3') {
                    addElement(item.type, 'end', item.content);
                } else if (item.type === 'image-container') {
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
        } else {
            console.error('Data is not an array or is empty:', data);
        }
    } catch (e) {
        console.error('Error parsing JSON:', e);
    }
}

        // Ensure kurs_data is valid JSON and log it
        
        let kursData = <?php echo json_encode($kurs_data_array); ?>;
        console.log(kursData); // Sprawdź w konsoli, czy jest to tablica
        loadFromJSON(kursData);
        
    </script>
</body>
</html>
