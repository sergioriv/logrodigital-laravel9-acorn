/**
 *
 * Calendar
 * A very basic and static implementation for the application mostly to show different layouts it has. Edit this class according to your project needs.
 * Implemented with the help of FullCalendar and with a static data from json/events.json file.
 *
 */

class Calendar {
  get options() {
    return {};
  }

  constructor(options = {}) {
    this.settings = Object.assign(this.options, options);
    this.colorMap = this._getColorMap();
    this.calendar = null;
    this.eventStartTime = null;
    this.eventEndTime = null;
    this.currentEventId = null;

    // this.newEventModal = new bootstrap.Modal(document.getElementById('newEventModal'));
    this.deleteConfirmModal = new bootstrap.Modal(document.getElementById('deleteConfirmModal'));

    Helpers.FetchJSON(Helpers.UrlFix('/json/calendar.json'), (data) => {
      this.events = data;
      this._addColors();
    //   this._alterEventsForDemo();
      this._initTimepicker();
      this._initCategory();
      this._init();
      this._addListeners();
    });
  }

  _init() {
    if (!document.getElementById('calendar') || !document.getElementById('calendarTitle') || typeof FullCalendar === 'undefined') {
      return;
    }
    this.calendar = new FullCalendar.Calendar(document.getElementById('calendar'), {
      timeZone: 'local',
      locale: 'es',
      themeSystem: 'bootstrap',
      slotMinTime: '06:00:00',
      slotMaxTime: '20:00:00',
      editable: false,
      dayMaxEvents: true,
      eventTimeFormat: {
        hour: '2-digit',
        minute: '2-digit',
        meridiem: false,
        hour12: false,
      },
      headerToolbar: {
        left: '',
        center: '',
        right: '',
      },
      viewDidMount: (args) => {
        this._updateTitle();
      },
      eventClick: this._eventClick.bind(this),
      eventAdd: this._eventAddCallback.bind(this),
      eventChange: this._eventChangeCallback.bind(this),
      eventRemove: this._eventRemoveCallback.bind(this),
      events: this.events,
    });
    this.calendar.render();
  }

  _addListeners() {
    document.getElementById('goToday') &&
      document.getElementById('goToday').addEventListener('click', () => {
        this.calendar.today();
        this._updateTitle();
      });

    document.getElementById('goPrev') &&
      document.getElementById('goPrev').addEventListener('click', () => {
        this.calendar.prev();
        this._updateTitle();
      });

    document.getElementById('goNext') &&
      document.getElementById('goNext').addEventListener('click', () => {
        this.calendar.next();
        this._updateTitle();
      });

    document.getElementById('monthView') &&
      document.getElementById('monthView').addEventListener('click', () => {
        this.calendar.changeView('dayGridMonth');
        this._updateTitle();
      });

    document.getElementById('weekView') &&
      document.getElementById('weekView').addEventListener('click', () => {
        this.calendar.changeView('timeGridWeek');
        this._updateTitle();
      });

    document.getElementById('dayView') &&
      document.getElementById('dayView').addEventListener('click', () => {
        this.calendar.changeView('timeGridDay');
        this._updateTitle();
      });

    document.documentElement.addEventListener(Globals.colorAttributeChange, this._updateAllColors.bind(this));
    // document.getElementById('addNewEvent') && document.getElementById('addNewEvent').addEventListener('click', this._addNewEvent.bind(this));
    // document.getElementById('addEvent') && document.getElementById('addEvent').addEventListener('click', this._addEventConfirm.bind(this));
    // document.getElementById('saveEvent') && document.getElementById('saveEvent').addEventListener('click', this._updateEventConfirm.bind(this));
    // document.getElementById('deleteEvent') && document.getElementById('deleteEvent').addEventListener('click', this._deleteEventClick.bind(this));
    // document.getElementById('deleteConfirmButton') &&
    //   document.getElementById('deleteConfirmButton').addEventListener('click', this._deleteConfirmClick.bind(this));
  }

  // Updating title of the calendar, not event related
  _updateTitle() {
    document.getElementById('calendarTitle').innerHTML = this.calendar.view.title;
  }

