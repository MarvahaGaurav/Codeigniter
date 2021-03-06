requirejs.config({
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
        helper: 'web/helpers/edit_project',
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
        helper: ['jqueryValidator'],
        autocomplete: ['jquery'],
        location: ['autocomplete'],
        mapsAPI: ['jquery'],
        mapsRender: ['mapsAPI'],
        mapsPlaces: ['mapsMarker'],
        mapsMarker: ['mapsRender'],
    }
});

requirejs(
    [
        "jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator",
        "helper", "autocomplete", "location", "selectPicker", "mapsAPI", "mapsRender",
        "mapsPlaces", "mapsMarker"
    ],
    function ($) {
        var $otherProjectCount = $("#other-project-count");

        var $otherLevelCountDiv = $("#other-level-count-div");
        var levelCount = $("#project_level_count").val();
        

        $(document).ready(function() {
            var level =$('#levels :selected').val();
           
            if(level=='others') {
                $('#levels').closest(".block-div")
                .removeClass("col-lg-4 col-md-4 col-sm-4 col-xs-12")
                .addClass("col-lg-2 col-md-2 col-sm-2 col-xs-6");
            $otherLevelCountDiv.removeClass("concealable");
            $('#levels').attr('name', '');
            $otherProjectCount.attr('name', 'levels');
            $otherProjectCount.val(levelCount);
            }
        });

        $otherProjectCount.on('keypress', function () {
            var self = $otherProjectCount,
                $self = $(this),
                val = parseInt($self.val());

            if (val < 12) {
                $self.val('');
                $self.val(11);
            }
        });

        $("#increment-others").on("click", function () {
            var otherProjectCount = parseInt($otherProjectCount.val());
            $otherProjectCount.val(otherProjectCount + 1);
        });

        $("#decrement-others").on("click", function () {
            var otherProjectCount = parseInt($otherProjectCount.val());
            if (otherProjectCount < 12) {
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
