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

        jQuery(form).validate({
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

        });
    }
}
