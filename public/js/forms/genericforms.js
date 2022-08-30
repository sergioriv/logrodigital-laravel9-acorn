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
        this._initStudentInfoForm();
        this._initTeacherForm();
    }

    _initStudentCreateForm() {
        const form = document.getElementById("studentCreateForm");
        if (!form) {
            console.log("studentCreateForm is null");
            return;
        }

        jQuery(form).validate({
            rules: {
                firstName: {
                    required: true,
                    maxlength: 191,
                },
                secondName: {
                    maxlength: 191,
                },
                fatherLastName: {
                    required: true,
                    maxlength: 191,
                },
                motherLastName: {
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

        });
    }

    _initStudentInfoForm() {
        const form = document.getElementById("studentInfoForm");
        if (!form) {
            console.log("studentInfoForm is null");
            return;
        }

        jQuery(form).validate({
            rules: {
                firstName: {
                    required: true,
                    maxlength: 191,
                },
                secondName: {
                    maxlength: 191,
                },
                fatherLastName: {
                    required: true,
                    maxlength: 191,
                },
                motherLastName: {
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

        });
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
                    maxlength: 191,
                },
                secondName: {
                    maxlength: 191,
                },
                fatherLastName: {
                    required: true,
                    maxlength: 191,
                },
                motherLastName: {
                    maxlength: 191,
                },
                phone: {
                    number: true,
                    minlength: 6,
                    maxlength: 20,
                },
                email: {
                    required: true,
                    email: true,
                    maxlength: 191,
                },
            },

        });
    }
}
