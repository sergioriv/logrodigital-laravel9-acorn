/**
 * Student page
 */
class StudentDeleteForm {
    constructor() {
      this.href = window.location.href;
      // Initialization of the page plugins
      this._initCodeConfirmation();
      this._initValidateCode();
    }

    _initCodeConfirmation() {
        const _this = this;
        let btnSendCodeConfirmation = jQuery('#btn-sendCodeConfirmation');
        let btnConfirmDelete = jQuery('#btn-confirmDelete');
        let inputSecurityCode = jQuery('#inputSecurityCode');
        btnSendCodeConfirmation.click(function () {

            btnSendCodeConfirmation.prop('disabled', true);

            $.get(_this.href + "/code-confirmation", {}, function (data) {
                callNotify(data.message);
                btnSendCodeConfirmation.prop('disabled', false);
            });
        });

        inputSecurityCode.on('keyup', function () {
            if ( inputSecurityCode.val().length === 6 ) {
                btnConfirmDelete.prop('disabled', false);
            } else {
                btnConfirmDelete.prop('disabled', true);
            }
        });
    }
    _initValidateCode() {
        const form = document.getElementById("studentDeleteForm");
        if (!form) {
            console.log("studentDeleteForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                code_confirm: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
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


















