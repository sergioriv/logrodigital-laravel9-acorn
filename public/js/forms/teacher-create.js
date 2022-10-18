/**
 *
 * GenericForms
 *
 * Interface.Forms.GenericForms page content scripts. Initialized from scripts.js file.
 *
 *
 */

class TeacherCreateForm {
    constructor() {
        // Initialization of the page plugins
        if (!jQuery().validate) {
            console.log("validate is undefined!");
            return;
        }

        this._initTeacherCreateForm();
    }

    _initTeacherCreateForm() {
        const form = document.getElementById("teacherCreateForm");
        if (!form) {
            console.log("teacherCreateForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                names: {
                    required: true,
                    maxlength: 191,
                },
                lastNames: {
                    required: true,
                    maxlength: 191,
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 191,
                },
                date_entry: {
                    required: true,
                    date: true
                },
                type_appointment: {
                    required: true
                },
                type_admin_act: {
                    required: true
                },
                appointment_number: {
                    maxlength: 20
                },
                date_appointment: {
                    date: true
                },
                possession_certificate: {
                    maxlength: 20
                },
                date_possession_certificate: {
                    date: true
                },
                transfer_resolution: {
                    maxlength: 20
                },
                date_transfer_resolution: {
                    date: true
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
