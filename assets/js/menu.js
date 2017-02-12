let menuBtn = document.getElementById('menuBtn');
let menu = document.getElementById('menu');
let deleteEl = menu.querySelector('.delete');

export default function init() {

    deleteEl.addEventListener('click', (e) => {
        e.preventDefault();
        menu.classList.remove('is-active');
        document.documentElement.classList.remove('no-scroll');
    });

    menuBtn.addEventListener('click', (e) => {
        e.preventDefault();
        menu.classList.add('is-active');
        document.documentElement.classList.add('no-scroll');
    });
}
