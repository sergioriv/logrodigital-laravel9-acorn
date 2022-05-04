/**
 *
 * Checkall
 * A small class that connects a check all button to elements that contains checkboxes.
 *
 * @param {element} button Check all button, should contain a target attribute that points to the container of elements which have checkboxes
 * @param {string} itemSelector Item blocks inside the container
 * @param {string} clickSelector Clickable content within the contentSelector(checkbox, card itself or any other)
 * @param {string} selectedClass Class name to add to the selected items
 *
 * @param {string} data-target Attribute of the element to point the list item container
 *
 * @method update Removes and adds listeners again to provide a way to update.
 * @method reset Clears all checkboxes
 * @method destroy Removes listeners
 *
 *
 */

class Checkall {
  get options() {
    return {
      itemSelector: '.card',
      clickSelector: '.card',
      selectedClass: 'selected',
    };
  }

  constructor(button, options = {}) {
    this.settings = Object.assign(this.options, options);
    this._button = button;
    this._contentSelector = this._button.getAttribute('data-target');
    this._contentContainer = document.querySelector(this._contentSelector);
    if (!this._button || !this._contentContainer) {
      console.error('Checkall initialization error');
      return;
    }
    this._onCheckAllClick = this._onCheckAllClick.bind(this);
    this._onCheckChange = this._onCheckChange.bind(this);
    this._onSingleCheckClick = this._onSingleCheckClick.bind(this);
    this._init();
  }

  _init() {
    this._addListeners();
  }

  _addListeners() {
    this._button.addEventListener('click', this._onCheckAllClick);
    this._button.querySelector('input') && this._button.querySelector('input').addEventListener('change', this._onCheckChange);
    this._contentContainer.addEventListener('click', this._onSingleCheckClick);
  }

  _onCheckAllClick(event) {
    event.currentTarget.querySelector('input').click();
  }

  _onCheckChange(event) {
    const currentTarget = event.currentTarget;
    const isCheckedAll = currentTarget.checked;
    if (isCheckedAll) {
      this._checkAllRows();
    } else {
      this._unCheckAllRows();
    }
    this._controlCheckAll();
  }

  _checkAllRows() {
    this._contentContainer.querySelectorAll(this.settings.itemSelector).forEach((el) => {
      el.classList.add(this.settings.selectedClass);
    });
    this._contentContainer.querySelectorAll(this.settings.itemSelector + ' .form-check input').forEach((el) => {
      el.checked = true;
    });
  }

  _unCheckAllRows() {
    this._contentContainer.querySelectorAll(this.settings.itemSelector).forEach((el) => {
      el.classList.remove(this.settings.selectedClass);
    });
    this._contentContainer.querySelectorAll(this.settings.itemSelector + ' .form-check input').forEach((el) => {
      el.checked = false;
    });
  }

  _controlCheckAll() {
    let anyChecked = false;
    let allChecked = true;
    this._contentContainer.querySelectorAll('.form-check input').forEach((el) => {
      if (el.checked) {
        anyChecked = true;
      } else {
        allChecked = false;
      }
    });
    const input = this._button.querySelector('input');
    if (anyChecked) {
      input.indeterminate = anyChecked;
    } else {
      input.indeterminate = anyChecked;
      input.checked = anyChecked;
    }
    if (allChecked) {
      input.indeterminate = false;
      input.checked = allChecked;
    }
  }

  _onSingleCheckClick(event) {
    // Allowing clicking on a and img tags
    if (event.target.tagName === 'A' || event.target.tagName === 'IMG') {
      return true;
    }
    event.preventDefault();
    if (event.target.closest(this.settings.clickSelector)) {
      const currentTarget = event.target.closest(this.settings.itemSelector);
      currentTarget.classList.toggle(this.settings.selectedClass);
      const checkbox = currentTarget.querySelector('.form-check input');
      checkbox.checked = !checkbox.checked;
      checkbox.dispatchEvent(new Event('change'));
      this._controlCheckAll();
    }
  }

  _removeListeners() {
    this._button.removeEventListener('click', this._onCheckAllClick);
    this._button.querySelector('input').removeEventListener('change', this._onCheckChange);
    this._contentContainer.removeEventListener('click', this._onSingleCheckClick);
  }

  reset() {
    this._unCheckAllRows();
    this._controlCheckAll();
  }

  update() {
    this._removeListeners();
    this._addListeners();
  }

  destroy() {
    this._removeListeners();
  }
}
