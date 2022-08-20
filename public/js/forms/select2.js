/*
 * Plugin Select2
 */

class Select2Form {
    constructor() {
        if (!jQuery().select2) {
            console.log("select2 is null");
            return;
        }

        this._initTheme();
        this._initSelect2();
    }

    _initTheme() {
        jQuery.fn.select2.defaults.set("theme", "bootstrap4");
    }

    _initSelect2() {
        jQuery("[logro='select2']").select2({
            minimumResultsForSearch: 30,
            placeholder: ''
        });
    }
}
