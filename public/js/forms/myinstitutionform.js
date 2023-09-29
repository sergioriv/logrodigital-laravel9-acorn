/**
 *
 * MyInstitutionForm
 *
 * logro.school.show page content scripts. Initialized from scripts.js file.
 *
 *
 */

class MyInstitutionForm {
    constructor() {
        // Initialization of the page plugins
        if (!jQuery().validate) {
            console.log("validate is undefined!");
            return;
        }

        this._initMyInstitutionForm();
        this._initSegurityEmailForm();
        this._initSendConfirmationEmail();
    }

    _initMyInstitutionForm() {
        const _this = this;
        const form = document.getElementById("myInstitutionForm");
        if (!form) {
            console.log("myInstitutionForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                name: {
                    required: true,
                    maxlength: 191,
                },
                nit: {
                    required: true,
                    maxlength: 20,
                },
                dane: {
                    required: false,
                    maxlength: 191,
                },
                contact_email: {
                    required: true,
                    maxlength: 100,
                },
                contact_telephone: {
                    required: true,
                    maxlength: 20,
                },
                institutional_email: {
                    required: false,
                    maxlength: 191,
                },
                handbook_coexistence: {
                    required: false,
                    url: true,
                },
            },
        };

        _this.formValidate(form, validateOptions);
    }

    _initSegurityEmailForm() {
        const _this = this;
        const form = document.getElementById("mySecurityEmailForm");
        if (!form) {
            console.log("mySecurityEmailForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                security_email: {
                    required: true,
                    email: true,
                    maxlength: 191,
                },
                code: {
                    required: true,
                    minlength: 6,
                    maxlength: 6,
                },
            }
        }

        _this.formValidate(form, validateOptions);
    }

    _initSendConfirmationEmail()
    {
        let btnSendConfirmation = jQuery('#btn-sendConfirmation');
        btnSendConfirmation.click(function () {
            var newEmailSecurity = $('#inputSecurityEmail').val().trim();

            if (newEmailSecurity == '') {
                return;
            }

            btnSendConfirmation.prop('disabled', true);

            $.get("myinstitution/send-confirmation", {
                email: newEmailSecurity
            }, function (data) {
                callNotify(data.message);
                btnSendConfirmation.prop('disabled', false);
            });
        });
    }

    formValidate(form, validateOptions)
    {
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
