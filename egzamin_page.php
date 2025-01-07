<?php 
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
    include_once 'includes/header.php';
    
    function getPdfLinks($courseType) {
        require_once 'connect.php';

        if (!$conn) {
            die("Connection failed: " . mysqli_connect_error());
        }

        // Tworzenie zapytania SQL
        $courseType = mysqli_real_escape_string($conn, $courseType);
        $sql = "SELECT course_name, pdf_link FROM pdf_files WHERE course_type = '$courseType'";

        // Wykonanie zapytania i przetwarzanie wyników
        $result = mysqli_query($conn, $sql);

        $pdfLinks = [];
        if ($result && mysqli_num_rows($result) > 0) {
            while ($row = mysqli_fetch_assoc($result)) {
                $pdfLinks[] = $row;
            }
        }

        mysqli_close($conn);
        return $pdfLinks;
    }

    $courseType = $_GET['course']; // Możesz ustawić dynamicznie w zależności od strony
    $pdfLinks = getPdfLinks($courseType);


?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Egzaminy - <?php echo htmlspecialchars($courseType); ?></title>
    <link rel="stylesheet" href="styles/egzamin_page.css">
</head>
<body>
<div id="main-wrapper">
    <div id="left-side">
        <a href="egzamin_page.php?course=HTML"><div class="item-menu">HTML</div></a>
        <a href="egzamin_page.php?course=CSS"><div class="item-menu">CSS</div></a>
        <a href="egzamin_page.php?course=JS"><div class="item-menu">JavaScript</div></a>
        
    </div>
    <div id="main-container">
        <svg id="connections" width="100%" height="100%" style="position: absolute; top: 0; right: 0; z-index: 0;"></svg>
        <?php foreach ($pdfLinks as $pdf): ?>
            <a href="download.php?pdf=<?php echo urlencode($pdf['pdf_link']); ?>">
                <div>
                    <img src="assets/img/pdf.png" alt="PDF Icon">
                    <p><?php echo htmlspecialchars($pdf['course_name']); ?></p>
                </div>
            </a>
        <?php endforeach; ?>
    </div>
</div>
<script src="js/egzamin_page.js"></script>
</body>
</html>