/**
 *
 * Change Avatar Form
 *
 * */

 class StudentPersonChargeForm {
    constructor() {
        // Initialization of the page

        this._initStudentPersonChargeForm();
        this._initStudentTutor();
    }

    _initStudentPersonChargeForm() {
        const form = document.getElementById("studentPersonChargeForm");
        if (!form) {
            console.log("studentPersonChargeForm is null");
            return;
        }

        jQuery(form).validate({
            rules: {
                person_charge: {
                    required: true,
                },

                /* MOTHER */
                mother_name: {
                    maxlength: 191,
                },
                mother_email: {
                    maxlength: 191,
                },
                mother_document: {
                    maxlength: 20,
                },
                mother_address: {
                    maxlength: 100,
                },
                mother_telephone: {
                    maxlength: 20,
                },
                mother_cellphone: {
                    maxlength: 20,
                },
                mother_birthdate: {
                    date: true
                },
                mother_occupation: {
                    maxlength: 191,
                },

                /* FATHER */
                father_name: {
                    maxlength: 191,
                },
                father_email: {
                    maxlength: 191,
                },
                father_document: {
                    maxlength: 20,
                },
                father_address: {
                    maxlength: 100,
                },
                father_telephone: {
                    maxlength: 20,
                },
                father_cellphone: {
                    maxlength: 20,
                },
                father_birthdate: {
                    date: true
                },
                father_occupation: {
                    maxlength: 191,
                },

                /* TUTOR/A */
                tutor_name: {
                    maxlength: 191,
                },
                tutor_email: {
                    maxlength: 191,
                },
                tutor_document: {
                    maxlength: 20,
                },
                tutor_address: {
                    maxlength: 100,
                },
                tutor_telephone: {
                    maxlength: 20,
                },
                tutor_cellphone: {
                    maxlength: 20,
                },
                tutor_birthdate: {
                    date: true
                },
                tutor_occupation: {
                    maxlength: 191,
                },
            },

        });
    }

    _initStudentTutor() {
        jQuery("#person_charge").change(function () {
            let val = $(this).val();
            if (val > 2) {
                $("#section-tutor").removeClass('d-none');
            } else {
                $("#section-tutor").addClass('d-none');
            }
        });
    }
 }
