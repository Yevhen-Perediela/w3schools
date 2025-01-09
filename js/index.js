var code_ex_js = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Kurs HTML</title>
            </head>
            <body>
                <h1>To jest nagłówek</h1>
                <p id='demo'>To jest paragraf</p>
                <script>
                    function Demo() {
                        let x = document.getElementById("demo");
                        x.style.fontSize = "25px"; 
                        x.style.color = "red"; 
                    }
                    Demo()
                </script>
            </body>
            </html>
            `;


        var code_ex_html = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Kurs HTML</title>
            </head>
            <body>
                <h1>To jest nagłówek</h1>
                <p>To jest paragraf</p>
            </body>
            </html>
        `

        var code_ex_css = `
            <!DOCTYPE html>
            <html>
            <head>
                <title>Kurs HTML</title>
                <style>
                    body {
                        background-color: lightblue;
                    }

                    h1 {
                        color: white;
                        text-align: center;
                    }

                    p {
                        font-family: verdana;
                        font-size: 20px;
                    }
                </style>        
            </head>
            <body>
                <h1>To jest nagłówek</h1>
                <p>To jest paragraf</p>
            </body>
            </html>
        `
        document.addEventListener('DOMContentLoaded', function() {
            var btns = document.querySelectorAll('.edytor-link');
            console.log('Buttons found:', btns);
            lang = [code_ex_html, code_ex_css, code_ex_js]
            for (let i = 0; i < btns.length; i++) {
                btns[i].addEventListener('click', function() {
                    console.log('Button clicked:', btns[i]);

                    localStorage.setItem('userCode', lang[i]);
                    window.location.href = 'edytor.php';
                });
            }
        });
        
        
