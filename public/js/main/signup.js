requirejs.config({
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
        appinventivCropper: 'cropperAssets/appinventivCropper',
        signUpCropper: 'cropperAssets/SignupCropper',
        mapsAPI: 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDhP9xXJsFempHkFwsNn0AuDd89WtTlmI0&libraries=places',
        mapsRender: 'web/helpers/maps-render',
        mapsMarker: 'web/helpers/maps-marker',
        mapsPlaces: 'web/helpers/maps-places',
    },
    shim: {
        //dependencies
        bootstrap: ['jquery'],
        selectPicker: ['bootstrap'],
        common: ['bootstrap'],
        jqueryScrollbar: ['jquery'],
        jqueryValidator: ['jquery'],
        cropper_JS: ['jquery'],
        appinventivCropper: ['cropper_JS'],
        signUpCropper: ['cropper_JS'],
        helper: ['jqueryValidator'],
        autocomplete: ['jquery'],
        location: ['autocomplete'],
        mapsAPI: ['jquery'],
        mapsRender: ['mapsAPI'],
        mapsPlaces: ['mapsMarker'],
        mapsMarker: ['mapsRender']
    }
});

requirejs(
    ["jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "helper", "autocomplete",
        "location", "selectPicker", "cropper_JS", "appinventivCropper", "mapsAPI", "mapsRender", "signUpCropper",
        "mapsPlaces", "mapsMarker"],
    function ($) {
        fetchLocation('/xhttp/cities');

        $("#select-user-types").selectpicker();
        $(".contact-number").selectpicker();
        $("select[name='country']").selectpicker();
    },
    function () {

    }
);
