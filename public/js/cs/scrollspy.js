/**
 *
 * Scrollspy
 *
 * @param {string} spyDropdownId Id of dropdown that the menu will be copied into
 * @param {string} spyMenuId Id of the menu
 *
 * @param {string} "data-width" Can be defined and passed via html, default value is 220px. Eg: <div class="col-md-auto d-none d-lg-block" id="scrollSpyMenu" data-width="140px">
 *
 */

class ScrollSpy {
  get options() {
    return {
      spyDropdownId: 'scrollSpyDropdown',
      spyMenuId: 'scrollSpyMenu',
    };
  }

  constructor(options = {}) {
    this.settings = Object.assign(this.options, options);
    this.spyDropdown = document.getElementById(this.settings.spyDropdownId);
    this.spyMenu = document.getElementById(this.settings.spyMenuId);
    this._init();
  }

  _init() {
    if (!this.spyMenu || !this.spyMenu.querySelector('ul') || !this.spyDropdown) {
      return;
    }
    this.menuPlain = this.spyMenu.querySelector('ul').outerHTML;
    this._copyMenu();
    this._setWidth();
    setTimeout(() => {
      this._reScroll();
    }, 30);
  }

  // Setting width via data-width attribute
  _setWidth() {
    if (this.spyMenu.getAttribute('data-width')) {
      this.spyMenu.style.width = this.spyMenu.getAttribute('data-width');
      this.spyDropdown.style.width = this.spyMenu.getAttribute('data-width');
    }
  }

  // Duplicate the menu for mobile dropdown
  _copyMenu() {
    this.spyDropdown.innerHTML = '';
    this.spyDropdown.insertAdjacentHTML('beforeend', this.menuPlain);
  }

  // Rescrolling to fix a firefox issue
  _reScroll() {
    if (!window.location.hash) {
      return;
    }
    const hash = window.location.hash;
    document.querySelector(hash).scrollIntoView();
  }
}
