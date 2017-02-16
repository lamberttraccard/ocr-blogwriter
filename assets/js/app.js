import Tabs from "./class/Tabs";
import Modal from "./class/Modal";
import axios from 'axios';
import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/modern/theme';
import 'tinymce/plugins/link/plugin';
import 'tinymce/plugins/autoresize/plugin';
import 'tinymce/plugins/paste/plugin';

// Init menu
let menuBtn = document.getElementById('menuBtn');
let menu = document.getElementById('menu');
let deleteEl = menu.querySelector('.delete');

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

// Init Notification message
let notifications = document.querySelectorAll('.notification');

notifications.forEach(notification => {
    let deleteEl = notification.querySelector('.delete');
    deleteEl.addEventListener('click', (e) => {
            e.preventDefault();
            notification.remove();
        }
    )
});

// Display tabs elements
let tabsEl = document.getElementById('tabs');
let tabs = tabsEl ? new Tabs() : null;

// Remove confirmation modal
let confirmationEls = document.querySelectorAll('.button.is-danger');

confirmationEls.forEach((el) => {
    el.addEventListener('click', (e) => {
        e.preventDefault();

        new Modal({
            title: 'Voulez-vous vraiment supprimer ?',
            content: 'Vous ne pourrez pas revenir en arrière.',
            abortButton: true,
            abortButtonClass: 'is-primary',
            successButtonClass: 'is-danger',
            successButtonText: 'Supprimer',
            successCallback: () => window.location = el.href,
        }).open();
    })
});

// Init TinyMce
tinymce.init({selector: '.tinymce', skin: false, plugins: ['paste', 'link', 'autoresize']});


// Check if username is available
let form = document.querySelector('.register form');

if (form) {

    let input = form.querySelector('#user_username');
    let button = form.querySelector('button');

    let shouldSubmit = 'true';
    let message = 'Le pseudo est déjà utilisé';
    let ul = document.createElement('ul');
    let li = document.createElement('li');
    li.innerText = message;
    ul.appendChild(li);

    input.addEventListener('keyup', function () {
        checkUsername(this.value);
    });

    form.addEventListener('submit', function (e) {
        if (!shouldSubmit) {
            e.preventDefault();
        }
        else {
            this.submit();
        }
    });

    function checkUsername(username) {
        axios.post('/api/user', {
            username: username
        })
            .then(() => {
                button.classList.add('is-disabled');
                input.insertAdjacentElement('beforebegin', ul);
                shouldSubmit = false;
            })
            .catch(() => {
                button.classList.remove('is-disabled');
                ul.remove();
                shouldSubmit = true;
            });
    }
}