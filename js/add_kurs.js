let imageCount = 0;

document.getElementById('main-control').onclick = function(event) {
    const menu = document.getElementById('menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    menu.style.left = event.pageX + 'px';
    menu.style.top = (event.pageY + 20) + 'px';
    menu.onclick = function(e) {
        const type = e.target.getAttribute('data-type');
        if (type != 'image') {
            addElement(type, 'end', '');
        }else{
            addImage('end', '')
        }
    };
    event.stopPropagation();
};

function addElement(type, where, clickedButton) {
    let element;
    if (type === 'textarea') {
        element = document.createElement('textarea');
        element.placeholder = 'Wpisz text..';
        element.oninput = function() {
            this.style.height = '';
            this.style.height = this.scrollHeight + 'px';
        };
    } else if (type === 'h1' || type === 'h2' || type === 'h3') {
        element = document.createElement('input');
        element.contentEditable = true;
        element.placeholder = type === 'h1' ? 'Wpisz H1...' : type === 'h2' ? 'Wpisz H2...' : 'Wpisz H3...';
        element.className = 'header-input ' + type;
    }

    const lineBreak = document.createElement('div');
    lineBreak.className = 'element-container';
    lineBreak.appendChild(element);

    const parentElement = document.getElementById('main-container');

    if (where === 'end') {
        parentElement.appendChild(lineBreak);
    } else {
        const currentElement = clickedButton.closest('.element-container');
        parentElement.insertBefore(lineBreak, currentElement.nextSibling);
    }

    document.getElementById('menu').style.display = 'none';
    addAddButtonToElement(lineBreak);
}


function addImage(where, clickedButton) {
    const input = document.createElement('input');
    input.type = 'file';
    input.accept = 'image/*';
    input.multiple = true;
    input.onchange = function(event) {
        const files = event.target.files;

        for (let i = 0; i < files.length; i++) {
            const file = files[i];

            // Tworzymy obiekt FormData do przesłania na serwer
            const formData = new FormData();
            formData.append('image', file);

            // Wyślij plik na serwer za pomocą POST
            fetch('upload.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.json())
            .then(data => {
                console.log('Odpowiedź z serwera:', data);
                // Jeśli serwer zwróci URL do obrazu, tworzymy element <img>
                if (data.success) {
                    const imageUrl = data.imageUrl; // URL obrazu zwrócony przez serwer

                    const imageContainer = document.createElement('div');
                    imageContainer.className = 'image-container';

                    const img = document.createElement('img');
                    img.src = imageUrl; // Użyj URL obrazu z serwera
                    const imageWrapper = document.createElement('div');
                    imageWrapper.className = 'image-wrapper';
                    imageContainer.appendChild(imageWrapper);
                    imageWrapper.appendChild(img);
                    
                    // Dodaj uchwyt zmiany rozmiaru po załadowaniu obrazu
                    makeResizableImage(img);

                    const lineBreak = document.createElement('div');
                    lineBreak.appendChild(imageContainer);
                    lineBreak.className = 'element-container';
                    document.getElementById('main-container').appendChild(lineBreak);

                    // Dodaj przycisk "Dodaj" do elementu
                    addAddButtonToElement(lineBreak);
                } else {
                    alert('Błąd przesyłania obrazu');
                }
            })
            .catch(error => {
                console.error('Błąd przesyłania obrazu:', error);
            });
        }

        document.getElementById('menu').style.display = 'none';
    };

    input.click();
}



function showAddButton(event) {
    const addButton = event.currentTarget.querySelector('.add-button');
    addButton.style.display = 'block';
}

function hideAddButton(event) {
    const addButton = event.currentTarget.querySelector('.add-button');
    addButton.style.display = 'none';
}

function addAddButtonToElement(element) {
    const addButton = document.createElement('button');
    addButton.className = 'add-button';
    addButton.innerText = 'Dodaj';
    addButton.onclick = function(event) {
        const menu = document.getElementById('menu');
        menu.style.display = 'block';
        menu.style.left = event.pageX + 'px';
        menu.style.top = (event.pageY + 20) + 'px';

        menu.onclick = function(e) {
            const type = e.target.getAttribute('data-type');
            if (type != 'image') {
                addElement(type, 'after', addButton);
            }else{
                addImage('after', addButton)
            }
        };

        event.stopPropagation();
    };

    element.appendChild(addButton);
}


document.querySelectorAll('#element > div').forEach(function(el) {
    el.classList.add('element-container');
    addAddButtonToElement(el);
});

document.addEventListener('click', function() {
    const menu = document.getElementById('menu');
    menu.style.display = 'none';
});
function makeResizableImage(img) {
    // Dodanie uchwytu
    const resizer = document.createElement('div');
    resizer.classList.add('resizer');
    const resizeImg = document.createElement('img')
    resizeImg.src = 'assets/img/resize.png'
    resizer.appendChild(resizeImg)
    
    // Dodaj uchwyt zmiany rozmiaru do wrappera obrazu
    const imageWrapper = img.closest('.image-wrapper');
    imageWrapper.appendChild(resizer);

    // Funkcjonalność zmiany rozmiaru
    resizer.addEventListener('mousedown', function (e) {
        e.preventDefault();

        const startX = e.clientX;
        const startY = e.clientY;
        const startWidth = img.offsetWidth;
        const startHeight = img.offsetHeight;
        const startRatio = startWidth / startHeight; // Zachowanie proporcji

        function resize(e) {
            // Oblicz nową szerokość i wysokość
            const newWidth = startWidth + (e.clientX - startX);
            const newHeight = newWidth / startRatio; // Wysokość zmienia się w zależności od szerokości

            img.style.width = newWidth + 'px';
            img.style.height = newHeight + 'px';
        }

        function stopResize() {
            document.removeEventListener('mousemove', resize);
            document.removeEventListener('mouseup', stopResize);
        }

        document.addEventListener('mousemove', resize);
        document.addEventListener('mouseup', stopResize);
    });
}




function generateJSON() {
    const elements = document.querySelectorAll('#main-container .element-container');
    const data = [];

    elements.forEach(element => {
        const obj = {};
        const child = element.firstChild;

        if (child.tagName === 'TEXTAREA') {
            obj.type = 'textarea';
            obj.content = child.value || child.placeholder;
            obj.styles = { height: child.style.height || 'auto' };
        } else if (child.tagName === 'INPUT') {
            obj.type = child.classList.contains('h2') ? 'h2' :
                       child.classList.contains('h3') ? 'h3' : 'h1';
            obj.content = child.value || child.placeholder;
            obj.styles = { class: child.className };
        } else if (child.classList.contains('image-container')) {
            obj.type = 'image-container';
            obj.images = [];
            const images = child.querySelectorAll('img');
            images.forEach(img => {
                obj.images.push({
                    src: img.src,
                    width: img.style.width || 'auto',
                    height: img.style.height || 'auto'
                });
            });
        }

        data.push(obj);
    });

    return JSON.stringify(data, null, 2);
}



function sendToBd(){
    const jsonData = generateJSON();

    fetch('save_kurs.php', {
        method: 'POST',
        headers: { 'Content-Type': 'application/json' },
        body: jsonData
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            alert('Kurs zapisany pomyślnie!');
        } else {
            alert('Wystąpił błąd podczas zapisu.');
        }
    })
    .catch(error => console.error('Błąd:', error));

}