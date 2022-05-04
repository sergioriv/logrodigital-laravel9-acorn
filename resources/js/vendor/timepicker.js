/**
 *
 * TimePicker
 * A simple component that turns a form input into a time picker with the help of Select2. All the settings below can be passed with data attribute on the element.
 *
 * @param {string} hours24 Comma separated hours for 24 hour time format
 * @param {string} hours12 Comma separated hours for 12 hour time format
 * @param {string} minutes Comma separated minutes for both formats
 * @param {string} format Time format, 24 or 12
 * @param {string} delimiter Separator for hour and minute. It's also used to split initial value of the input
 * @param {string} addZeros If 'true' adds a zero at the start of the numbers that smaller than 10
 * @param {string} output Can be set as 'string' or 'date'. Example date output: Fri Aug 07 2020 01:30:00 GMT+0300 (GMT+03:00)  Example string output: 01:30 or 01:30:AM
 * @param {string} classname Class to add Select2 elements.
 * @param {string} dropdownClassname Class to add Select2 dropdown elements.
 *
 */

class TimePicker {
  get options() {
    return {
      hours24: '0, 1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12, 13, 14, 15, 16, 17, 18, 19, 20, 21, 22, 23',
      hours12: '1, 2, 3, 4, 5, 6, 7, 8, 9, 10, 11, 12',
      minutes: '0, 15, 30, 45',
      format: '24',
      delimiter: ':',
      addZeros: 'true',
      output: 'string',
      classname: '',
      containerClassname: '',
    };
  }

  constructor(element, options = {}) {
    this.settings = Object.assign(this.options, options);
    this.settings = Object.assign(this.settings, element.dataset);
    this.element = element;
    this.parent = element.parentNode;
    this.hoursSelect;
    this.minutesSelect;
    this.amPmSelect;

    this._init();
    this._initStartValue();
  }

  _initStartValue() {
    if (this.element.value === '') {
      return;
    }
    let values = this.element.value.split(this.settings.delimiter);
    let hours = values[0] + '';
    let minutes = values[1] + '';
    this.hoursSelect.val(hours).trigger('change');
    this.minutesSelect.val(minutes).trigger('change');
    if (this.settings.format === '12') {
      this.amPmSelect.val(values[2]).trigger('change');
    }
  }

  _init() {
    if (this.settings.format === '12') {
      this.hoursArray = this.settings.hours12.replace(/\s/g, '').split(',');
    } else {
      this.hoursArray = this.settings.hours24.replace(/\s/g, '').split(',');
    }
    this.minutesArray = this.settings.minutes.replace(/\s/g, '').split(',');
    this._addSelects();
    this._addListeners();
  }

  _addListeners() {
    this.minutesSelect.on('change', (e) => {
      this._updateInput();
    });

    this.hoursSelect.on('change', (e) => {
      this._updateInput();
    });

    this.minutesSelect.on('select2:open', (e) => {
      this._onOpen();
    });

    this.hoursSelect.on('select2:close', (e) => {
      this._onClose();
    });

    this.minutesSelect.on('select2:close', (e) => {
      this._onClose();
    });

    this.hoursSelect.on('select2:open', (e) => {
      this._onOpen();
    });

    if (this.amPmSelect) {
      this.amPmSelect.on('change', (e) => {
        this._updateInput();
      });
      this.amPmSelect.on('select2:open', (e) => {
        this._onOpen();
      });
      this.amPmSelect.on('select2:close', (e) => {
        this._onClose();
      });
    }
  }

  _updateInput() {
    let hours = parseInt(this.hoursSelect.val() || 0);
    let minutes = parseInt(this.minutesSelect.val() || 0);
    let ampm;
    if (this.settings.format === '12') {
      ampm = this.amPmSelect.val();
      if (ampm === 'PM') {
        hours = parseInt(hours) + 12;
      }
    }

    if (this.settings.output === 'date') {
      var date = new Date();
      date.setHours(hours);
      date.setMinutes(minutes);
      date.setSeconds(0);
      this.element.value = date;
    } else {
      this.element.value = hours + this.settings.delimiter + minutes;
      if (this.settings.addZeros === 'true') {
        let hoursZeroAdded = hours < 10 ? '0' + hours : hours;
        let minutesZeroAdded = minutes < 10 ? '0' + minutes : minutes;
        this.element.value = hoursZeroAdded + this.settings.delimiter + minutesZeroAdded;
      }
      if (this.settings.format === '12' && ampm) {
        this.element.value += this.settings.delimiter + ampm;
      }
    }
    this.element.dispatchEvent(new CustomEvent('UPDATE'));
  }

