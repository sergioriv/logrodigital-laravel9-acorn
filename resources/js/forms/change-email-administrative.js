/**
 * Student page
 */
class ChangeEmailAdministrative {
    constructor() {
      this.href = window.location.href;
      // Initialization of the page plugins
      this._initCodeConfirmation();
      this._initValidateCode();
    }

    _initCodeConfirmation() {
        const _this = this;
        let btnSendCodeConfirmation = jQuery('#btn-sendCodeConfirmation');
        let btnConfirmChange = jQuery('#btn-confirmChange');
        let inputSecurityCode = jQuery('#inputSecurityCode');
        btnSendCodeConfirmation.click(function () {

            btnSendCodeConfirmation.prop('disabled', true);

            $.get(_this.href + "/change-email/code-confirmation", {}, function (data) {
                callNotify(data.message);
                btnSendCodeConfirmation.prop('disabled', false);
            });
        });

        inputSecurityCode.on('keyup', function () {
            if ( inputSecurityCode.val().length === 6 ) {
                btnConfirmChange.prop('disabled', false);
            } else {
                btnConfirmChange.prop('disabled', true);
            }
        });
    }
    _initValidateCode() {
        const form = document.getElementById("changeEmailAddressForm");
        if (!form) {
            console.log("changeEmailAddressForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                code_confirm: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                },
                new_email: {
                    required: true,
                    email: true,
                    maxlength: 191,
                },
            }
        }

        jQuery(form).validate(validateOptions);
        form.addEventListener("submit", (event) => {
            if (!jQuery(form).valid()) {
                $("button[type='submit']", form).prop("disabled", false);
                event.preventDefault();
                event.stopPropagation();
                return;
            } else {
                $("button[type='submit']", form).prop("disabled", true);
            }
        });
    }
}


















