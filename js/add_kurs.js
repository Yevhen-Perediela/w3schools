let imageCount = 0;

document.getElementById('main-control').onclick = function(event) {
    const menu = document.getElementById('menu');
    menu.style.display = menu.style.display === 'block' ? 'none' : 'block';
    menu.style.left = event.pageX + 'px';
    menu.style.top = (event.pageY + 20) + 'px';
    event.stopPropagation();
};

function addElement(type) {
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
    lineBreak.appendChild(element);
    document.getElementById('element').appendChild(lineBreak);
    document.getElementById('menu').style.display = 'none';
}

function addImage() {
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
            };
            reader.readAsDataURL(file);
        }

        const lineBreak = document.createElement('div');
        lineBreak.appendChild(imageContainer);
        document.getElementById('element').appendChild(lineBreak);
    };
    input.click();
}

document.addEventListener('click', function() {
    const menu = document.getElementById('menu');
    menu.style.display = 'none';
});