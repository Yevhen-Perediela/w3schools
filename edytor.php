<?php
  ini_set('display_errors', 1);
  ini_set('display_startup_errors', 1);
  error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="pl">
<head>
  <meta charset="UTF-8">
  <title>Edytor Kodu</title>
  <link rel="stylesheet" href="styles/edytor.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
  <link href="https://cdn.jsdelivr.net/npm/@fontsource/cascadia-code@4.2.1/index.css" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Fira+Code:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=JetBrains+Mono:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Source+Code+Pro:wght@400;500&display=swap" rel="stylesheet">
  <link href="https://fonts.googleapis.com/css2?family=Ubuntu+Mono:wght@400;700&display=swap" rel="stylesheet">
  <link rel="stylesheet" href="styles/chat-widget.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/prism/1.24.1/themes/prism-tomorrow.min.css">
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.15.3/ext-searchbox.js"></script>
  <script src="https://unpkg.com/prettier@2.8.8/standalone.js"></script>
  <script src="https://unpkg.com/prettier@2.8.8/parser-html.js"></script>
  <script src="https://unpkg.com/prettier@2.8.8/parser-postcss.js"></script>
  <script src="https://unpkg.com/prettier@2.8.8/parser-babel.js"></script>
</head>
<body>
    <?php include_once 'includes/header.php'; ?>
  <!-- <header>
    <div class="header-content">
      <a href="../index.php"><div class="logo">
          <img src="../assets/img/logo.png" alt="Logo">
      </div></a>
      <h1>Edytor Kodu</h1>

    </div>
  </header> -->

  <div class="main-container">
    <div id="editorContainer">
      <div class="editor-tools">
        <div class="left-buttons">
          <div class="tool-group">
              <button onclick="copyCode()" title="Kopiuj kod">
                  <i class="fas fa-copy"></i>
              </button>
              <button onclick="runCode()" title="Uruchom">
                  <i class="fas fa-start"></i>
              </button>
              <button onclick="downloadCode()" title="Pobierz jako HTML">
                  <i class="fas fa-download"></i>
              </button>
          </div>
          
          <div class="tool-group">
              <button onclick="formatCode()" title="Formatuj kod">
                  <i class="fas fa-indent"></i>
              </button>
              <button onclick="toggleInvisibles()" title="Pokaż/ukryj niewidoczne znaki">
                  <i class="fas fa-eye"></i>
              </button>
              <button onclick="increaseFontSize()" title="Zwiększ czcionkę">
                  <i class="fas fa-plus"></i>
              </button>
              <button onclick="decreaseFontSize()" title="Zmniejsz czcionkę">
                  <i class="fas fa-minus"></i>
              </button>
          </div>
        </div>

        <div class="selector-group">
            <select id="themeSelector" onchange="changeTheme(this.value)" title="Wybierz motyw">
                <option value="dracula">Dracula</option>
                <option value="monokai">Monokai</option>
                <option value="github">GitHub</option>
                <option value="tomorrow">Tomorrow</option>
                <option value="twilight">Twilight</option>
            </select>
            <select id="fontSelector" onchange="changeFont(this.value)" title="Wybierz czcionkę">
                <option value="Cascadia Code" selected>Cascadia Code</option>
                <option value="Fira Code">Fira Code</option>
                <option value="JetBrains Mono">JetBrains Mono</option>
                <option value="Source Code Pro">Source Code Pro</option>
                <option value="Ubuntu Mono">Ubuntu Mono</option>
            </select>
        </div>
      </div>

      <div class="editor-status">
          Linia: <span id="cursorPos">1:1</span> | Tryb: HTML
      </div>

      <div id="editor"></div>
    </div>

    <!-- PRAWA KOLUMNA: PODGLĄD (iframe) + KONSOLA -->
    <div id="outputContainer">
        <div class="preview-header">
            <div class="preview-info">
                <img id="previewFavicon" src="" alt="" class="preview-favicon">
                <span id="previewTitle">Podgląd</span>
            </div>
        </div>
        <iframe id="output"></iframe>
        <div class="console-container">
            <div class="console-header">
                <span>Konsola</span>
                <button onclick="clearConsole()">Wyczyść</button>
            </div>
            <div id="consoleOutput"></div>
        </div>
    </div>
  </div>

  <!-- ŁADOWANIE ACE EDITOR -->
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.15.3/ace.js" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.6/beautify.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/js-beautify/1.14.6/beautify-html.min.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.15.3/ext-language_tools.js"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.15.3/ext-beautify.js"></script>


  <script>

    const editor = ace.edit("editor");
    editor.setTheme("ace/theme/dracula");
    editor.session.setMode("ace/mode/html");
    editor.setOptions({
        enableBasicAutocompletion: true,
        enableLiveAutocompletion: true,
        enableSnippets: true,
        showPrintMargin: false,
        wrap: true,
        fontSize: "14px",
        highlightActiveLine: true,
        highlightSelectedWord: true,
        scrollPastEnd: 0.5,
        behavioursEnabled: true,
        displayIndentGuides: true,
        showGutter: true,
        showInvisibles: false,
        fadeFoldWidgets: true,
        animatedScroll: true,
        tabSize: 2,
        fontFamily: "Cascadia Code",
        enableLigatures: false
    });

    // Dodaj style dla edytora aby włączyć ligatury
    document.querySelector('.ace_editor').style.fontFeatureSettings = "normal";

    const savedCode = localStorage.getItem('userCode');
    if (savedCode) {
        editor.setValue(savedCode, -1);
    } else {
      editor.setValue(`
      <!DOCTYPE html>
          <html lang="pl">
          <head>
            <meta charset="UTF-8">
            <style>
              body {
                font-family: Arial, sans-serif;
                background-color: #1a1b26;
                text-align: center;
                display: flex;
                justify-content: center;
                align-items: center;
                min-height: 100vh;
                margin: 0;
                perspective: 1000px;
              }
              .container {
                width: 300px;
                height: 300px;
                transform-style: preserve-3d;
                animation: rotate 10s infinite linear;
              }
              img {
                width: 100%;
                height: 100%;
                object-fit: cover;
                border-radius: 10px;
                box-shadow: 0 0 20px rgba(0, 0, 0, 0.5);
              }
              @keyframes rotate {
                from { transform: rotateY(0deg); }
                to { transform: rotateY(360deg); }
              }
            </style>
          </head>
          <body>
            <div class="container">
              <img src="https://media.discordapp.net/attachments/716126422242033707/820264285376610335/image0.gif?ex=67810b25&is=677fb9a5&hm=eb426289a2bc3d9c21176241e9719ce07cc554e2dc7026db1851a25449b90b6c&">
            </div>
          </body>
          </html>`, -1);
    }

    // Uruchom kod od razu po inicjalizacji edytora
    runCode();

    // Formatuj kod od razu po inicjalizacji edytora
    formatCode();

    // Automatyczne odświeżanie po wprowadzeniu zmian
    // let updateTimeout;
    // editor.session.on('change', () => {
    //     clearTimeout(updateTimeout);
    //     updateTimeout = setTimeout(() => {
    //         runCode();
    //         localStorage.setItem('userCode', editor.getValue());
    //     }, 50);
    // });

    // Funkcja zmieniająca tryb edytora (HTML/CSS/JavaScript)
    function setEditorMode(mode) {
      editor.session.setMode("ace/mode/" + mode);
    }

    // Funkcja uruchamiająca kod w iframe i przechwytująca console.log
    function runCode() {
        const code = editor.getValue(); 
        const outputFrame = document.getElementById('output')
        console.log(code.includes('for'));

        
            




        // Przygotuj skrypt do przechwytywania console.log
        const consoleScript = `
            const originalConsole = {
                log: console.log,
                error: console.error,
                warn: console.warn,
                info: console.info
            };

            function sendToParent(type, args) {
                window.parent.postMessage({
                    type: 'console',
                    logType: type,
                    data: Array.from(args).map(arg => {
                        try {
                            return typeof arg === 'object' ? JSON.stringify(arg) : String(arg);
                        } catch (e) {
                            return String(arg);
                        }
                    })
                }, '*');
            }

            console.log = function() { 
                sendToParent('log', arguments);
                originalConsole.log.apply(console, arguments);
            };
            console.error = function() {
                sendToParent('error', arguments);
                originalConsole.error.apply(console, arguments);
            };
            console.warn = function() {
                sendToParent('warn', arguments);
                originalConsole.warn.apply(console, arguments);
            };
            console.info = function() {
                sendToParent('info', arguments);
                originalConsole.info.apply(console, arguments);
            };

            window.onerror = function(msg, url, line, col, error) {
                sendToParent('error', [\`\${msg} (line \${line}, column \${col})\`]);
                return false;
            };
        `;

        // Tworzymy dokument HTML
        const html = `
            <!DOCTYPE html>
            <html>
                <head>
                    <script>${consoleScript}<\/script>
                </head>
                <body>
                    ${code}
                </body>
            </html>
        `;

        // Ustawiamy zawartość iframe
        outputFrame.srcdoc = html;
        
        // Aktualizuj informacje o stronie po załadowaniu iframe
        outputFrame.onload = () => {
            setTimeout(updatePreviewInfo, 100); // Dodajemy małe opóźnienie
            var iframe = document.getElementById('output');
        const iframeDocument = iframe.contentDocument || iframe.contentWindow.document;

        const element = iframeDocument.getElementById('div');
            if (element) {
                console.log('Element znaleziony:', element.innerHTML);
                if(element.innerHTML.replace(/\s/g, "") == '<p>Hello</p><p>Hello</p><p>Hello</p><p>Hello</p><p>Hello</p>'){
                    if(code.includes('for')){
                        alert('Zadanie wykonane')
                        return
                    }     
                }
            } else {
                console.log('Element nie znaleziony');
            }

            const element2 = iframeDocument.getElementById('p');
            if (element2) {
                if(element2.style.color == 'red'){
                    
                    alert('Zadanie wykonane')
                    return
                       
                }
            } else {
                console.log('Element nie znaleziony');
            }
        };

        
    }

    // Nasłuchujemy wiadomości z iframe
    window.addEventListener('message', (event) => {
        if (event.data && event.data.type === 'console') {
            const consoleDiv = document.getElementById('consoleOutput');
            const logElement = document.createElement('div');
            logElement.className = `console-${event.data.logType}`;
            
            // Dodaj licznik logów
            const logCount = consoleDiv.children.length + 1;
            
            // Formatuj wiadomość w zależności od typu danych
            const formattedMessage = event.data.data.map(item => {
                try {
                    if (typeof item === 'object') {
                        return JSON.stringify(item, null, 2);
                    }
                    return item;
                } catch (e) {
                    return String(item);
                }
            }).join(' ');
            
            // Dodajemy timestamp
            const time = new Date().toLocaleTimeString();
            logElement.innerHTML = `
                <span class="console-count">[${logCount}]</span>
                <span class="console-time">[${time}]</span>
                <span class="console-type">[${event.data.logType.toUpperCase()}]</span>
                <span class="console-message">${formattedMessage}</span>
            `;
            
            consoleDiv.appendChild(logElement);
            consoleDiv.scrollTop = consoleDiv.scrollHeight;
        }
    });

    // Funkcja kopiująca kod z edytora do schowka
    function copyCode() {
      const code = editor.getValue();
      navigator.clipboard.writeText(code)
        .then(() => alert('Skopiowano kod do schowka!'))
        .catch(err => console.error('Błąd podczas kopiowania: ', err));
    }
    function changeTheme(themeName){
      editor.setTheme("ace/theme/"+ themeName)
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
    ace.edit(element, {
    mode: "ace/mode/javascript",
    selectionStyle: "text"
})

    // Formatowanie kodu
    function formatCode() {
        try {
            const code = editor.getValue();
            const cursorPosition = editor.getCursorPosition();
            
            // Konfiguracja Prettier
            const prettierConfig = {
                parser: "html",
                plugins: [prettierPlugins.html, prettierPlugins.postcss, prettierPlugins.babel],
                printWidth: 80,
                tabWidth: 2,
                useTabs: false,
                semi: true,
                singleQuote: false,
                trailingComma: "none",
                bracketSpacing: true,
                arrowParens: "avoid",
                htmlWhitespaceSensitivity: "css",
                endOfLine: "lf"
            };
            
            // Formatowanie kodu
            const formattedCode = prettier.format(code, prettierConfig);
            
            // Aktualizacja edytora
            editor.setValue(formattedCode, -1);
            editor.moveCursorToPosition(cursorPosition);
            
            // Pokaż powiadomienie o sukcesie
            showNotification("Kod został sformatowany", "success");
        } catch (error) {
            console.error("Błąd formatowania:", error);
            showNotification("Błąd formatowania kodu", "error");
        }
    }

    // Funkcja do pokazywania powiadomień
    function showNotification(message, type = "info") {
        const notification = document.createElement("div");
        notification.className = `notification ${type}`;
        notification.textContent = message;
        
        Object.assign(notification.style, {
            position: "fixed",
            bottom: "20px",
            right: "20px",
            padding: "12px 24px",
            borderRadius: "4px",
            color: "white",
            zIndex: 1000,
            animation: "slideIn 0.3s ease-out",
            backgroundColor: type === "success" ? "rgba(4, 170, 109, 0.9)" : "rgba(255, 68, 68, 0.9)"
        });
        
        document.body.appendChild(notification);
        
        setTimeout(() => {
            notification.style.animation = "slideOut 0.3s ease-out";
            setTimeout(() => notification.remove(), 300);
        }, 2000);
    }

    // Czyszczenie konsoli
    function clearConsole() {
        document.getElementById('consoleOutput').innerHTML = '';
    }

    // Pokazywanie pozycji kursora
    editor.selection.on('changeCursor', () => {
        const pos = editor.selection.getCursor();
        document.getElementById('cursorPos').textContent = `${pos.row + 1}:${pos.column + 1}`;
    });

    // Skróty klawiszowe
    editor.commands.addCommand({
        name: 'runCode',
        bindKey: {win: 'Ctrl-Enter', mac: 'Command-Enter'},
        exec: runCode
    });

    editor.commands.addCommand({
        name: 'formatCode',
        bindKey: {win: 'Ctrl-Shift-F', mac: 'Command-Shift-F'},
        exec: formatCode
    });

    // Przełączanie widoczności niewidocznych znaków
    function toggleInvisibles() {
        const current = editor.getOption('showInvisibles');
        editor.setOption('showInvisibles', !current);
        document.querySelector('button[onclick="toggleInvisibles()"]').classList.toggle('active');
    }

    // Zarządzanie rozmiarem czcionki
    function increaseFontSize() {
        const currentSize = parseInt(editor.getFontSize());
        if (currentSize < 32) { // maksymalny rozmiar
            editor.setFontSize(currentSize + 2);
        }
    }

    function decreaseFontSize() {
        const currentSize = parseInt(editor.getFontSize());
        if (currentSize > 8) { // minimalny rozmiar
            editor.setFontSize(currentSize - 2);
        }
    }

    // Dodaj więcej skrótów klawiszowych
    editor.commands.addCommand({
        name: 'increaseFontSize',
        bindKey: {win: 'Ctrl-=', mac: 'Command-='},
        exec: increaseFontSize
    });

    editor.commands.addCommand({
        name: 'decreaseFontSize',
        bindKey: {win: 'Ctrl--', mac: 'Command--'},
        exec: decreaseFontSize
    });

    editor.commands.addCommand({
        name: 'toggleInvisibles',
        bindKey: {win: 'Ctrl-I', mac: 'Command-I'},
        exec: toggleInvisibles
    });

    // Dodaj funkcję wyszukiwania
    editor.commands.addCommand({
        name: 'find',
        bindKey: {win: 'Ctrl-F', mac: 'Command-F'},
        exec: (editor) => {
            ace.require('ace/ext/searchbox').Search(editor);
        }
    });

    // Dodaj funkcję zamiany
    editor.commands.addCommand({
        name: 'replace',
        bindKey: {win: 'Ctrl-H', mac: 'Command-Option-F'},
        exec: (editor) => {
            ace.require('ace/ext/searchbox').Search(editor, true);
        }
    });

    // Dodaj nową funkcję do zmiany czcionki
    function changeFont(fontFamily) {
        const elements = editor.container.querySelectorAll('.ace_text-layer, .ace_line, .ace_content, .ace_gutter');
        elements.forEach(element => {
            element.style.fontFamily = `${fontFamily}, monospace`;
        });
        
        editor.setOptions({
            fontFamily: fontFamily,
            enableLigatures: ['Cascadia Code', 'Fira Code', 'JetBrains Mono'].includes(fontFamily)
        });
        
        // Włącz/wyłącz ligatury w zależności od czcionki
        if (['Cascadia Code', 'Fira Code', 'JetBrains Mono'].includes(fontFamily)) {
            editor.container.style.fontFeatureSettings = "'liga' on, 'calt' on";
        } else {
            editor.container.style.fontFeatureSettings = "normal";
        }
        
        localStorage.setItem('preferredFont', fontFamily);
        editor.renderer.updateFontSize();
        editor.renderer.updateText();
    }
    
    // Wczytaj zapisaną preferencję czcionki przy starcie
    const savedFont = localStorage.getItem('preferredFont');
    
    if (savedFont) {
        document.getElementById('fontSelector').value = savedFont;
        changeFont(savedFont);
    } else {
        // Jeśli nie ma zapisanej czcionki, ustaw stan ligatur dla domyślnej czcionki
        editor.container.style.fontFeatureSettings = "'liga' on, 'calt' on";
    }

    // Funkcja aktualizująca informacje o podglądzie
    function updatePreviewInfo() {
        const iframe = document.getElementById('output');
        try {
            const iframeWindow = iframe.contentWindow;
            const iframeDoc = iframeWindow.document;
            
            // Próbujemy odczytać tytuł z różnych miejsc
            let title = 'Podgląd';
            if (iframeDoc.title) {
                title = iframeDoc.title;
            } else {
                const titleElement = iframeDoc.querySelector('title');
                if (titleElement) {
                    title = titleElement.textContent;
                }
            }
            
            const favicon = iframeDoc.querySelector('link[rel*="icon"]');
            
            document.getElementById('previewTitle').textContent = title;
            
            const faviconElement = document.getElementById('previewFavicon');
            if (favicon && favicon.href) {
                faviconElement.src = favicon.href;
                faviconElement.style.display = 'block';
            } else {
                faviconElement.style.display = 'none';
            }
        } catch (e) {
            console.error('Błąd podczas aktualizacji informacji o podglądzie:', e);
            document.getElementById('previewTitle').textContent = 'Podgląd';
        }
    }
  </script>
  <?php include 'includes/chat-widget.php'; ?>
  <script src="js/chat-widget.js"></script>
</body>
</html>
