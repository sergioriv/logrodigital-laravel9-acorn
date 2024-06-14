/**
 *
 * RowsAjax
 *
 * Interface.Plugins.Datatables.RowsAjax page content scripts. Initialized from scripts.js file.
 *
 *
 */

class DatatablesMyInstitution {
    constructor() {
        if (!jQuery().DataTable) {
            console.log("DataTable is null!");
            return;
        }

        this._dataTableScroll = null;

        this._initBoxedRector();
        this._initBoxedCoordination();
        this._initBoxedTeachers();
        this._initBoxedOrientation();
        this._initBoxedSecretariat();
        this._extendDatatables();
    }

    _initBoxedTeachers() {
        const _this = this;
        jQuery("#datatable_teachers").DataTable({
            destroy: false,
            paging: true,
            buttons: false,
            length: 10,
            sDom: '<"row"<"col-sm-12"<"table-container"<"half-padding"t>>>><"row"<"col-12 mt-3"p>>',
            responsive: true,
            language: {
                url: "/json/datatable.spanish.json",
            },
            preDrawCallback: function (settings) {
                _this._preDrawCallback($(this), settings);
            },
        });
    }

    _initBoxedRector() {
        const _this = this;
        jQuery("#datatable_rector").DataTable({
            destroy: false,
            paging: true,
            buttons: false,
            length: 10,
            sDom: '<"row"<"col-sm-12"<"table-container"<"half-padding"t>>>><"row"<"col-12 mt-3"p>>',
            responsive: true,
            language: {
                url: "/json/datatable.spanish.json",
            },
            preDrawCallback: function (settings) {
                _this._preDrawCallback($(this), settings);
            },
        });
    }

    _initBoxedCoordination() {
        const _this = this;
        jQuery("#datatable_coordination").DataTable({
            destroy: false,
            paging: true,
            buttons: false,
            length: 10,
            sDom: '<"row"<"col-sm-12"<"table-container"<"half-padding"t>>>><"row"<"col-12 mt-3"p>>',
            responsive: true,
            language: {
                url: "/json/datatable.spanish.json",
            },
            preDrawCallback: function (settings) {
                _this._preDrawCallback($(this), settings);
            },
        });
    }

    _initBoxedOrientation() {
        const _this = this;
        jQuery("#datatable_orientation").DataTable({
            destroy: false,
            paging: true,
            buttons: false,
            length: 10,
            sDom: '<"row"<"col-sm-12"<"table-container"<"half-padding"t>>>><"row"<"col-12 mt-3"p>>',
            responsive: true,
            language: {
                url: "/json/datatable.spanish.json",
            },
            preDrawCallback: function (settings) {
                _this._preDrawCallback($(this), settings);
            },
        });
    }

    _initBoxedSecretariat() {
        const _this = this;
        jQuery("#datatable_secretariat").DataTable({
            destroy: false,
            paging: true,
            buttons: false,
            length: 10,
            sDom: '<"row"<"col-sm-12"<"table-container"<"half-padding"t>>>><"row"<"col-12 mt-3"p>>',
            responsive: true,
            language: {
                url: "/json/datatable.spanish.json",
            },
            preDrawCallback: function (settings) {
                _this._preDrawCallback($(this), settings);
            },
        });
    }

    // Calling extend makes search, page length, print and export work
    _extendDatatables() {
        new DatatableExtend();
    }

    _preDrawCallback(datatable, settings) {
        var api = new $.fn.dataTable.Api(settings);
        var pagination = datatable
            .closest(".dataTables_wrapper")
            .find(".dataTables_paginate");
        pagination.toggle(api.page.info().pages > 1);
    }
}