  // Filling the event details modal for showing the event
  _eventClick(info) {
    const event = info.event;
    if (event.url != '') {
      window.open(event.url, '_self');
      info.jsEvent.preventDefault();
      return;
    }
    this._clearForm();
    this.currentEventId = event.id;
    // if (!event.allDay) {
        const startDate = new Date(event.start);
        const endDate = new Date(event.end);
    // }
    // jQuery('#eventStartDate').datepicker('update', event.start);
    // jQuery('#eventEndDate').datepicker('update', event.end);

    // document.getElementById('eventTitle').value = event.title;
    // document.getElementById('eventCategory').value = event.extendedProps.category;
    // document.getElementById('modalTitle').innerHTML = 'Edit Event';
    // jQuery('#eventCategory').trigger('change');

    document.getElementById('deleteConfirmDetail').innerHTML = moment(startDate).format('YYYY-MM-DD hh:mm') + ' - ' + moment(endDate).format('YYYY-MM-DD hh:mm') + ' - ' +  event.title;
    this.deleteConfirmModal.show();
    // this._enableEdit();
  }

  // Updating earlier clicked event
//   _updateEventConfirm() {
//     const currentEvent = this.calendar.getEventById(this.currentEventId);
//     const startDate = new Date(jQuery('#eventStartDate').datepicker('getDate'));
//     const startTime = new Date(this.eventStartTime.getTimeAsDateObject());
//     const endDate = new Date(jQuery('#eventEndDate').datepicker('getDate'));
//     const endTime = new Date(this.eventEndTime.getTimeAsDateObject());

//     startDate.setHours(startTime.getHours());
//     startDate.setMinutes(startTime.getMinutes());

//     currentEvent.title !== document.getElementById('eventTitle').value && currentEvent.setProp('title', document.getElementById('eventTitle').value);
//     currentEvent.start.getTime() !== startDate.getTime() && currentEvent.setStart(startDate);
//     if (currentEvent.extendedProps.category !== document.getElementById('eventCategory').value) {
//       currentEvent.setExtendedProp('category', document.getElementById('eventCategory').value);
//       this._setColor(currentEvent);
//     }

//     if (jQuery('#eventEndDate').datepicker('getDate') && this.eventEndTime.getTime() !== '') {
//       endDate.setHours(endTime.getHours());
//       endDate.setMinutes(endTime.getMinutes());
//     }
//     if (jQuery('#eventEndDate').datepicker('getDate')) {
//       currentEvent.end.getTime() !== endDate.getTime() && currentEvent.setEnd(endDate);
//     }
//     this.newEventModal.hide();
//   }

  // Showing modal for adding a new event
//   _addNewEvent() {
//     this._clearForm();
//     this.currentEventId = null;
//     this._enableAdd();
//     document.getElementById('modalTitle').innerHTML = 'Add Event';
//     this.newEventModal.show();
//   }

  // Adding new event to the calendar
//   _addEventConfirm() {
//     let startDate = new Date(jQuery('#eventStartDate').datepicker('getDate'));
//     let endDate = new Date(jQuery('#eventEndDate').datepicker('getDate'));
//     let startTime = new Date(this.eventStartTime.getTimeAsDateObject());
//     let endTime = new Date(this.eventEndTime.getTimeAsDateObject());
//     let category = document.getElementById('eventCategory').value;
//     if (this.eventStartTime.getTime() !== '') {
//       startDate.setHours(startTime.getHours());
//       startDate.setMinutes(startTime.getMinutes());
//     } else {
//       startDate = moment(startDate).format('YYYY-MM-DD');
//     }
//     if (this.eventEndTime.getTime() !== '') {
//       endDate.setHours(endTime.getHours());
//       endDate.setMinutes(endTime.getMinutes());
//     } else {
//       endDate = moment(endDate).format('YYYY-MM-DD');
//     }
//     const title = document.getElementById('eventTitle').value;
//     this.calendar.addEvent({
//       title: title,
//       start: startDate,
//       end: endDate,
//       id: Helpers.NextId(this.calendar.getEvents(), 'id'),
//       category: category,
//       color: this._getColorByCategory(category),
//     });
//     this.newEventModal.hide();
//   }

