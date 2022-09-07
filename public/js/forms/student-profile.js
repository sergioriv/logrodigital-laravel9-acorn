/**
 * Student page
 */
class StudentProfileInfoForm {
    constructor() {
      // Initialization of the page plugins
      this._initStudentInfoForm();
      this._initStudentProfileInfoForm();
      this._initSelect2();
      this._initChangeCountry();
      this._initDisability();
      this._initViewDocument();
      this._initDescriptionDocument();
    }

    _initStudentInfoForm() {
        const form = document.getElementById("studentInfoForm");
        if (!form) {
            console.log("studentInfoForm is null");
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
                    maxlength: 20
                },
                birthdate: {
                    date: true
                },
                number_siblings: {
                    number: true,
                    max: 200,
                    min: 0,
                },
            },

        };

        this.formValidate(form, validateOptions);
    }

    _initStudentProfileInfoForm() {
        const form = document.getElementById("studentProfileInfoForm");
        if (!form) {
            console.log("studentProfileInfoForm is null");
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
                telephone: {
                    required: true,
                    maxlength: 20,
                },
                document_type: {
                    required: true,
                },
                document: {
                    required: true,
                    maxlength: 20
                },
                number_siblings: {
                    required: true,
                    number: true,
                    max: 200,
                    min: 0,
                },
                birthdate: {
                    required: true,
                    date: true
                },
                country: {
                    required: true,
                },
                siblings_in_institution: {
                    required: true,
                },
                gender: {
                    required: true,
                },
                rh: {
                    required: true,
                },
                zone: {
                    required: true,
                },
                residence_city: {
                    required: true,
                },
                address: {
                    required: true,
                    maxlength: 100,
                },
                social_stratum: {
                    required: true,
                },
                dwelling_type: {
                    required: true,
                },
                neighborhood: {
                    required: true,
                    maxlength: 100,
                },
                health_manager: {
                    required: true
                },
                school_insurance: {
                    required: true,
                    maxlength: 100,
                },
                sisben: {
                    required: true,
                },
                disability: {
                    required: true,
                }
            },

        };

        this.formValidate(form, validateOptions);
    }

    _initSelect2() {
        jQuery("[logro='select2']").select2({
            minimumResultsForSearch: 30,
            placeholder: ''
        });
    }

    _initChangeCountry() {
        jQuery("#country").change(function() {
            let national = $("option:selected", this).attr('national');
            if (1 == national) {
                $("#birth_city").prop('disabled', false);
            } else {
                $("#birth_city").prop('disabled', true);
            }
        });
    }

    _initDisability() {
        jQuery("#disability").change(function() {
            let val = $(this).val();
            if (val > 1)
            {
                $("#content-disability").removeClass('d-none');
            } else
            {
                $("#content-disability").addClass('d-none');
            }
        })
    }

    _initViewDocument() {
        jQuery("[logro='studentDocument']").click(function() {
            jQuery('#modalStudentDocuments img').attr('src', $(this).data('image'));
        });
    }

    _initDescriptionDocument() {
        jQuery("#selectStudentDocument").change(function () {
            var info = $(this).find('option:selected').attr('fileInfo');
            jQuery("#infoStudentDocument").removeClass('d-none').html(info);
        });
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


















