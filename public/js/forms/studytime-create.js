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

        this._initMissingAreas();
        this._initIMask();
        this._initPerformance();
        this._initPrimaryTab();
    }

    _initMissingAreas()
    {
        let missingAreas = $('#missingAreas');

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
    }

    _initIMask()
    {
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
    }

    _initPerformance()
    {
        var minGrade = jQuery('#minimum_grade');
        var lowPerformance = jQuery('#low_performance');
        var acceptablePerformance = jQuery('#acceptable_performance');
        var highPerformance = jQuery('#high_performance');
        var maxGrade = jQuery('#maximum_grade');

        minGrade.on('change', function() {
            lowPerformance.attr('min', $(this).val())
        });
        lowPerformance.on('change', function() {
            minGrade.attr('max', $(this).val())
            acceptablePerformance.attr('min', $(this).val())
        });
        acceptablePerformance.on('change', function() {
            lowPerformance.attr('max', $(this).val())
            highPerformance.attr('min', $(this).val())
        });
        highPerformance.on('change', function() {
            acceptablePerformance.attr('max', $(this).val())
            maxGrade.attr('min', $(this).val())
        });
        maxGrade.on('change', function() {
            highPerformance.attr('max', $(this).val())
        });
    }

    _initPrimaryTab()
    {
        const _this = this;
        const form = document.getElementById("studyTimeCreateForm");
        if (!form) {
            console.log("studyTimeCreateForm is null");
            return;
        }

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
                },
                minimum_grade: {
                    required: true,
                    number: true,
                },
                low_performance: {
                    required: true,
                    number: true,
                },
                medium_performance: {
                    required: true,
                    number: true,
                },
                high_performance: {
                    required: true,
                    number: true,
                },
                maximum_grade: {
                    required: true,
                    number: true,
                },
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
