/**
 *
 * AutocompleteCustom
 *
 * Extending autoComplete to disable up and down arrows when input is focused.
 *
 * @param {object} options Options to initialize autoComplete plugin
 * @param {string} searchInputId Id of the input to init autocomplete
 * @param {object} searchResultsId Id to give created result element
 *
 *
 */

class AutocompleteCustom {
  get options() {
    return {};
  }

  constructor(searchInputId, searchResultsId, options = {}) {
    this.settings = Object.assign(this.options, options);
    this._searchInputId = searchInputId;
    this._searchResultsId = searchResultsId;
    this._init();
    this._addListeners();
    this._autoComplete;
  }

  _init() {
    this._extendProps();
    this._autoComplete = new autoComplete(this.settings);
  }

  _extendProps() {
    this.settings.selector = '#' + this._searchInputId;

    // Using the provided ones via the settings if available
    this.settings.resultsList = this.settings.resultsList || {
      render: true,
      container: (source) => {
        source.classList.add('autocomplete-results');
        source.classList.add('dropdown-menu');
        source.setAttribute('id', this._searchResultsId);
      },
      destination: document.getElementById(this._searchInputId),
      position: 'afterend',
      element: 'div',
    };

    this.settings.resultItem = this.settings.resultItem || {
      content: (data, source) => {
        source.innerHTML = data.match;
        source.setAttribute('class', 'auto-complete-result-item dropdown-item');
      },
      element: 'a',
    };

    this.settings.noResults =
      this.settings.noResults ||
      (() => {
        const result = document.createElement('div');
        result.setAttribute('class', 'text-center');
        result.setAttribute('tabindex', '1');
        result.innerHTML = 'No Results';
        document.getElementById(this._searchResultsId).appendChild(result);
      });

    this.settings.onSelection =
      this.settings.onSelection ||
      ((feedback) => {
        document.getElementById(this._searchInputId).value = feedback.selection.value || feedback.selection.value['label'];
        document.getElementById(this._searchInputId).blur();
      });
  }

  _addListeners() {
    document.getElementById(this._searchInputId).addEventListener('focus', this._onInputFocus.bind(this));
    document.getElementById(this._searchInputId).addEventListener('focusout', this._onInputFocusOut.bind(this));
  }

  _onInputFocus() {
    document.getElementById(this._searchInputId).addEventListener('keydown', this._onKeyDown.bind(this));
    document.getElementById(this._searchInputId).addEventListener('keyup', this._onKeyUp.bind(this));
    this._onKeyUp();
  }

  _onInputFocusOut() {
    document.getElementById(this._searchInputId).removeEventListener('keydown', this._onKeyDown.bind(this));
    document.getElementById(this._searchInputId).removeEventListener('keyup', this._onKeyUp.bind(this));
    this._hideResults();
  }

  // Preventing up and down key events to go start and end of the input
  _onKeyDown(event) {
    if (event.which === 38 || event.which === 40) {
      event.preventDefault();
    }
  }

  _onKeyUp() {
    const results = document.getElementById(this._searchResultsId);
    if (results.querySelector('a') || results.querySelector('div')) {
      this._showResults();
    } else {
      this._hideResults();
    }
  }

  _showResults() {
    document.getElementById(this._searchResultsId).classList.add('show');
  }

  _hideResults() {
    document.getElementById(this._searchResultsId).classList.remove('show');
  }

  get autoComplete() {
    return this._autoComplete;
  }
}
