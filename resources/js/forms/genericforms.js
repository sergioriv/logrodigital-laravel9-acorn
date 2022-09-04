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

        this._initStudentCreateForm();
        this._initTeacherForm();
    }

    _initStudentCreateForm() {
        const form = document.getElementById("studentCreateForm");
        if (!form) {
            console.log("studentCreateForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                firstName: {
                    required: true,
                    maxlength: 191,
                },
                secondName: {
                    maxlength: 191,
                },
                firstLastName: {
                    required: true,
                    maxlength: 191,
                },
                secondLastName: {
                    maxlength: 191,
                },
                institutional_email: {
                    required: true,
                    email: true,
                    maxlength: 191,
                },
                document_type: {
                    required: true,
                },
                document: {
                    required: true,
                    minlength: 5,
                    maxlength: 20
                },
                headquarters: {
                    required: true
                },
                studyTime: {
                    required: true
                },
                studyYear: {
                    required: true
                },
                birthdate: {
                    date: true
                }
            },

        };

        this.formValidate(form, validateOptions);
    }

    _initTeacherForm() {
        const form = document.getElementById("teacherForm");
        if (!form) {
            console.log("teacherForm is null");
            return;
        }

        const validateOptions = {
            rules: {
                firstName: {
                    required: true,
                    maxlength: 191,
                },
                secondName: {
                    required: false,
                    maxlength: 191,
                },
                firstLastName: {
                    required: true,
                    maxlength: 191,
                },
                secondLastName: {
                    maxlength: 191,
                },
                phone: {
                    required: true,
                    minlength: 6,
                    maxlength: 20,
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 191,
                },
            },

        };

        this.formValidate(form, validateOptions);
    }

    formValidate(form, validateOptions) {
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
