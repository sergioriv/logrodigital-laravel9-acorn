/**
 *
 * BoxedVariations
 *
 * Interface.Plugins.Datatables.BoxedVariations page content scripts. Initialized from scripts.js file.
 *
 *
 */

class boxedStudentsMatriculate {
  constructor() {
    if (!jQuery().DataTable) {
      console.log('DataTable is null!');
      return;
    }

    this._dataTableScroll = null;

    this._initBoxedMatriculate();
    this._extendDatatables();
  }

  // Boxed variation for pagination, hover and stripe examples
  _initBoxedMatriculate() {
    jQuery('#boxedStudentsMatriculate').DataTable({
      destroy: false,
      paging: false,
      buttons: ['copy', 'excel', 'csv', 'print'],
    //   length: 10,
      sDom: '<"row"<"col-sm-12"<"table-container"<"card"<"card-body half-padding"t>>>>><"row"<"col-12 mt-3"p>>',
      responsive: true,
      language: {
        url: '/json/datatable.spanish.json',
      },
    });
  }

  // Calling extend makes search, page length, print and export work
  _extendDatatables() {
    new DatatableExtend();
  }
}
