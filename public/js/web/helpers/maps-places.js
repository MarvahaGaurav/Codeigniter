(function ($, window) {
    var input = document.getElementById('maps-places');
    var autocomplete = new google.maps.places.Autocomplete(input);

    autocomplete.bindTo('bounds', map);

    
    autocomplete.addListener('place_changed', function() {
        var place = autocomplete.getPlace();

        if (!place.geometry) {
            // User entered the name of a Place that was not suggested and
            // pressed the Enter key, or the Place Details request failed.
            window.alert("No details available for input: '" + place.name + "'");
            return;
          }
        
        if (place.geometry && place.geometry.location) {
            var lat = place['geometry']['location']['lat']();
            var lng = place['geometry']['location']['lng']();

            var LatLng = new google.maps.LatLng(lat, lng);
            window.marker.setPosition(LatLng);
            window.map.setCenter(LatLng);
            window.map.setZoom(17);
            window.updateMapAddress(place['formatted_address']);
            window.updateMapCoordinates(LatLng);
        }
    });

})($, window);