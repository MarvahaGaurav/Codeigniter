$(window).load(function () {
       initMap();
       $("#location").focus(function () {
           $("#locationModal").modal("show");
           
       });
       $('#locationModal').on('hidden.bs.modal', function (e) {
           $("#location").val($("#location1").val());
       });
       $('#locationModal').on('shown.bs.modal', function () {
			google.maps.event.trigger(map, "resize");
		});
      
   });
   var map;
   var marker = false;
   var geocoder;
//    var centerOfMap = new google.maps.LatLng(28.535517, 77.391029);

   function initMap() {

       var mapProp = {
           center: new google.maps.LatLng(28.535517, 77.391029),
           zoom: 15,
           draggable: true
       };
       var input = document.getElementById('location1');
       var autocomplete = new google.maps.places.Autocomplete(input);

       map = new google.maps.Map(document.getElementById("map"), mapProp);
       geocoder = new google.maps.Geocoder();
       marker = new google.maps.Marker({
           position: new google.maps.LatLng(),
           map: map,
           draggable: true
       });

       google.maps.event.addListener(autocomplete, 'place_changed', function () {

           var place = autocomplete.getPlace();

           latlng = new google.maps.LatLng(place.geometry.location.lat(), place.geometry.location.lng());

           map.setCenter(latlng);

           map.setZoom(15);

           marker.setPosition(latlng);
           // Update current position info.
           //updateMarkerPosition(latlng);
           //updateMarkerAddress($('#eventLocation').val());
           markerLocation();
       });

       markerLocation();
       google.maps.event.addListener(marker, 'dragend', function (event) {
           markerLocation();
       });
       
          var pacContainerInitialized = false;
				  $('#location1').keypress(function() {
				 if (!pacContainerInitialized) {
				 $('.pac-container').css('z-index', '9999');
				 pacContainerInitialized = true;
				 }
		  });

   }
   ;

   $('#marker').click(function () {
       $('.map-box').slideUp();
   })

   function resizeMap() {
       if (typeof map == "undefined")
           return;
       setTimeout(function () {
           resizingMap();
       }, 400);
   }

   function resizingMap() {
       if (typeof map == "undefined")
           return;
       var center = map.getCenter();
       google.maps.event.trigger(map, "resize");
       map.setCenter(center);
   }
//This function will get the marker's current location and then add the lat/long
//values to our textfields so that we can save the location.
   function markerLocation() {
       var currentLocation = marker.getPosition();
       document.getElementById('latitude').value = currentLocation.lat(); //latitude
       document.getElementById('longitude').value = currentLocation.lng(); //latitude
       //~ google.maps.event.addListener(marker, 'dragend', function(event){
       geocodePosition(currentLocation);
       //~ });

   }
//Load the map when the page has finished loading.
//~ google.maps.event.addDomListener(window, 'load', initMap);

   function geocodePosition(pos) {
       geocoder.geocode({
           latLng: pos
       }, function (responses) {
           if (responses && responses.length > 0) {
               document.getElementById('location1').value = responses[0].formatted_address;
               //document.getElementById('lng').value = responses[0].formatted_address+'dfad';
           } else {
               document.getElementById('location1').value = '';
           }
       });
   }
