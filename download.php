<?php
include_once 'includes/header.php';
// Adres docelowy pliku PDF
$file = "";

// // Ustaw odpowiednie nagłówki
// header("Content-Type: application/pdf");
// header("Content-Disposition: inline; filename=\"download.pdf\"");

// // Pobierz zawartość pliku i wyświetl ją
// readfile($file);
// exit;
?>
<style>
    *{
        margin: 0;
        padding: 0;
    }
    body{
        overflow: hidden;
    }
    iframe{
        width: 100%;
        height: 87.5vh;
    }
</style>

<!-- https://pl-static.z-dn.net/files/d35/05b3b3f94845455e692c132ba0bc0cbe.pdf -->
<iframe src="<?php echo $_GET['pdf'] ?>" width="100%" height="auto"></iframe>