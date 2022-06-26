/**
 *
 * GenericForms
 *
 * Interface.Forms.GenericForms page content scripts. Initialized from scripts.js file.
 *
 *
 */

class GenericForms {
    constructor() {
        // Initialization of the page plugins
        if (!jQuery().validate) {
            console.log("validate is undefined!");
            return;
        }

        this._initTeacherForm();
    }

    _initTeacherForm() {
        const form = document.getElementById("teacherForm");
        if (!form) {
            console.log("teacherForm is null");
            return;
        }

        jQuery(form).validate({
            rules: {
                firstName: {
                    required: true,
                    lettersonly: true,
                },
                secondName: {
                    lettersonly: true,
                },
                fatherLastName: {
                    required: true,
                    lettersonly: true,
                },
                motherLastName: {
                    lettersonly: true,
                },
                phone: {
                    number: true,
                    minlength: 6,
                    maxlength: 12,
                },
                email: {
                    required: true,
                    email: true,
                },
            },

        });
    }
}
