const defaults = {
    tabsSelector: '#tabs ul > li',
    itemsSelector: '#tabs-container > section',
    tabActiveClass: 'is-active',
    itemActiveClass: 'is-active',
    start: 0
};

export default class Tabs {

    static extend(a, b) {
        for (let key in b) {
            if (b.hasOwnProperty(key)) {
                a[key] = b[key];
            }
        }
        return a;
    }

    constructor(options) {
        this.options = Tabs.extend(defaults, options);
        this.init();
    }

    init() {
        // tabs elements
        this.tabs = document.querySelectorAll(this.options.tabsSelector);
        // content items
        this.items = document.querySelectorAll(this.options.itemsSelector);
        // current index
        this.current = -1;
        // show current content item
        this.show();
        // init events
        this.initEvents();
    };

    initEvents() {
        let self = this;
        this.tabs.forEach(function (tab, idx) {
            tab.addEventListener('click', function (ev) {
                ev.preventDefault();
                self.show(idx);
            });
        });
    };

    show(idx) {
        if (this.current >= 0) {
            this.tabs[this.current].classList.remove(this.options.tabActiveClass);
            this.items[this.current].classList.remove(this.options.itemActiveClass);
        }
        // change current
        this.current = idx != undefined ? idx : this.options.start >= 0 && this.options.start < this.items.length ? this.options.start : 0;
        this.tabs[this.current].classList.add(this.options.tabActiveClass);
        this.items[this.current].classList.add(this.options.itemActiveClass);
    };
}