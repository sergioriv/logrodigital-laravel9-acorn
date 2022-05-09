/**
 *
 * RowsAjax
 *
 * Interface.Plugins.Datatables.RowsAjax page content scripts. Initialized from scripts.js file.
 *
 *
 */

class RowsAjaxRoles {
    constructor() {
        if (!jQuery().DataTable) {
            console.log("DataTable is null!");
            return;
        }

        // Datatable instance
        this._datatable;

        // Controls and select helper
        this._datatableExtend;

        // Datatable single item height
        this._staticHeight = 62;

        this._createInstance();
        this._extend();
    }

    // Creating datatable instance. Table data is provided by json/products.json file and loaded via ajax
    _createInstance() {
        const _this = this;
        this._datatable = jQuery("#datatable_roles").DataTable({
            scrollX: true,
            ajax: "/roles.json",
            sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>', // Hiding all other dom elements except table and pagination
            pageLength: 10,
            columns: [
                { data: "name" },
                { data: "created_at" },
                { data: "permissions" },
            ],
            language: {
                paginate: {
                    previous: '<i class="cs-chevron-left"></i>',
                    next: '<i class="cs-chevron-right"></i>',
                },
            },
            initComplete: function (settings, json) {
                _this._setInlineHeight();
            },
            drawCallback: function (settings) {
                _this._setInlineHeight();
            },
            columnDefs: [
                // Adding Name content as an anchor with a target #
                {
                    targets: 0,
                    render: function (data, type, row, meta) {
                        return (
                            '<a class="list-item-heading body" href="/roles/' + row.id + '/edit">' + data + "</a>"
                        );
                    },
                },
                {
                    targets: 1,
                    render: function(data) {
                        return (
                            '<h4 class="text-small">' + data + '</h4>'
                        )
                    }
                },
                // Adding Role content as a span with a badge class
                {
                    targets: 2,
                    render: function (data, type, row, meta) {
                        var permissionsRole = '';
                        data.forEach(permission => {
                            permissionsRole +=
                                '<span class="badge bg-outline-primary me-sm-1">' + permission.name + "</span>";
                        });
                        return permissionsRole
                    },
                },
            ],
        });
    }

    // Extending with DatatableExtend to get search, select and export working
    _extend() {
        this._datatableExtend = new DatatableExtend({
            datatable: this._datatable,
        });
    }

    // Setting static height to datatable to prevent pagination movement when list is not full
    _setInlineHeight() {
        if (!this._datatable) {
            return;
        }
        const pageLength = this._datatable.page.len();
        document.querySelector(".dataTables_scrollBody").style.height =
            this._staticHeight * pageLength + "px";
    }
}
