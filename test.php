<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Edytor Zadań</title>
  <link rel="stylesheet" href="styles/edytor.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.15.3/ace.js"></script>
</head>
<body>
  <header>
    <div class="header-content">
      <h1>Edytor Zadań</h1>
    </div>
  </header>

  <div class="main-container">
    <div id="editorContainer">
      <p id="taskDescription">Zadanie zostanie tutaj załadowane...</p>
      <div id="editor"></div>
      <div class="buttons">
        <button onclick="validateCurrentTask()">Sprawdź kod</button>
        <button onclick="loadTask(0)">Zadanie 1</button>
        <button onclick="loadTask(1)">Zadanie 2</button>
      </div>
      <div id="result"></div>
    </div>
  </div>

  <script>
    const editor = ace.edit("editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/javascript");
    editor.setOptions({
      fontSize: "14px",
      wrap: true,
      showPrintMargin: false,
    });

    const tasks = [
      {
        description: "Zadanie 1: Oblicz sumę dwóch liczb.",
        code: function calculateSum(a, b) {
            // Uzupełnij kod, aby zwrócić sumę dwóch liczb
            return
        }

        console.log(calculateSum(2, 3)); // Oczekiwany wynik: 5,
        validator: (output) => output === 5
      },
      {
        description: "Zadanie 2: Zwróć większą liczbę z dwóch podanych.",
        code: function getMax(a, b) {
    // Uzupełnij kod, aby zwrócić większą liczbę
    return;
}

console.log(getMax(7, 10)); // Oczekiwany wynik: 10,
        validator: (output) => output === 10
      }
    ];

    let currentTaskIndex = 0;

    function loadTask(index) {
      const task = tasks[index];
      editor.setValue(task.code, -1);
      document.getElementById("taskDescription").textContent = task.description;
      document.getElementById("result").textContent = ""; // Reset wyników
      currentTaskIndex = index;
    }

    function validateCurrentTask() {
      const userCode = editor.getValue();
      let result;
      try {
        const func = new Function(${userCode} return calculateSum(2, 3););
        result = func();

        if (tasks[currentTaskIndex].validator(result)) {
          showResult("Brawo! Poprawna odpowiedź.", true);
        } else {
          showResult(Niepoprawny wynik. Otrzymano: ${result}., false);
        }
      } catch (error) {
        showResult(Błąd w kodzie: ${error.message}, false);
      }
    }

    function showResult(message, isSuccess) {
      const resultDiv = document.getElementById("result");
      resultDiv.textContent = message;
      resultDiv.style.color = isSuccess ? "green" : "red";
    }

    // Wczytaj pierwsze zadanie domyślnie
    loadTask(0);
  </script>
</body>
</html>