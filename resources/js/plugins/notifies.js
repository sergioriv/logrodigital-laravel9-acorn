/**
 *
 * Notifies
 *
 * Interface.Plugins.Notify page content scripts. Initialized from scripts.js file.
 *
 *
 */

class Notifies {
  constructor() {
    // Initialization of the page plugins
    if (!jQuery.notify) {
      console.log('notify is null!');
      return;
    }

    this._initBasic();
    this._initDirections();
    this._initColors();
    this._initIcons();
    this._initImage();
    this._initHtml();
    this._initProgress();
  }

  // Basic notification
  _initBasic() {
    jQuery('#defaultNotifyButton').on('click', (event) => {
      event.preventDefault();
      jQuery.notify({title: 'Hello World!', message: ''}, {type: 'primary', delay: 5000});
    });
  }

  // Notification directions
  _initDirections() {
    jQuery('#notifyButtonTopLeft').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'primary',
          delay: 5000,
          placement: {
            from: 'top',
            align: 'left',
          },
        },
      );
    });

    jQuery('#notifyButtonTopCenter').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'primary',
          delay: 5000,
          placement: {
            from: 'top',
            align: 'center',
          },
        },
      );
    });

    jQuery('#notifyButtonTopRight').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'primary',
          delay: 5000,
          placement: {
            from: 'top',
            align: 'right',
          },
        },
      );
    });

    jQuery('#notifyButtonBottomLeft').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'primary',
          delay: 5000,
          placement: {
            from: 'bottom',
            align: 'left',
          },
        },
      );
    });

    jQuery('#notifyButtonBottomCenter').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'primary',
          delay: 5000,
          placement: {
            from: 'bottom',
            align: 'center',
          },
        },
      );
    });

    jQuery('#notifyButtonBottomRight').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'primary',
          delay: 5000,
          placement: {
            from: 'bottom',
            align: 'right',
          },
        },
      );
    });
  }

  // Notification colors
  _initColors() {
    jQuery('#notifyButtonPrimary').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'primary',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonSecondary').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'secondary',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonTertiary').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'tertiary',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonQuaternary').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'quaternary',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonSuccess').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'success',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonDanger').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'danger',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonWarning').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'warning',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonInfo').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'info',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonLight').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'light',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonDark').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.'},
        {
          type: 'dark',
          delay: 5000,
        },
      );
    });
  }

  // Notifications with icons
  _initIcons() {
    jQuery('#notifyButtonErrorIcon').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.', icon: 'cs-error-hexagon'},
        {
          type: 'primary',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonInfoIcon').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.', icon: 'cs-info-hexagon'},
        {
          type: 'primary',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonNoteIcon').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.', icon: 'cs-file-text'},
        {
          type: 'primary',
          delay: 5000,
        },
      );
    });

    jQuery('#notifyButtonSendIcon').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {title: 'Hello World!', message: 'Here is a notification.', icon: 'cs-send'},
        {
          type: 'primary',
          delay: 5000,
        },
      );
    });
  }

  // Notification with image thumbnail
  _initImage() {
    jQuery('#notifyButtonImage').on('click', (event) => {
      event.preventDefault();
      jQuery.notify({title: 'Hello World!', message: '', icon: '/img/profile/profile-1.webp'}, {type: 'primary', icon_type: 'image'});
    });
  }

  // Notification with html content
  _initHtml() {
    jQuery('#notifyButtonHtml').on('click', (event) => {
      event.preventDefault();
      jQuery.notify(
        {
          title: '<strong>Bold Title</strong> ',
          message: 'Acorn is created by <a href="https://themeforest.net/user/coloredstrategies" target="_blank">ColoredStrategies</a>',
        },
        {type: 'primary', icon_type: 'image'},
      );
    });
  }

  // Notification with progress bar
  _initProgress() {
    jQuery('#notifyButtonProgress').on('click', (event) => {
      event.preventDefault();
      jQuery.notify({title: 'Hello World!', message: 'Here is a notification.'}, {type: 'primary', showProgressbar: true});
    });
  }
}
