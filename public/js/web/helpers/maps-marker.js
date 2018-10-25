(function($, window) {

    var center = window.map.getCenter();
    var position = {lat: parseFloat(center.lat()), lng: parseFloat(center.lng())}

    var marker = new google.maps.Marker({
        position: position,
        map: window.map,
        title: 'SG'
    });

    window.marker = window.marker || marker;
})($, window);