  // Delete click that shows the confirmation modal
//   _deleteEventClick() {
//     const currentEvent = this.calendar.getEventById(this.currentEventId);
//     document.getElementById('deleteConfirmDetail').innerHTML = currentEvent.title;
//     this.deleteConfirmModal.show();
//   }

  // Deleting event after confirmation
//   _deleteConfirmClick() {
//     const currentEvent = this.calendar.getEventById(this.currentEventId);
//     currentEvent.remove();
//     this.newEventModal.hide();
//     this.deleteConfirmModal.hide();
//   }

  _clearForm() {
    this.eventStartTime.reset();
    this.eventEndTime.reset();
    document.getElementById('eventTitle').value = '';
    document.getElementById('eventCategory').value = null;
    jQuery('#eventCategory').trigger('change');
    jQuery('#eventStartDate').datepicker('update', '');
    jQuery('#eventEndDate').datepicker('update', '');
  }

//   _enableEdit() {
//     this._showElement('saveEvent');
//     this._showElement('deleteEvent');
//     this._hideElement('addEvent');
//   }

//   _enableAdd() {
//     this._hideElement('saveEvent');
//     this._hideElement('deleteEvent');
//     this._showElement('addEvent');
//   }

//   _showElement(selector) {
//     document.getElementById(selector) && document.getElementById(selector).classList.add('d-inline-block');
//     document.getElementById(selector) && document.getElementById(selector).classList.remove('d-none');
//   }

//   _hideElement(selector) {
//     document.getElementById(selector) && document.getElementById(selector).classList.remove('d-inline-block');
//     document.getElementById(selector) && document.getElementById(selector).classList.add('d-none');
//   }

  _getColorByCategory(category) {
    const selected = this.colorMap.find((colorItem) => {
      return colorItem.category === category;
    });
    if (selected) {
      return selected.color;
    } else {
      this.colorMap[0].color;
    }
  }

  // Getting color values from Globals and adding them in an array with the category
  _getColorMap() {
    return [
      {color: Globals.primary, category: 'Event'},
      {color: Globals.pink, category: 'Advice'},
    ];
  }

  // Updating color after calendar initialization
  _setColor(event) {
    const selectedColorItem = this._getColorByCategory(event.extendedProps.category);
    event.setProp('color', selectedColorItem);
  }

  // Adding colors based on the event category
  _addColors() {
    this.events.map((event) => {
      event.color = this._getColorByCategory(event.category);
    });
  }

  // Theme color change event that updates all the event colors
  _updateAllColors() {
    this.colorMap = this._getColorMap();
    const events = this.calendar.getEvents();
    events.map((event) => {
      this._setColor(event);
    });
  }

  // Initialization of TimePicker plugin for event start and end times
  _initTimepicker() {
    this.eventStartTime = new TimePicker(document.getElementById('eventStartTime'), {dropdownClassname: 'time-top-label-dropdown'});
    this.eventEndTime = new TimePicker(document.getElementById('eventEndTime'), {dropdownClassname: 'time-top-label-dropdown'});
  }

  // Initialization of Select2 plugin for categories
  _initCategory() {
    /* function formatText(item) {
      if (jQuery(item.element).val()) {
        return jQuery(
          '<div><span class="align-middle d-inline-block option-circle me-2 rounded-xl ' +
            jQuery(item.element).data('class') +
            '"></span> <span class="align-middle d-inline-block lh-1">' +
            item.text +
            '</span></div>',
        );
      }
    } */
    if (jQuery().select2) {
      jQuery('#eventCategory').select2({
        minimumResultsForSearch: Infinity,
        dropdownCssClass: 'hide-first-option',
        // templateSelection: formatText,
        // templateResult: formatText,
      });
    }
  }

  // Add event callback for getting the data to sync with server
  _eventAddCallback(args) {
    // console.log(args.event.toPlainObject());
  }

  // Removed event callback for getting the data to sync with server
  _eventRemoveCallback(args) {
    // console.log(args.event.toPlainObject());
  }

  // Change event callback for getting the data to sync with server
  _eventChangeCallback(args) {
    // console.log(args.event.toPlainObject());
  }
}
