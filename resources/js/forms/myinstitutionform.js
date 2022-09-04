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
    }

    _initMyInstitutionForm() {
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
                    required: true,
                    url: true,
                },
            },
        };

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
