/**
 * Student page
 */
class StudentProfileInfo {
    constructor() {
      // Initialization of the page plugins
      this._initStudentInfoForm();
      this._initStudentProfileInfoForm();
      this._initSelect2();
      this._initChangeDocumentType();
      this._initDisability();
      this._initChangeAvatar();
      this._initViewDocument();
      this._initDescriptionDocument();
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
                document_type: {
                    required: true,
                },
                document: {
                    required: true,
                    maxlength: 20
                },
                birthdate: {
                    date: true
                }
            },

        });
    }

    _initStudentProfileInfoForm() {
        const form = document.getElementById("studentProfileInfoForm");
        if (!form) {
            console.log("studentProfileInfoForm is null");
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
                },
                birthdate: {
                    required: true,
                    date: true
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

        });
    }

    _initSelect2() {
        jQuery("[logro='select2']").select2({
            minimumResultsForSearch: 30,
            placeholder: ''
        });
    }

    _initChangeDocumentType() {
        jQuery("#document_type").change(function() {
            let foreigner = $("#document_type option:selected").attr('foreigner');
            if (1 == foreigner)
            {
                $("#birth_city").addClass('d-none');
                $("#country").removeClass('d-none');
            } else
            {
                $("#birth_city").removeClass('d-none');
                $("#country").addClass('d-none');
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

    _initChangeAvatar() {
        $('#avatar').on("change", function() {
            $("#formAvatar").submit();
        });
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
}


















