(function ($, window) {
    $("#address-lat").on("change", function () {
        var self = this,
            $self = $(self),
            value = $self.val();

        console.log(value, 'here');
        
        // if (value.trim().length > 0) {
        //     $("#address-map-error").html('');
        // }
    });

    map = new google.maps.Map(document.getElementById('maps-box'), {
        center: { lat: 28.53551610, lng: 77.39102650 },
        zoom: 10
    });

    var mapRender = function (lat, lng) {
        map = new google.maps.Map(document.getElementById('maps-box'), {
            center: { lat: lat, lng: lng },
            zoom: 10
        });

        window.map = map;
    }

    var updateMapAddress = function (address) {
        document.getElementById('address').value = address;
    }

    var updateMapCoordinates = function(LatLng) {
        document.getElementById('address-lat').value = LatLng.lat();
        document.getElementById('address-lng').value = LatLng.lng();
    }

    var geocoder = new google.maps.Geocoder();

    var geocodePosition = function (LatLng) {
        geocoder.geocode({latLng: LatLng}, function (response) {
            if (response && response.length > 0) {
                document.getElementById('maps-places').value = response[0]['formatted_address'];
                window.updateMapCoordinates(LatLng);
                window.updateMapAddress(response[0]['formatted_address']);
            }
        });
    }

    window.updateMapAddress = updateMapAddress;
    window.updateMapCoordinates = updateMapCoordinates;
    window.geocodePosition = window.geocodePosition|| geocodePosition;
    window.mapRender = mapRender;
    window.map = window.map || map;
})($, window);
