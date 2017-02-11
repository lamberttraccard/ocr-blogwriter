import Tabs from "./class/Tabs";
import Notification from "./class/Notification";


//Notification
new Notification();

// Tabs
let tabsEl = document.getElementById('tabs');
let tabs = tabsEl ? new Tabs() : null;
