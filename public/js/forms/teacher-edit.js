/**
 *
 * GenericForms
 *
 * Interface.Forms.GenericForms page content scripts. Initialized from scripts.js file.
 *
 *
 */

class TeacherEditForm {
    constructor() {
        // Initialization of the page plugins
        if (!jQuery().validate) {
            console.log("validate is undefined!");
            return;
        }

        this._initTeacherProfileForm();
    }

    _initTeacherProfileForm() {
        const form = document.getElementById("teacherProfileForm");
        if (!form) {
            console.log("teacherProfileForm is null");
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
                document: {
                    required: true,
                    maxlength: 20
                },
                expedition_city: {
                    date: true
                },
                birthdate: {
                    date: true
                },
                address: {
                    maxlength: 100,
                },
                telephone: {
                    maxlength: 20,
                },
                cellphone: {
                    maxlength: 20,
                },
                institutional_email: {
                    required: true,
                    email: true,
                    maxlength: 191,
                },
                appointment_number: {
                    maxlength: 20
                },
                date_appointment: {
                    date: true
                },
                file_appointment: {
                    accept: "pdf"
                },
                possession_certificate: {
                    maxlength: 20
                },
                date_possession_certificate: {
                    date: true
                },
                file_possession_certificate: {
                    accept: "pdf"
                },
                transfer_resolution: {
                    maxlength: 20
                },
                date_transfer_resolution: {
                    date: true
                },
                file_transfer_resolution: {
                    accept: "pdf"
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
