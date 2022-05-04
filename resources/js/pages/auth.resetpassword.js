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
        token: {
          required: true,
        },
        email: {
          required: true,
          email: true,
        },
        password: {
          required: true,
          minlength: 6,
          regex: /[a-z].*[0-9]|[0-9].*[a-z]/i,
        },
        password_confirmation: {
          required: true,
          minlength: 6,
          regex: /[a-z].*[0-9]|[0-9].*[a-z]/i,
          equalTo: '#password',
        },
      },
      messages: {
        password: {
          minlength: 'Password must be at least {0} characters!',
          regex: 'Password must contain a letter and a number!',
        },
        password_confirmation: {
          minlength: 'Password must be at least {0} characters!',
          regex: 'Password must contain a letter and a number!',
          equalTo: 'Passwords must match!',
        },
      },
    };
    jQuery(form).validate(validateOptions);
    form.addEventListener('submit', (event) => {
      event.preventDefault();
      event.stopPropagation();
      if (jQuery(form).valid()) {
        const formValues = {
          token: form.querySelector('[name="token"]').value,
          email: form.querySelector('[name="email"]').value,
          password: form.querySelector('[name="password"]').value,
          password_confirmation: form.querySelector('[name="password_confirmation"]').value,
        };
        console.log(formValues);
        return;
      }
    });
  }
}
