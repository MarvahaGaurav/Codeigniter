(function($, window) {

    var center = window.map.getCenter();
    var position = {lat: parseFloat(center.lat()), lng: parseFloat(center.lng())}

    var marker = new google.maps.Marker({
        position: position,
        map: window.map,
        draggable: true,
        title: 'SG'
    });

    marker.addListener('dragend', function () {
        var lat = marker.position.lat();
        var lng = marker.position.lng();
        var LatLng = new google.maps.LatLng(lat, lng);
        window.map.setCenter(LatLng);
        geocodePosition(LatLng);
    });

    window.marker = window.marker || marker;
})($, window);