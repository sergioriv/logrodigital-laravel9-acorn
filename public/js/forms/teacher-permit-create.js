/**
 *
 * GenericForms
 *
 * Interface.Forms.GenericForms page content scripts. Initialized from scripts.js file.
 *
 *
 */

class TeacherPermitCreateForm {
    constructor() {
        // Initialization of the page plugins
        if (!jQuery().validate) {
            console.log("validate is undefined!");
            return;
        }

        this._initTeacherPerimtCreateForm();
    }

    _initTeacherPerimtCreateForm() {
        const form = document.getElementById("addPermitTeacherForm");
        if (!form) {
            console.log("addPermitTeacherForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                short_description: {
                    required: true,
                    maxlength: 100,
                },
                permit_date_start: {
                    required: true,
                    date: true,
                },
                permit_date_end: {
                    required: true,
                    date: true,
                },
                support_document: {
                    required: true,
                    extension: "pdf",
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
