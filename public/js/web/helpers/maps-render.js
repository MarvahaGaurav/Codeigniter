(function ($, window) {
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

    window.mapRender = mapRender;
    window.map = window.map || map;
})($, window);
