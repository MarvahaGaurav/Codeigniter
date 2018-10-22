requirejs.config( {
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        // bootstrap: "https://stackpath.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min",
        common: "web/common",
        selectPicker: "bootstrap-select.min",
        // selectPicker: "https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.2/js/bootstrap-select.min",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
        jqueryValidator: 'jquery.validate.min',
        autocomplete: 'jquery.autocomplete.min',
        location: 'lib/location',
        helper: 'web/helpers/signup',
        cropper_JS: 'cropperAssets/cropper',
        appinventivCropper: 'cropperAssets/appinventivCropper'
    },
    shim: {
        //dependencies
        bootstrap: [ 'jquery' ],
        selectPicker: [ 'bootstrap' ],
        common: [ 'bootstrap' ],
        jqueryScrollbar: [ 'jquery' ],
        jqueryValidator: [ 'jquery' ],
        cropper_JS: [ 'jquery' ],
        appinventivCropper: [ 'cropper_JS' ],
        helper: [ 'jqueryValidator' ],
        autocomplete: [ 'jquery' ],
        location: [ 'autocomplete' ]
    }
} );

requirejs(
        [ "jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "helper", "autocomplete", "location", "selectPicker", "cropper_JS", "appinventivCropper", ],
        function ( $ ) {
            fetchLocation( '/xhttp/cities' );

            $( "#select-user-types" ).selectpicker();
            $( ".contact-number" ).selectpicker();
            $( "select[name='country']" ).selectpicker();
        },
        function () {

        }
);
