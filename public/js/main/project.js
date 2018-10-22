requirejs.config( {
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        selectPicker: "bootstrap-select.min",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
        jqueryValidator: 'jquery.validate.min',
        autocomplete: 'jquery.autocomplete.min',
        location: 'lib/location',
        helper: 'web/helpers/project'
    },
    shim: {
        //dependencies
        bootstrap: [ 'jquery' ],
        selectPicker: [ 'bootstrap' ],
        common: [ 'bootstrap' ],
        jqueryScrollbar: [ 'jquery' ],
        jqueryValidator: [ 'jquery' ],
        helper: [ 'jqueryValidator' ],
        autocomplete: [ 'jquery' ],
        location: [ 'autocomplete' ]
    }
} );

requirejs(
        [ "jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "helper", "autocomplete", "location", "selectPicker" ],
        function ( $ ) {
            fetchLocation( '/xhttp/cities' );

            $( "#select-user-types" ).selectpicker();
            $( ".contact-number" ).selectpicker();
            $( "select[name='country']" ).selectpicker();
        },
        function () {

        }
);
