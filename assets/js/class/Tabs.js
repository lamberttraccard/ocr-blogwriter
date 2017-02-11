const defaults = {
    start: 0
};

class Tabs {

    static extend(a, b) {
        for (let key in b) {
            if (b.hasOwnProperty(key)) {
                a[key] = b[key];
            }
        }
        return a;
    }

    constructor(el, options) {
        this.el = el;
        this.options = Tabs.extend(defaults, options);
        this.init();
    }

    init() {
        // tabs elements
        this.tabs = this.el.querySelectorAll('.tabs__nav > ul > li');
        // content items
        this.items = this.el.querySelectorAll('.tabs__container > section');
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
            this.tabs[this.current].className = '';
            this.items[this.current].className = '';
        }
        // change current
        this.current = idx != undefined ? idx : this.options.start >= 0 && this.options.start < this.items.length ? this.options.start : 0;
        this.tabs[this.current].className = 'tab-current';
        this.items[this.current].className = 'content-current';
    };
}

export default Tabs;