/**
 *
 * GenericForms
 *
 * Interface.Forms.GenericForms page content scripts. Initialized from scripts.js file.
 *
 *
 */

class GenericUserForms {
    constructor() {
        // Initialization of the page plugins
        if (!jQuery().validate) {
            console.log("validate is undefined!");
            return;
        }

        this._initUserCreateForm();
        this._initTeacherForm();
    }

    _initUserCreateForm() {
        const form = document.getElementById("userCreateForm");
        if (!form) {
            console.log("userCreateForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                name: {
                    required: true,
                    maxlength: 191,
                },
                last_names: {
                    required: true,
                    maxlength: 191,
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 191
                },
                telephone: {
                    required: false,
                    maxlength: 20,
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