  _addSelects() {
    this._addHours();
    this._addMinutes();
    if (this.settings.format === '12') {
      this._addAmPm();
    }
  }

  _addHours() {
    var selectList = document.createElement('select');
    selectList.classList.add('time-picker-select');
    selectList.classList.add('time-picker-hour');
    var option = document.createElement('option');
    selectList.appendChild(option);

    const hours = this.hoursArray;
    for (var i = 0; i < hours.length; i++) {
      var option = document.createElement('option');
      option.text = hours[i];
      option.value = hours[i];
      if (this.settings.addZeros === 'true') {
        if (parseInt(hours[i]) < 10) {
          option.text = '0' + hours[i];
          option.value = '0' + hours[i];
        }
      }
      selectList.appendChild(option);
    }

    this.parent.insertBefore(selectList, this.element);
    this.hoursSelect = jQuery(selectList).select2({
      minimumResultsForSearch: Infinity,
      dropdownCssClass: 'time-picker-dropdown ' + this.settings.dropdownClassname,
      placeholder: '',
    });
    this.hoursSelect.data('select2').$container.addClass(this.settings.classname);
  }

  _addMinutes() {
    var selectList = document.createElement('select');
    selectList.classList.add('time-picker-select');
    selectList.classList.add('time-picker-minute');
    var option = document.createElement('option');
    selectList.appendChild(option);

    const minutes = this.minutesArray;
    for (var i = 0; i < minutes.length; i++) {
      var option = document.createElement('option');
      option.text = minutes[i];
      option.value = minutes[i];
      if (this.settings.addZeros === 'true') {
        if (parseInt(minutes[i]) < 10) {
          option.text = '0' + minutes[i];
          option.value = '0' + minutes[i];
        }
      }
      selectList.appendChild(option);
    }

    this.parent.insertBefore(selectList, this.element);
    this.minutesSelect = jQuery(selectList).select2({
      minimumResultsForSearch: Infinity,
      dropdownCssClass: 'time-picker-dropdown ' + this.settings.dropdownClassname,
      placeholder: '',
    });
    this.minutesSelect.data('select2').$container.addClass(this.settings.classname);
  }

  _addAmPm() {
    var selectList = document.createElement('select');
    selectList.classList.add('time-picker-select');
    selectList.classList.add('time-picker-ampm');

    var option = document.createElement('option');
    selectList.appendChild(option);

    var option = document.createElement('option');
    option.text = 'AM';
    option.value = 'AM';
    selectList.appendChild(option);

    var option = document.createElement('option');
    option.text = 'PM';
    option.value = 'PM';
    selectList.appendChild(option);

    this.parent.insertBefore(selectList, this.element);

    this.amPmSelect = jQuery(selectList).select2({
      minimumResultsForSearch: Infinity,
      dropdownCssClass: 'time-picker-dropdown am-pm ' + this.settings.dropdownClassname,
      placeholder: '',
    });
    this.amPmSelect.data('select2').$container.addClass(this.settings.classname);
  }

  setTime(timeString) {
    this.element.value = timeString;
    this._initStartValue();
  }

  setTimeFromDateObject(timeObject) {
    this.setTime(('0' + timeObject.getHours()).substr(-2) + ':' + ('0' + timeObject.getMinutes()).substr(-2));
  }

  getTime() {
    return this.element.value;
  }

  getTimeAsDateObject() {
    let values = this.element.value.split(this.settings.delimiter);
    let hours = values[0] + '';
    let minutes = values[1] + '';
    let date = new Date();
    date.setHours(hours);
    date.setMinutes(minutes);
    date.setSeconds(0);
    return date;
  }

  reset() {
    this.hoursSelect.val(null).trigger('change');
    this.minutesSelect.val(null).trigger('change');
    this.element.value = '';
    this._initStartValue();
  }

  _onOpen() {
    this.element.parentNode.classList.add('focus');
  }

  _onClose() {
    this.element.parentNode.classList.remove('focus');
  }
}
