<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Edytor Kodu </title>

  <style> 
    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }
    html, body {
      height: 100%;
      width: 100%;
      font-family: Arial, sans-serif;
    }

    body {
      display: flex;
      flex-direction: column;
    }
    header {
      background-color: #333;
      color: #fff;
      padding: 10px 20px;
    }
    .header-content {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .main-container {
      display: flex;
      flex: 1; /* Wypełnia pozostałą wysokość okna */
      overflow: hidden; /* By nie generować pasków przewijania */
    }

    /* LEWA KOLUMNA (EDYTOR) */
    #editorContainer {
      flex: 1;
      display: flex;
      flex-direction: column;
      border-right: 2px solid #ccc;
      min-width: 300px;
    }

    /* GÓRNY PANEL W LEWEJ KOLUMNIE (np. przyciski, wybór języka) */
    .editor-tools {
      background-color: #f9f9f9;
      padding: 10px;
      border-bottom: 1px solid #ccc;
    }
    .editor-tools select,
    .editor-tools button {
      margin-right: 10px;
      padding: 6px 10px;
      cursor: pointer;
      font-size: 14px;
    }


    #editor {
      flex: 1;
      /* Ace Editor sam zarządza swoim rozmiarem wewnątrz tego kontenera */
    }

    /* PRAWA KOLUMNA (PODGLĄD + KONSOLA) */
    #outputContainer {
      flex: 1;
      display: flex;
      flex-direction: column;
    }

    #output {
      flex: 1;
      border: none;
    }

    #consoleOutput {
      border-top: 2px solid #ccc;
      max-height: 150px;
      overflow-y: auto;
      padding: 10px;
      background-color: #fafafa;
      font-family: monospace;
      font-size: 14px;
    }
    #consoleOutput p {
      margin-bottom: 6px;
    }
  </style>
</head>
<body>

  <header>
    <div class="header-content">
      <h1>Edytor Kodu</h1>
      <div style="font-size: 0.9em; color: #ccc;">
        (Wersja demo z konsolą i zapisem w localStorage)
      </div>
    </div>
  </header>

  <div class="main-container">
    <div id="editorContainer">
      <div class="editor-tools">
        <select id="modeSelect" onchange="setEditorMode(this.value)">
          <option value="html" selected>HTML</option>
          <option value="css">CSS</option>
          <option value="javascript">JavaScript</option>
        </select>
        <button onclick="runCode()">Uruchom kod</button>
        <button onclick="copyCode()">Kopiuj kod</button>
        <button onclick="downloadCode()">Pobierz HTML</button>
      </div>

      <div id="editor"></div>
    </div>

    <!-- PRAWA KOLUMNA: PODGLĄD (iframe) + KONSOLA -->
    <div id="outputContainer">
      <iframe id="output"></iframe>
      <div id="consoleOutput"></div>
    </div>
  </div>

  <!-- ŁADOWANIE ACE EDITOR -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.15.3/ace.js"
          integrity="sha512-..."
          crossorigin="anonymous"
          referrerpolicy="no-referrer">
  </script>

  <script>

    const editor = ace.edit("editor");
    editor.setTheme("ace/theme/monokai");
    // domyślnie tryb HTML
    editor.session.setMode("ace/mode/html");

    // wczytanie poprzednio zapisanego kodu z localStorage (jeżeli istnieje)
    const savedCode = localStorage.getItem('userCode');
    if (savedCode) {
      editor.setValue(savedCode, -1);  // -1, by nie przesuwać kursora na koniec
    } else {
      editor.setValue(`
      <!DOCTYPE html>
          <html lang="pl">
          <head>
            <meta charset="UTF-8">
            <title>Hello!</title>
            <style>
              body {
                font-family: Arial, sans-serif;
                background-color: #eef;
                text-align: center;
                padding: 2em;
              }
              h2 {
                color: #336699;
              }
            </style>
          </head>
          <body>
            <h2>Witaj w naszym edytorze!</h2>
            <p>To jest domyślny kod HTML.</p>

            <script>
              console.log('Witaj w w3schools!');
            <\/script>
          </body>
          </html>`, -1);
    }

    // Gdy użytkownik coś zmienia w edytorze, zapisujemy kod do localStorage
    editor.session.on('change', () => {
      localStorage.setItem('userCode', editor.getValue());
    });

    // Funkcja zmieniająca tryb edytora (HTML/CSS/JavaScript)
    function setEditorMode(mode) {
      editor.session.setMode("ace/mode/" + mode);
    }

    // Funkcja uruchamiająca kod w iframe i przechwytująca console.log
    function runCode() {
      const code = editor.getValue();
      const outputFrame = document.getElementById('output');
      const consoleDiv = document.getElementById('consoleOutput');

      // Czyścimy poprzednią zawartość konsoli
      consoleDiv.innerHTML = '';

      // Otaczamy kod w "szablon" HTML, aby przechwycić console.log
      const wrappedCode = `
        <html>
          <head></head>
          <body>
            ${code}
            <script>
              (function() {
                // Zapisujemy oryginalny console.log
                const originalLog = console.log;
                // Nadpisujemy console.log w iframe
                console.log = function(...args) {
                  // Wysyłamy dane do rodzica (do głównego okna)
                  window.parent.postMessage({ type: 'iframeConsole', data: args }, '*');
                  // Wywołujemy oryginalny console.log (aby ewentualnie pojawiało się też w konsoli przeglądarki)
                  originalLog.apply(console, args);
                };
              })();
            <\/script>
          </body>
        </html>
      `;

      // Załadowanie kodu do iframe (srcdoc)
      outputFrame.srcdoc = wrappedCode;
    }

    // Nasłuchujemy wiadomości z iframe
    window.addEventListener('message', (event) => {
      if (event.data && event.data.type === 'iframeConsole') {
        const consoleDiv = document.getElementById('consoleOutput');
        // Łączymy wszystkie argumenty w jeden string
        const messages = event.data.data.join(' ');
        // Wrzucamy to do <p> i dodajemy do konsoli
        const p = document.createElement('p');
        p.textContent = messages;
        consoleDiv.appendChild(p);
      }
    });

    // Funkcja kopiująca kod z edytora do schowka
    function copyCode() {
      const code = editor.getValue();
      navigator.clipboard.writeText(code)
        .then(() => alert('Skopiowano kod do schowka!'))
        .catch(err => console.error('Błąd podczas kopiowania: ', err));
    }

    // Funkcja pobierająca kod jako plik .html
    function downloadCode() {
      const code = editor.getValue();
      const blob = new Blob([code], { type: 'text/html' });
      const url = URL.createObjectURL(blob);

      const a = document.createElement('a');
      a.href = url;
      a.download = 'projekt.html';
      document.body.appendChild(a);
      a.click();

      document.body.removeChild(a);
      URL.revokeObjectURL(url);
    }
  </script>
</body>
</html>
