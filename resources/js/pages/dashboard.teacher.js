

class DashboardTeacher {
    constructor () {

        const select2Students = document.getElementById("students_observer");
        if (!select2Students) {
            return;
        }

        this._init();

    }

    _init() {

        jQuery('#students_observer').select2({
            ajax: {
                url: Helpers.UrlFix('/json/students.json'),
                dataType: 'json',
                delay: 250,
                data: function(params) {
                    return {
                        search: {
                            value: params.term
                        },
                        page: params.page,
                    };
                },
                processResults: function(data, page) {
                    return {
                        results: data.data,
                    };
                },
                cache: true,
            },
            placeholder: 'Buscar',
            escapeMarkup: function(markup) {
                return markup;
            },
            minimumInputLength: 2,
            templateResult: function formatResult(result) {
                if (result.loading) return result.text;
                var markup = '<div class="clearfix">' + result.fullname + '</div>';
                if (result.group) {
                    markup += '<div class="text-muted text-small">' + result.headquarters.name + ' | ' + result
                        .group.name + '</div>';
                }
                return markup;
            },
            templateSelection: function formatResultSelection(result) {
                return result.fullname;
            },
        });

    }
}
