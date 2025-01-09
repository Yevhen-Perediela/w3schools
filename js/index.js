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
        
            for (let i = 0; i < btns.length; i++) {
                btns[i].addEventListener('click', function() {
                    console.log('Button clicked:', btns[i]);
                    localStorage.setItem('userCode', 'hijch');
                    window.location.href = 'edytor.php';
                });
            }
        });
        
        
