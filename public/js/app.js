const lang = $('html').attr('lang');

// function callNotify(type = "success", title, message = "") {
function callNotify(notify) {

    var n = notify.split('|');

    var type = n[0];
    var title = n[1];
    var message = ""

    if (n[2])
        message = n[2];

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
            delay: 10000,
        }
    );
}

if (jQuery().select2) {
    jQuery.fn.select2.defaults.set("theme", "bootstrap4");
}

if (jQuery().datepicker) {
    jQuery("[logro='datePicker']").datepicker({
        language: 'es',
        format: 'yyyy-mm-dd',
        endDate: '-1y',
        autoclose: true,
    });
}

function DataTableInterval(datatable) {
    setInterval(() => {
        jQuery(datatable).DataTable().ajax.reload();
    }, 2000);
}

