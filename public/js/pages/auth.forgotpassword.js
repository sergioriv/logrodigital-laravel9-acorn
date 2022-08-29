/**
 *
 * AuthForgotPassword
 *
 * Pages.Authentication.ForgotPassword page content scripts. Initialized from scripts.js file.
 *
 *
 */

class AuthForgotPassword {
  constructor() {
    // Initialization of the page plugins
    this._initForm();
  }

  // Form validation
  _initForm() {
    const form = document.getElementById('forgotPasswordForm');
    if (!form) {
      return;
    }
    const validateOptions = {
      rules: {
        email: {
          required: true,
          email: true,
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
