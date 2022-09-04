/**
 *
 * GenericForms
 *
 * Interface.Forms.GenericForms page content scripts. Initialized from scripts.js file.
 *
 *
 */

class StudentTransferForm {
    constructor() {
        // Initialization of the page plugins
        if (!jQuery().validate) {
            console.log("validate is undefined!");
            return;
        }

        this._initStudentTransferForm();
    }

    _initStudentTransferForm() {
        const form = document.getElementById("studentTransferForm");
        if (!form) {
            console.log("studentTransferForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                headquarters: {
                    required: true
                },
                studyTime: {
                    required: true
                },
                studyYear: {
                    required: true
                }
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
