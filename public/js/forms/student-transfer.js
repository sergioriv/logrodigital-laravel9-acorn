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

        jQuery(form).validate({
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

        });
    }

}
