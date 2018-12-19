requirejs.config( {
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
        helper: 'web/helpers/select_product',
        searchProduct: "web/helpers/select_product_search"
    },
    shim: {
        //dependencies
        bootstrap: [ 'jquery' ],
        common: [ 'bootstrap' ],
        jqueryScrollbar: [ 'jquery' ],
        helper: [ 'jquery' ],
        searchProduct: ['helper']
    }
} );

requirejs(
        [ "jquery", "bootstrap", "common", "jqueryScrollbar", "helper", 'searchProduct' ],
        function ( $ ) {

        },
        function () {

        }
);

