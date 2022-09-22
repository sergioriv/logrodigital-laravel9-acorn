/*
    Student Advice Create Form
*/

class StudentAdviceForm {
    constructor() {
        // Initialization of the page
        this.form = document.getElementById("studentAdviceCreateForm");
        if (!this.form) {
            console.log("studentAdviceCreateForm is null");
            return;
        }

        this._initAdviceCreate();
        this._initActionsForm();
    }

    _initAdviceCreate() {
        const _this = this;

        const validateOptions = {
            rules: {
                attendance: {
                    required: true,
                },
                evolution: {
                    minlength: 10,
                    maxlength: 500,
                },
                recommendations_teachers: {
                    minlength: 10,
                    maxlength: 500,
                },
                date_limite: {
                    date: true,
                },
                recommendations_family: {
                    minlength: 10,
                    maxlength: 500,
                },
                observations_for_entity: {
                    minlength: 10,
                    maxlength: 500,
                }
            },
        };

        _this.validate(_this.form, validateOptions);
    }

    _initActionsForm(){
        let attendanceSelect = $('#attendance-content select');
        let attendanceTextarea = $('#attendance-content textarea:not(#observations_for_entity)');

        let dateLimitText = $('#date_limite');
        let observationsEntityText = $('#observations_for_entity');
        let evolutionText = $("#evolution");

        jQuery("#attendance").on('change', function () {
            let option = $("option:selected", this).val();

            if ('done' == option) {
                attendanceSelect.prop('disabled', false);
                attendanceTextarea.prop('disabled', false);

                evolutionText.prop('required', true);
            } else {
                attendanceSelect.prop('disabled', true);
                attendanceTextarea.prop('disabled', true);

                evolutionText.prop('required', false);
            }
        });

        jQuery("#recommendations_teachers").on('change', function () {
            let value = $(this).val().trim();

            if (value === null || value == '')
            {
                dateLimitText.prop('disabled', true).prop('required', false);
            } else {
                dateLimitText.prop('disabled', false).prop('required', true);
            }
        });

        jQuery("#entity_remit").on('change', function () {
            let option = $("option:selected", this).val();

            if ('Ninguna' == option) {
                observationsEntityText.prop('disabled', true).prop('required', false);
            } else {
                observationsEntityText.prop('disabled', false).prop('required', true);
            }
        });
    }

    validate(form, validateOptions) {
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
