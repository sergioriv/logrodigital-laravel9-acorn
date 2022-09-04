/**
 *
 * Change Avatar Form
 *
 * */

class StudentPersonChargeForm {
    constructor() {
        // Initialization of the page
        this.form = document.getElementById("studentPersonChargeForm");
        if (!this.form) {
            console.log("studentPersonChargeForm is null");
            return;
        }

        this._initPersonCharge();
        this._initStudentTutor();
        // this._motherRequired();
        // this._initStudentPersonChargeForm(false, false, false);
    }

    _initPersonCharge() {
        const _this = this;
        // jQuery(_this.form).validate({
        const validateOptions = {
            rules: {
                person_charge: {
                    required: true,
                },
            },
        };

        jQuery(_this.form).validate(validateOptions);
        _this.form.addEventListener("submit", (event) => {
            if (!jQuery(_this.form).valid()) {
                $("button[type='submit']", _this.form).prop("disabled", false);
                event.preventDefault();
                event.stopPropagation();
                return;
            } else {
                $("button[type='submit']", _this.form).prop("disabled", true);
            }
        });
    }

    _initStudentTutor() {
        jQuery("#person_charge").change(function () {
            let val = $(this).val();

            if (val > 2) {
                $("#section-tutor").removeClass("d-none");
            } else {
                $("#section-tutor").addClass("d-none");
            }
        });
    }

    // _motherRequired() {
    //     const form = document.getElementById("studentInfoForm");
    //     if (!form) {
    //         console.log("studentInfoForm is null");
    //         return;
    //     }

    //     jQuery(form).validate({
    //         rules: {

    //             /* MOTHER */
    //             mother_name: {
    //                 required: true,
    //                 maxlength: 191,
    //             },
    //             mother_email: {
    //                 required: true,
    //                 maxlength: 191,
    //             },
    //             mother_document: {
    //                 required: true,
    //                 maxlength: 20,
    //             },
    //             mother_address: {
    //                 required: true,
    //                 maxlength: 100,
    //             },
    //             mother_telephone: {
    //                 required: true,
    //                 maxlength: 20,
    //             },
    //             mother_cellphone: {
    //                 required: true,
    //                 maxlength: 20,
    //             },
    //             mother_birthdate: {
    //                 required: true,
    //                 date: true
    //             },
    //             mother_occupation: {
    //                 required: true,
    //                 maxlength: 191,
    //             }
    //         }
    //     });
    // }

    // _initStudentPersonChargeForm(motherRequired, fatherRequired, tutorRequired) {
    //     const _this = this;

    //     jQuery(_this.form).validate({
    //         rules: {

    //             /* FATHER */
    //             father_name: {
    //                 required: fatherRequired,
    //                 maxlength: 191,
    //             },
    //             father_email: {
    //                 required: fatherRequired,
    //                 maxlength: 191,
    //             },
    //             father_document: {
    //                 required: fatherRequired,
    //                 maxlength: 20,
    //             },
    //             father_address: {
    //                 required: fatherRequired,
    //                 maxlength: 100,
    //             },
    //             father_telephone: {
    //                 required: fatherRequired,
    //                 maxlength: 20,
    //             },
    //             father_cellphone: {
    //                 required: fatherRequired,
    //                 maxlength: 20,
    //             },
    //             father_birthdate: {
    //                 required: fatherRequired,
    //                 date: true
    //             },
    //             father_occupation: {
    //                 required: fatherRequired,
    //                 maxlength: 191,
    //             },

    //             /* TUTOR/A */
    //             tutor_name: {
    //                 required: tutorRequired,
    //                 maxlength: 191,
    //             },
    //             tutor_email: {
    //                 required: tutorRequired,
    //                 maxlength: 191,
    //             },
    //             tutor_document: {
    //                 required: tutorRequired,
    //                 maxlength: 20,
    //             },
    //             tutor_address: {
    //                 required: tutorRequired,
    //                 maxlength: 100,
    //             },
    //             tutor_telephone: {
    //                 required: tutorRequired,
    //                 maxlength: 20,
    //             },
    //             tutor_cellphone: {
    //                 required: tutorRequired,
    //                 maxlength: 20,
    //             },
    //             tutor_birthdate: {
    //                 required: tutorRequired,
    //                 date: true
    //             },
    //             tutor_occupation: {
    //                 required: tutorRequired,
    //                 maxlength: 191,
    //             },
    //         },

    //     });
    // }
}
