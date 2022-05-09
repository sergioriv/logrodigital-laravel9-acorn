// require('./bootstrap');

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();

// $( document ).ready(function() {

function callNotify(type = "success", title, message = "") {
    let icon = "cs-check";
    let color = "primary";

    switch (type) {
        case "fail":
            icon = "cs-error-hexagon";
            color = "danger";
            break;
        case "info":
            icon = "cs-info-hexagon";
            color = "info";
            break;

        default:
            break;
    }

    jQuery.notify(
        {
            title: title,
            message: message,
            icon: icon,
        },
        {
            type: color,
            delay: 5000,
        }
    );
}

if (jQuery().select2) {
    jQuery.fn.select2.defaults.set("theme", "bootstrap4");
    jQuery(".kii_select2").select2({ minimumResultsForSearch: Infinity });
}

/* if (jQuery().DataTable) {
    jQuery(".kii_datatable").DataTable({
        scrollX: true,
        paging: false,
        ordering: false,
        info: false,
        sDom: '<"row"<"col-sm-12"<"table-container"t>r>><"row"<"col-12"p>>', // Hiding all other dom elements except table and pagination
    });
}
 */

