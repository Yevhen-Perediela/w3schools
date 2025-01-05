<?php
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    
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
    <div id="left-side">
    <?php
        require_once 'connect.php';
        $type = $_GET['type'];

        $sql = "SELECT title, id FROM kursy WHERE kurs_type = '$type'";
        $result = mysqli_query($conn, $sql);
        if (mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_array($result)) {
                echo '<div class="item-menu">'.$row["title"].'</div>';
            }
        }else{
            echo "Brak";
        }

    ?>
    </div>
    <div id="main-container"></div>
</body>
</html>    