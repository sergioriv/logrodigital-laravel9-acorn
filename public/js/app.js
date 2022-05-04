// require('./bootstrap');

// import Alpine from 'alpinejs';

// window.Alpine = Alpine;

// Alpine.start();

function callNotify(_title, _message = '') {
    jQuery.notify(
        { title: _title, message: _message },
        {
            type: "primary",
            delay: 10000,
            placement: {
                from: "top",
                align: "right",
            },
        }
    );
}
