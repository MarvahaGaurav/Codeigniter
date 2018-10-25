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
        helper: 'web/helpers/project',
        mapsAPI: 'https://maps.googleapis.com/maps/api/js?key=AIzaSyAGhlvsdgd8ZPkLJDhkWblTEbvxPU_WAko&libraries=places',
        mapsRender: 'web/helpers/maps-render',
        mapsMarker: 'web/helpers/maps-marker',
        mapsPlaces: 'web/helpers/maps-places',
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
        location: [ 'autocomplete' ],
        mapsAPI: ['jquery'],
        mapsRender: ['mapsAPI'],
        mapsPlaces: ['mapsMarker'],
        mapsMarker: ['mapsRender'],
    }
} );

requirejs(
        [ 
            "jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator",
            "helper", "autocomplete", "location", "selectPicker", "mapsAPI", "mapsRender",
            "mapsPlaces", "mapsMarker"
        ],
        function ( $ ) {
            var $otherProjectCount = $("#other-project-count");

            $otherProjectCount.on('keypress', function () {
                var self = this,
                    $self = $(this),
                    val = parseInt($self.val());

                if (val < 12) {
                    $self.val('');
                    $self.val(11);
                }
            });

            $("#increment-others").on("click", function() {
                var otherProjectCount = parseInt($otherProjectCount.val());
                $otherProjectCount.val(otherProjectCount + 1);
            });

            $("#decrement-others").on("click", function() {
                var otherProjectCount = parseInt($otherProjectCount.val());
                if (otherProjectCount < 12 ) {
                    $otherProjectCount.val(11);
                    return;
                }
                $otherProjectCount.val(otherProjectCount - 1);
            }); 
            // $( "#levels" ).selectpicker();
        },
        function () {

        }
);
