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

    _initMissingAreas() {
        let missingAreas = $("#missingAreas");

        jQuery("#checkMissingAreas").change(function () {
            let checked = $(this).prop("checked");
            if (1 == checked) {
                missingAreas.prop("disabled", false);
                missingAreas.prop("required", true);
            } else {
                missingAreas.prop("disabled", true);
                missingAreas.prop("required", false);
            }
        });
    }

    _initIMask() {
        IMask(document.querySelector("#conceptual"), {
            mask: Number,
            min: 0,
            max: 100,
        });
        IMask(document.querySelector("#procedural"), {
            mask: Number,
            min: 0,
            max: 100,
        });
        IMask(document.querySelector("#attitudinal"), {
            mask: Number,
            min: 0,
            max: 100,
        });
        IMask(document.querySelector("#missingAreas"), {
            mask: Number,
            min: 1,
            max: 10,
        });
    }

    _initPerformance() {
        const _this = this;
        var decimal = jQuery("#decimal");
        _this.decimal = 2;
        _this.step = 0.01;

        _this.minGrade = jQuery("#minimum_grade");
        _this.lowPerformance = jQuery("#low_performance");
        _this.basicPerformance = jQuery("#basic_performance");
        _this.highPerformance = jQuery("#high_performance");
        _this.maxGrade = jQuery("#maximum_grade");

        var minBasic = jQuery("#minBasic");
        var minHigh = jQuery("#minHigh");
        var minSuperior = jQuery("#minSuperior");

        decimal.on("change", function () {
            _this.decimal = $(this).val();

            if (_this.decimal == 2) {
                _this.step = 0.01;

                _this.minGrade.val(0.0).attr("_this.", _this.step);
                _this.lowPerformance.val(2.99).attr("step", _this.step);
                minBasic.html(3);
                _this.basicPerformance.val(3.99).attr("step", _this.step);
                minHigh.html(4);
                _this.highPerformance.val(4.59).attr("step", _this.step);
                minSuperior.html(4.6);
                _this.maxGrade.val(5.0).attr("step", _this.step);

                _this._maxmin();
            }
            if (_this.decimal == 1) {
                _this.step = 0.1;

                _this.minGrade.val(0.0).attr("step", _this.step);
                _this.lowPerformance.val(2.9).attr("step", _this.step);
                minBasic.html(3);
                _this.basicPerformance.val(3.9).attr("step", _this.step);
                minHigh.html(4);
                _this.highPerformance.val(4.5).attr("step", _this.step);
                minSuperior.html(4.6);
                _this.maxGrade.val(5.0).attr("step", _this.step);

                _this._maxmin();
            }
            if (_this.decimal == 0) {
                _this.step = 1;

                _this.minGrade.val(0).attr("step", _this.step);
                _this.lowPerformance.val(29).attr("step", _this.step);
                minBasic.html(30);
                _this.basicPerformance.val(39).attr("step", _this.step);
                minHigh.html(40);
                _this.highPerformance.val(45).attr("step", _this.step);
                minSuperior.html(46);
                _this.maxGrade.val(50).attr("step", _this.step);

                _this._maxmin();
            }
        });

        _this.minGrade.on("change", function () {
            _this._maxmin();
        });
        _this.lowPerformance.on("change", function () {
            minBasic.html(
                (parseFloat($(this).val()) + _this.step).toFixed(_this.decimal)
            );

            _this._maxmin();
        });
        _this.basicPerformance.on("change", function () {
            minHigh.html(
                (parseFloat($(this).val()) + _this.step).toFixed(_this.decimal)
            );

            _this._maxmin();
        });
        _this.highPerformance.on("change", function () {
            minSuperior.html(
                (parseFloat($(this).val()) + _this.step).toFixed(_this.decimal)
            );

            _this._maxmin();
        });
        _this.maxGrade.on("change", function () {
            _this._maxmin();
        });
    }

    _maxmin() {
        this.minGrade.attr(
            "max",
            (parseFloat(this.lowPerformance.val()) - this.step).toFixed(this.decimal)
        );
        this.lowPerformance
            .attr(
                "min",
                (parseFloat(this.minGrade.val()) + this.step).toFixed(this.decimal)
            )
            .attr(
                "max",
                (parseFloat(this.basicPerformance.val()) - (this.step * 2)).toFixed(this.decimal)
            );
        this.basicPerformance
            .attr(
                "min",
                (parseFloat(this.lowPerformance.val()) + (this.step * 2)).toFixed(this.decimal)
            )
            .attr(
                "max",
                (parseFloat(this.highPerformance.val()) - (this.step * 2)).toFixed(this.decimal)
            );
        this.highPerformance
            .attr(
                "min",
                (parseFloat(this.basicPerformance.val()) + (this.step * 2)).toFixed(this.decimal)
            )
            .attr(
                "max",
                (parseFloat(this.maxGrade.val()) - (this.step * 2)).toFixed(this.decimal)
            );
        this.maxGrade.attr(
            "min",
            (parseFloat(this.highPerformance.val()) + (this.step * 2)).toFixed(this.decimal)
        );
    }

    _initPrimaryTab() {
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
                decimal: {
                    required: true,
                    number: true,
                    min: 0,
                    max: 2,
                },
                round: {
                    required: true,
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
            },
        };

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
