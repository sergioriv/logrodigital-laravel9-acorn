/**
 *
 * DatatableExtend
 * A basic utility that is responsible for interface actions & keyboard shortcuts for selecting rows, search, export, print and page size elements
 *
 *
 * @param {Datatable} datatable Datatable instance to fire controls
 * @param {Object} editRowCallback Click callback for anchor element of a row, passes the row as a param
 * @param {Object} singleSelectCallback Callback for selecting single list item
 * @param {Object} multipleSelectCallback Callback for selecting multiple list items
 * @param {Object} anySelectCallback Callback for selecting at least one of the list items
 * @param {Object} noneSelectCallback Callback for selecting none of the list items
 * @param {Object} lengthChangeCallback Callback for page length change
 *
 * @method controlCheckAll Controls the rows and determines if all checked, any checked or none checked
 * @method unCheckAllRows Makes all the rows unselected
 * @method checkAllRows Makes all the rows selected
 * @method getSelectedRows Returns selected rows
 *
 *
 */

class DatatableExtend {
  get options() {
    return {
      datatable: null,
      editRowCallback: null,
      singleSelectCallback: null,
      anySelectCallback: null,
      noneSelectCallback: null,
      multipleSelectCallback: null,
      lengthChangeCallback: null,
    };
  }

  constructor(options = {}) {
    this.settings = Object.assign(this.options, options);
    this.datatable = this.settings.datatable; // Datatable instance passed via settings
    if (this.datatable) {
      this.element = this.datatable.table().container();
    }
    this._init();
  }

  _init() {
    this._addListeners();
    this._addShortcuts();
  }

  _addListeners() {
    // Check all button change listener
    if (document.getElementById('datatableCheckAll')) {
      document.getElementById('datatableCheckAll').addEventListener('change', this._onCheckAllChange.bind(this));
    }

    // Listener for top right check all
    if (document.getElementById('datatableCheckAllButton')) {
      document.getElementById('datatableCheckAllButton').addEventListener('click', this._onCheckAllButtonClick.bind(this));
    }

    // Click listeners for rows to make them selected or show the edit modal
    if (this.element) {
      this.element.querySelectorAll('tbody').forEach((el) => el.addEventListener('click', this._onRowClick.bind(this)));
    }

    // Search listeners
    document.querySelectorAll('.datatable-search').forEach((el) => {
      el.addEventListener('keyup', this._onSearchKeyup.bind(this));
    });
    document.querySelectorAll('.search-delete-icon').forEach((el) => {
      el.addEventListener('click', this._onSearchDelete.bind(this));
    });

    // Export listeners
    document.querySelectorAll('.datatable-export .dropdown-item').forEach((el) => {
      el.addEventListener('click', this._onExportClick.bind(this));
    });

    // Print listeners
    document.querySelectorAll('.datatable-print').forEach((el) => {
      el.addEventListener('click', this._onPrintClick.bind(this));
    });

    // Length listeners
    document.querySelectorAll('.datatable-length .dropdown-item').forEach((el) => {
      el.addEventListener('click', this._onLengthClick.bind(this));
    });
  }

  _addShortcuts() {
    // Shortcust for ctrl+a and ctrl+d to select all and deselect all
    if (typeof Mousetrap !== 'undefined') {
      Mousetrap.bind('mod+a', (event) => {
        event.preventDefault();
        if (!this.datatable.data().any()) {
          // Only no data warning row available at this point
          return;
        }
        this.checkAllRows();
        this.controlCheckAll();
      });
      Mousetrap.bind('mod+d', (event) => {
        event.preventDefault();
        if (!this.datatable.data().any()) {
          // Only no data warning row available at this point
          return;
        }
        this.unCheckAllRows();
        this.controlCheckAll();
      });
    }
  }

  _onRowClick(event) {
    event.preventDefault();
    if (!this.datatable.data().any()) {
      // Only no data warning row available at this point
      return;
    }
    const currentTarget = event.target.closest('tr');
    if (event.target.tagName === 'A') {
      // Title clicked. Showing the edit view.
      this.unCheckAllRows();
      this.settings.editRowCallback(this.datatable.row(currentTarget));
      return true;
    }
    currentTarget.classList.toggle('selected');
    const checkbox = currentTarget.querySelector('.form-check input');
    checkbox.checked = !checkbox.checked;
    checkbox.dispatchEvent(new Event('change'));
    this.controlCheckAll();
  }

  _onCheckAllChange(event) {
    const isCheckedAll = document.getElementById('datatableCheckAll').checked;
    if (isCheckedAll) {
      this.checkAllRows();
    } else {
      this.unCheckAllRows();
    }
    this.controlCheckAll();
  }

