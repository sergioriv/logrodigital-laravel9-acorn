/**
 *
 * AuthResetPassword
 *
 * Pages.Authentication.ResetPassword page content scripts. Initialized from scripts.js file.
 *
 *
 */

class AuthResetPassword {
  constructor() {
    // Initialization of the page plugins
    this._initForm();
  }

  // Form validation
  _initForm() {
    const form = document.getElementById('resetForm');
    if (!form) {
      return;
    }
    const validateOptions = {
      rules: {
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          minlength: 6,
        },
        password_confirmation: {
          required: true,
          minlength: 6,
          equalTo: '#password',
        },
      },
    };
    jQuery(form).validate(validateOptions);
    form.addEventListener('submit', (event) => {
      if (!jQuery(form).valid()) {
        event.preventDefault();
        event.stopPropagation();
        return;
      }
    });
  }
}
