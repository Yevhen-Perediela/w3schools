<?php
session_start();

?>
<!DOCTYPE html>
<html lang="pl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>W4Schools - Darmowe Kursy Online</title>
    <link rel="shortcut icon" href="./assets/img/logo.png" type="image/png" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link rel="stylesheet" href="./styles/index.css">
    <link rel="stylesheet" href="./styles/header.css">
    <link rel="stylesheet" href="./styles/footer.css">
    <link rel="stylesheet" href="./styles/stars.css">
</head>
<body>
    <?php include './includes/header.php'; ?>
    
    <div class="hero-container">
        <div class="stars" id="stars"></div>
        
        <h1 class="hero-title">Naucz się programować</h1>
        <p class="hero-subtitle">Z największą stroną dla programistów na świecie.</p>
        
        <div class="hero-search-container">
            <input type="text" class="hero-search-input" placeholder="Szukaj kursów, np. HTML">
            <button class="hero-search-button">
                <i class="fas fa-search"></i>
            </button>
        </div>
        
        <a href="#wave-transition" class="scroll-down">
            <i class="fas fa-chevron-down"></i>
            <span>Zobacz więcej</span>
        </a>
    </div>

    <div class="wave-transition" id="wave-transition">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#282a35" d="M0,160L48,165C96,170,192,180,288,176C384,172,480,154,576,144C672,134,768,134,864,144C960,154,1056,176,1152,181C1248,186,1344,176,1392,170L1440,165L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
            <path fill="#d9eee1" d="M0,160L48,165C96,170,192,180,288,176C384,172,480,154,576,144C672,134,768,134,864,144C960,154,1056,176,1152,181C1248,186,1344,176,1392,170L1440,165L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <div class="html-section" id="html-section">
        <div class="html-content">
            <h1 class="html-title">HTML</h1>
            <p class="html-subtitle">Język do budowania stron internetowych</p>
            
            <div class="html-buttons">
                <a href="#" class="button button-learn">Kurs HTML</a>
            </div>
        </div>

        <div class="html-example">
            <h2>Przykład HTML:</h2>
            <div class="code-editor">
                <pre><code><span class="bracket">&lt;</span><span class="tag">!DOCTYPE </span><span class="doctype-html">html</span><span class="bracket">&gt;</span>
<span class="bracket">&lt;</span><span class="tag">html</span><span class="bracket">&gt;</span>
<span class="bracket">  &lt;</span><span class="tag">head</span><span class="bracket">&gt;</span>
<span class="bracket">    &lt;</span><span class="tag">title</span><span class="bracket">&gt;</span><span class="text">Kurs HTML</span><span class="bracket">&lt;/</span><span class="tag">title</span><span class="bracket">&gt;</span>
<span class="bracket">  &lt;/</span><span class="tag">head</span><span class="bracket">&gt;</span>
<span class="bracket">  &lt;</span><span class="tag">body</span><span class="bracket">&gt;</span>
<span class="bracket">      &lt;</span><span class="tag">h1</span><span class="bracket">&gt;</span><span class="text">To jest nagłówek</span><span class="bracket">&lt;/</span><span class="tag">h1</span><span class="bracket">&gt;</span>
<span class="bracket">      &lt;</span><span class="tag">p</span><span class="bracket">&gt;</span><span class="text">To jest paragraf</span><span class="bracket">&lt;/</span><span class="tag">p</span><span class="bracket">&gt;</span>
<span class="bracket">  &lt;/</span><span class="tag">body</span><span class="bracket">&gt;</span>
<span class="bracket">&lt;/</span><span class="tag">html</span><span class="bracket">&gt;</span></code></pre>
                <button class="try-it">Uruchom kod</button>
            </div>
        </div>
    </div>

    <div class="wave-transition">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#d9eee1" d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,90.7C672,85,768,107,864,122.7C960,139,1056,149,1152,144C1248,139,1344,117,1392,106.7L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
            <path fill="#fff4a3" d="M0,64L48,80C96,96,192,128,288,128C384,128,480,96,576,90.7C672,85,768,107,864,122.7C960,139,1056,149,1152,144C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <div class="css-section">
        <div class="html-content">
            <h1 class="css-title">CSS</h1>
            <p class="css-subtitle">Język do stylizacji stron internetowych</p>
            
            <div class="html-buttons">
                <a href="#" class="button button-learn-css">Kurs CSS</a>
            </div>
        </div>

        <div class="html-example">
            <h2>Przykład CSS:</h2>
            <div class="code-editor">
                <pre><code><span class="selector">body</span> {
    <span class="property">background-color</span>: <span class="value">lightblue</span>;
}

<span class="selector">h1</span> {
    <span class="property">color</span>: <span class="value">white</span>;
    <span class="property">text-align</span>: <span class="value">center</span>;
}

<span class="selector">p</span> {
    <span class="property">font-family</span>: <span class="value">verdana</span>;
    <span class="property">font-size</span>: <span class="value">20px</span>;
}</code></pre>
                <button class="try-it">Uruchom kod</button>
            </div>
        </div>
    </div>

    <div class="wave-transition">
        <svg viewBox="0 0 1440 320" preserveAspectRatio="none">
            <path fill="#fff4a3" d="M0,32L48,53.3C96,75,192,117,288,122.7C384,128,480,96,576,85.3C672,75,768,85,864,101.3C960,117,1056,139,1152,138.7C1248,139,1344,117,1392,106.7L1440,96L1440,0L1392,0C1344,0,1248,0,1152,0C1056,0,960,0,864,0C768,0,672,0,576,0C480,0,384,0,288,0C192,0,96,0,48,0L0,0Z"></path>
            <path fill="#282a35" d="M0,32L48,53.3C96,75,192,117,288,122.7C384,128,480,96,576,85.3C672,75,768,85,864,101.3C960,117,1056,139,1152,138.7C1248,139,1344,117,1392,106.7L1440,96L1440,320L1392,320C1344,320,1248,320,1152,320C1056,320,960,320,864,320C768,320,672,320,576,320C480,320,384,320,288,320C192,320,96,320,48,320L0,320Z"></path>
        </svg>
    </div>

    <div class="js-section">
        <div class="html-content">
            <h1 class="js-title">JS</h1>
            <p class="js-subtitle">Język do programowania stron internetowych</p>
            
            <div class="html-buttons">
                <a href="#" class="button button-learn-js">Kurs JavaScript</a>
            </div>
        </div>

        <div class="html-example">
            <h2>Przykład JavaScript:</h2>
            <div class="code-editor">
                <pre><code><span class="keyword">function</span> Demo() {
    <span class="keyword">let</span> x = document.getElementById(<span class="string">"demo"</span>);
    x.style.fontSize = <span class="string">"25px"</span>; 
    x.style.color = <span class="string">"red"</span>; 
}</code></pre>
                <button class="try-it">Uruchom kod</button>
            </div>
        </div>
    </div>

    <?php include './includes/footer.php'; ?>

    <script src="./js/stars.js"></script>
    <script>
        createStars();

        let title =document.title;
        window.addEventListener("blur", ()=>{
            document.title ="Wracaj!";
        });
        window.addEventListener("focus", ()=>{
            document.title = title;    
        })
    </script>
</body>
</html>