  _onCheckAllButtonClick(event) {
    if (!this.datatable.data().any()) {
      // Only no data warning row available at this point
      return;
    }
    const target = event.target;
    const currentTarget = event.currentTarget;
    if (!target.classList.contains('form-check-input')) {
      currentTarget.querySelector('input').click(); // Firing click event on the checkbox via the button click
    }
  }

  controlCheckAll() {
    if (!document.getElementById('datatableCheckAll')) {
      return;
    }
    let anyChecked = false;
    let allChecked = true;
    this.element.querySelectorAll('tbody tr .form-check input').forEach((el) => {
      if (el.checked) {
        anyChecked = true;
      } else {
        allChecked = false;
      }
    });
    if (this.datatable && !this.datatable.data().any()) {
      allChecked = false;
      anyChecked = false;
    }
    if (anyChecked) {
      document.getElementById('datatableCheckAll').indeterminate = anyChecked;
      this.settings.anySelectCallback && this.settings.anySelectCallback();
    } else {
      document.getElementById('datatableCheckAll').indeterminate = anyChecked;
      document.getElementById('datatableCheckAll').checked = anyChecked;
      this.settings.noneSelectCallback && this.settings.noneSelectCallback();
    }
    if (allChecked) {
      document.getElementById('datatableCheckAll').indeterminate = false;
      document.getElementById('datatableCheckAll').checked = allChecked;
    }

    if (this.element.querySelectorAll('tbody tr .form-check input:checked').length === 1) {
      this.settings.singleSelectCallback && this.settings.singleSelectCallback();
    } else {
      this.settings.multipleSelectCallback && this.settings.multipleSelectCallback();
    }
  }

  unCheckAllRows() {
    if (!this.element) {
      return;
    }
    this.element.querySelectorAll('tbody tr').forEach((el) => el.classList.remove('selected'));
    this.element.querySelectorAll('tbody tr .form-check input').forEach((el) => {
      el.checked = false;
    });
  }

  checkAllRows() {
    if (!this.element) {
      return;
    }
    this.element.querySelectorAll('tbody tr').forEach((el) => el.classList.add('selected'));
    this.element.querySelectorAll('tbody tr .form-check input').forEach((el) => {
      el.checked = true;
    });
  }

  getSelectedRows() {
    return this.datatable.rows('.selected');
  }

  _getDatatable(target) {
    const selector = target.getAttribute('data-datatable');
    return jQuery(selector).DataTable();
  }

  _onSearchKeyup(event) {
    this._getDatatable(event.currentTarget).search(event.currentTarget.value).draw();
    const searchIcon = event.currentTarget.closest('div').querySelector('.search-magnifier-icon');
    const deleteIcon = event.currentTarget.closest('div').querySelector('.search-delete-icon');
    if (event.currentTarget.value !== '') {
      deleteIcon.classList.remove('d-none');
      searchIcon.classList.add('d-none');
    } else {
      deleteIcon.classList.add('d-none');
      searchIcon.classList.remove('d-none');
    }
    this.unCheckAllRows();
    this.controlCheckAll();
  }

  _onSearchDelete(event) {
    const container = event.currentTarget.closest('div');
    const searchIcon = container.querySelector('.search-magnifier-icon');
    const deleteIcon = container.querySelector('.search-delete-icon');
    container.querySelector('input').value = '';
    this._getDatatable(container.querySelector('input')).search('').draw();
    deleteIcon.classList.add('d-none');
    searchIcon.classList.remove('d-none');
    this.unCheckAllRows();
    this.controlCheckAll();
  }

  _onPrintClick(event) {
    event.preventDefault();
    try {
      this._getDatatable(event.currentTarget).buttons(3).trigger();
    } catch (error) {
      console.log('Trigger button is not found');
    }
  }

  _onExportClick(event) {
    event.preventDefault();
    const selector = event.currentTarget.closest('.datatable-export').getAttribute('data-datatable');
    if (event.currentTarget.classList.contains('export-copy')) {
      try {
        this._getDatatable(event.currentTarget.closest('.datatable-export')).buttons(0).trigger();
      } catch (error) {
        console.log('Trigger button is not found');
      }
    }
    if (event.currentTarget.classList.contains('export-excel')) {
      try {
        this._getDatatable(event.currentTarget.closest('.datatable-export')).buttons(1).trigger();
      } catch (error) {
        console.log('Trigger button is not found');
      }
    }
    if (event.currentTarget.classList.contains('export-cvs')) {
      try {
        this._getDatatable(event.currentTarget.closest('.datatable-export')).buttons(2).trigger();
      } catch (error) {
        console.log('Trigger button is not found');
      }
    }
  }

  _onLengthClick(event) {
    event.preventDefault();
    const length = parseInt(event.currentTarget.innerHTML);
    this._getDatatable(event.currentTarget.closest('.datatable-length')).page.len(length).draw();
    this.unCheckAllRows();
    this.controlCheckAll();
    this.settings.lengthChangeCallback && this.settings.lengthChangeCallback();
  }
}
