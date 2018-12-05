requirejs.config( {
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
        helper: 'web/helpers/caltest'

    },
    shim: {
        //dependencies
        bootstrap: [ 'jquery' ],
        common: [ 'bootstrap' ],
        jqueryScrollbar: [ 'jquery' ],
        helper: [ 'jquery' ]
    }
} );

requirejs(
        [ "jquery", "bootstrap", "common", "jqueryScrollbar", "helper" ],
        function ( $ ) {

        },
        function () {

        }
);
