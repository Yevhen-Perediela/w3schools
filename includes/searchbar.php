<?php
/**************
 * searchbar.php
 * 
 * Ten plik zawiera:
 *  1) Logikę wyszukiwania (po otrzymaniu ?action=search)
 *  2) Kod HTML + JS do wyświetlenia searchbara (jeśli nie ma action=search)
 **************/
// ------------------------------------
// 1. Obsługa żądania AJAX (szukanie)
// ------------------------------------
if (isset($_GET['action']) && $_GET['action'] === 'search') {
    // Parametr wyszukiwania
    $q = isset($_GET['q']) ? trim($_GET['q']) : '';
    // Jeśli brak query, zwracamy pustą tablicę
    if ($q === '') {
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode([]);
        exit;
    }
    require_once '../connect.php';
    try {
        $dsn = "mysql:host=$host;dbname=$dbname;charset=utf8";
        $pdo = new PDO($dsn, $username, $password, [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);
        // Proste wyszukiwanie w tabeli kursy (po kolumnach title, kurs_type)
        // + ewentualnie JSON_SEARCH(kurs_data, 'one', :q) jak w poprzednich przykładach
        $sql = "
            SELECT id, title, kurs_type
            FROM kursy
            WHERE title LIKE :likeQuery
               OR kurs_type LIKE :likeQuery
        ";
        $stmt = $pdo->prepare($sql);
        $likeQuery = '%'.$q.'%';
        $stmt->bindParam(':likeQuery', $likeQuery, PDO::PARAM_STR);
        
        $stmt->execute();
        $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
        header('Content-Type: application/json; charset=utf-8');
        echo json_encode($results);
    } catch (PDOException $e) {
        // Błąd zapytania / połączenia
        header('HTTP/1.1 500 Internal Server Error');
        echo json_encode(["error" => $e->getMessage()]);
    }
    // Kończymy działanie skryptu, by nie doklejać dalej HTML:
    exit;
}
// -----------------------------------------------------------
// 2. Jeśli to nie jest 'action=search', wstawiamy HTML + JS
// -----------------------------------------------------------
?>
<!-- 
  Tutaj normalnie zaczynamy HTML i JS searchbara
  Będzie to użyte przez "include 'searchbar.php';" w header.php
-->
<div class="search-bar" style="position: relative;">
    <input 
        type="text" 
        id="search-input" 
        placeholder="Szukaj..." 
        autocomplete="off"
    >
    <button type="button" id="search-button">
        <i class="fas fa-search"></i>
    </button>
    <div id="search-results" 
         class="search-dropdown" 
         style="position: absolute; background: white; width: 100%; display: none; max-height: 200px; overflow-y: auto;">
    </div>
</div>
<script>
document.addEventListener('DOMContentLoaded', () => {
    const input      = document.getElementById('search-input');
    const input2      = document.getElementById('search-input2');
    const button     = document.getElementById('search-button');
    const resultsDiv = document.getElementById('search-results');
    // Funkcja wysyłająca zapytanie do TEGO SAMEGO pliku searchbar.php
    async function fetchSearchResults(query) {
        try {
            // Zwróć uwagę na ?action=search
            const url = 'includes/searchbar.php?action=search&q=' + encodeURIComponent(query);
            const response = await fetch(url);
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return await response.json();
        } catch (error) {
            console.error(error);
            return [];
        }
    }
    // Funkcja do wyświetlania wyników
    function displayResults(data) {
        resultsDiv.innerHTML = '';
        if (data.length === 0) {
            resultsDiv.innerHTML = '<div style="padding: 8px;">Brak wyników</div>';
            resultsDiv.style.display = 'block';
            return;
        }
        data.forEach(item => {
            const link = document.createElement('a');
            // przykładowy link do kursu
            link.href = 'kurs.php?type=' + encodeURIComponent(item.kurs_type) +
            '&lesson=' + encodeURIComponent(item.title);
            link.textContent = item.title + ' (' + item.kurs_type + ')';
            link.style.display = 'block';
            link.style.padding = '8px';
            link.style.color = '#333';
            link.style.textDecoration = 'none';
            link.addEventListener('mouseover', () => link.style.background = '#eee');
            link.addEventListener('mouseout',  () => link.style.background = 'white');
            resultsDiv.appendChild(link);
        });
        resultsDiv.style.display = 'block';
    }
    // Obsługa kliknięcia „lupy”
    button.addEventListener('click', async () => {
        const query = input.value.trim();
        if (query.length === 0) {
            resultsDiv.style.display = 'none';
            return;
        }
        const results = await fetchSearchResults(query);
        displayResults(results);
    });
    // „Live search” – po wpisywaniu w input (opcjonalnie)
    input.addEventListener('input', async () => {
        const query = input.value.trim();
        if (query.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }
        const results = await fetchSearchResults(query);
        displayResults(results);
    });
    input2.addEventListener('input', async () => {
        const query = input2.value.trim();
        if (query.length < 2) {
            resultsDiv.style.display = 'none';
            return;
        }
        const results = await fetchSearchResults(query);
        displayResults(results);
    });
    // Chowanie dropdownu po kliknięciu poza
    document.addEventListener('click', (event) => {
        if (!event.target.closest('.search-bar')) {
            resultsDiv.style.display = 'none';
        }
    });
});
</script>