/**
 *
 * ResponsiveTab
 *
 * Turns Bootstrap tab navigation to a better responsive component with a dropdown.
 *
 */

class ResponsiveTab {
  get options() {
    return {};
  }

  constructor(element, options = {}) {
    if (!element) {
      console.log('TabsExtend element is null!');
      return;
    }
    this.settings = Object.assign(this.options, options);
    this.settings = Object.assign(this.settings, element.dataset);
    this.element = element;
    this.maxHeight = this.element.querySelector('.nav-item').offsetHeight + 5;
    this.currentHeight = this.element.offsetHeight;
    this._init();
  }

  _init() {
    this._addListeners();
    setTimeout(() => {
      this._check();
    }, 100);
  }

  _check() {
    if (!this.element.querySelector('.dropdown')) {
      console.log('ResponsiveTab dropdown is null!');
      return;
    }
    this.currentHeight = this.element.offsetHeight;
    const dropdown = this.element.querySelector('.responsive-tab-dropdown');
    const dropdownMenu = dropdown.querySelector('.dropdown-menu');

    if (this.currentHeight > this.maxHeight) {
      dropdown.classList.remove('d-none');

      while (this.currentHeight > this.maxHeight) {
        const liElements = this.element.querySelectorAll(':scope>li:not(:last-child)');
        const lastElement = liElements[liElements.length - 1];
        this.element.removeChild(lastElement);
        dropdownMenu.insertBefore(lastElement, dropdownMenu.firstChild);
        this.currentHeight = this.element.offsetHeight;
      }
    } else {
      let collapsedOnes = dropdownMenu.querySelectorAll('li');
      while (this.currentHeight <= this.maxHeight && collapsedOnes.length > 0 && this.element.querySelectorAll(':scope>li').length > 0) {
        collapsedOnes = dropdownMenu.querySelectorAll('li');
        const collapsedToMove = collapsedOnes[0];
        dropdownMenu.removeChild(collapsedToMove);
        this.element.insertBefore(collapsedToMove, this.element.lastElementChild);
        this.currentHeight = this.element.offsetHeight;
        collapsedOnes = dropdownMenu.querySelectorAll('li');
      }

      if (this.currentHeight > this.maxHeight) {
        this._check();
      }
      if (dropdownMenu.querySelectorAll('li').length === 0) {
        dropdown.classList.add('d-none');
      }
    }
  }

  _onResize() {
    this._check();
  }

  _menuPlacementChange(event) {
    this._check();
  }

  _onAnchorClick(event) {
    if (event.currentTarget.classList.contains('nav-link')) {
      this.element.querySelectorAll('.nav-item .nav-link').forEach((el) => {
        el.classList.remove('active');
      });
    }
    event.currentTarget.classList.add('active');
  }

  _addListeners() {
    window.addEventListener('resize', this._onResize.bind(this));
    document.documentElement.addEventListener(Globals.menuPlacementChange, this._menuPlacementChange.bind(this));
    this.element.querySelectorAll('.nav-item .nav-link').forEach((element) => {
      element.addEventListener('click', this._onAnchorClick.bind(this));
    });
  }
}
