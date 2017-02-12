import Tabs from "./class/Tabs";
import menu from "./menu";
import notification from "./notification";
import tinymce from 'tinymce/tinymce';
import 'tinymce/themes/modern/theme';
import 'tinymce/plugins/link/plugin';
import 'tinymce/plugins/autoresize/plugin';
import 'tinymce/plugins/paste/plugin';

//Init
menu();
notification();
tinymce.init({ selector: '.tinymce', skin: false, plugins: ['paste', 'link', 'autoresize'] });

let tabsEl = document.getElementById('tabs');
let tabs = tabsEl ? new Tabs() : null;
