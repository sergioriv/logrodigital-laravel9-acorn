/**
 *
 * SingleImageUpload
 * A simple component that creates a single image selector and provides the image data to upload.
 * Requires a button, a file input and an img tag.
 *
 * @method getFile Returns the file to attach to the request
 * @callback options.fileSelectCallback Provides the file upon selection
 *
 */

class SingleImageUpload {
  get options() {
    return {
      fileSelectCallback: null,
    };
  }

  constructor(element, options = {}) {
    this.settings = Object.assign(this.options, options);
    this.element = element;
    if (!element) {
      console.error('Single image upload element is not defined!');
      return;
    }
    this._init();
  }

  _init() {
    this.button = this.element.getElementsByTagName('button')[0];
    this.input = this.element.getElementsByTagName('input')[0];
    this.img = this.element.getElementsByTagName('img')[0];
    this._addListeners();
  }

  _addListeners() {
    this.button && this.button.addEventListener('click', this._onSelectButtonClick.bind(this));
    this.input && this.input.addEventListener('change', this._onInputChange.bind(this));
  }

  _onSelectButtonClick(event) {
    this.input.dispatchEvent(new MouseEvent('click'));
  }

  _onInputChange(event) {
    this._fileSelected();
  }

  _fileSelected() {
    if (this.input.files && this.input.files[0]) {
      var reader = new FileReader();
      reader.onload = this._onFileLoad.bind(this);
      reader.readAsDataURL(this.input.files[0]);
    }
  }

  _onFileLoad(event) {
    this.img.setAttribute('src', event.target.result);
    if (this.settings.fileSelectCallback) {
      let file = this.input.files[0];
      this.settings.fileSelectCallback({file: file, result: event.target.result});
    }
  }

  getFile() {
    return this.input.files[0];
  }
}
