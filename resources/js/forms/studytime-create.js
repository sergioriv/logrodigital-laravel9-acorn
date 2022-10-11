/**
 *
 * GenericForms
 *
 * Interface.Forms.GenericForms page content scripts. Initialized from scripts.js file.
 *
 *
 */

 class StudyTimeCreateForm {
    constructor() {
        // Initialization of the page plugins

        this._initPrimaryTab();
    }

    _initPrimaryTab()
    {
        const _this = this;
        const form = document.getElementById("studyTimeCreateForm");
        if (!form) {
            console.log("studyTimeCreateForm is null");
            return;
        }

        let missingAreas = $('#missingAreas');

        IMask(document.querySelector('#conceptual'), {
            mask: Number,
            min: 0,
            max: 100,
        });
        IMask(document.querySelector('#procedural'), {
            mask: Number,
            min: 0,
            max: 100,
        });
        IMask(document.querySelector('#attitudinal'), {
            mask: Number,
            min: 0,
            max: 100,
        });
        IMask(document.querySelector('#missingAreas'), {
            mask: Number,
            min: 1,
            max: 10,
        });

        jQuery('#checkMissingAreas').change(function() {
            let checked = $(this).prop('checked');
            if (1 == checked) {
                missingAreas.prop('disabled', false);
                missingAreas.prop('required', true);
            } else {
                missingAreas.prop('disabled', true);
                missingAreas.prop('required', false);
            }
        });

        const validateOptions = {
            rules: {
                name: {
                    required: true,
                },
                missing_areas: {
                    number: true,
                    max: 10,
                    min: 0,
                },
                conceptual: {
                    required: true,
                    number: true,
                    max: 100,
                    min: 0,
                },
                procedural: {
                    required: true,
                    number: true,
                    max: 100,
                    min: 0,
                },
                attitudinal: {
                    required: true,
                    number: true,
                    max: 100,
                    min: 0,
                }
            }
        }

        _this._validate(form, validateOptions);
    }

    _validate(form, validateOptions) {
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
