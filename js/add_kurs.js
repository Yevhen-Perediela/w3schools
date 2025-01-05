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

    const parentElement = document.getElementById('element');

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
        const imageContainer = document.createElement('div');
        imageContainer.className = 'image-container';

        for (let i = 0; i < files.length; i++) {
            const file = files[i];
            const reader = new FileReader();
            reader.onload = function(e) {
                const img = document.createElement('img');
                img.src = e.target.result;
                imageContainer.appendChild(img);
                makeResizableImage(img);
            };
            reader.readAsDataURL(file);
        }

        const lineBreak = document.createElement('div');
        lineBreak.appendChild(imageContainer);
        document.getElementById('element').appendChild(lineBreak);
        const parentElement = document.getElementById('element');

        if (where === 'end') {
            parentElement.appendChild(lineBreak);
        } else {
            const currentElement = clickedButton.closest('.element-container');
            parentElement.insertBefore(lineBreak, currentElement.nextSibling);
        }
        // makeResizable(imageContainer);
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
    img.parentElement.style.position = 'relative'; // Pozycjonowanie rodzica
    img.parentElement.appendChild(resizer);

    // Funkcjonalność zmiany rozmiaru
    resizer.addEventListener('mousedown', function (e) {
        e.preventDefault();

        const startX = e.clientX;
        const startY = e.clientY;
        const startWidth = img.offsetWidth;
        const startHeight = img.offsetHeight;

        function resize(e) {
            const newWidth = startWidth + (e.clientX - startX);
            const newHeight = startHeight + (e.clientY - startY);

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

