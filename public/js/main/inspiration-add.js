requirejs.config({
  baseUrl: 'public/js',
  waitSeconds: 60,
  paths: {
    jquery: 'jquery.min',
    bootstrap: 'bootstrap.min',
    common: 'web/common',
    jqueryValidator: 'jquery.validate.min',
    jqueryScrollbar: 'plugin/jquery.scrollbar.min',
    imageVideoUploader: 'image.video.uploader',
    select2: 'select2.min',
    mapsAPI: 'https://maps.googleapis.com/maps/api/js?key=AIzaSyDhP9xXJsFempHkFwsNn0AuDd89WtTlmI0&libraries=places',
    mapsRender: 'web/helpers/maps-render',
    mapsMarker: 'web/helpers/maps-marker',
    mapsPlaces: 'web/helpers/maps-places',
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryValidator: ['jquery'],
    jqueryScrollbar: ['jquery'],
    select2: ['common'],
    imageVideoUploader: ['select2'],
    mapsAPI: ['jquery'],
    mapsRender: ['mapsAPI'],
    mapsPlaces: ['mapsMarker'],
    mapsMarker: ['mapsRender'],
  }
});

requirejs(
  [
    'jquery',
    'bootstrap',
    'common',
    'jqueryValidator',
    'jqueryScrollbar',
    'imageVideoUploader',
    'select2',
    "mapsAPI",
    "mapsRender",
    "mapsPlaces",
    "mapsMarker"
  ],
  function ($) {
    $('#multiple-checked').select2();
    $(document).ready(function () {
      $('#multiple-checked').select2();
    });
    $('form#add-inspiration').validate({
      ignore: [],
      rules: {
        title: {
          required: true,
          maxlength: 255
        },
        description: {
          required: true,
          maxlength: 255
        },
        address: {
          required: true
        },
        address_lat: {
          required: true
        },
        address_lng: {
          required: true
        },
        'products[]': {
          required: true
        }
      },
      errorPlacement: function (error, $element) {
        if ($element.attr('name') == 'products[]') {
          $("#multicheck-products").after(error);
        } else if ($element.attr('name') == 'address_lat' || $element.attr('name') == 'address_lng' || $element.attr('name') === 'address') {
          $("#address-map-error").html(error);
        } else {
          $element.after(error);
        }
      },
      submitHandler: function (form) {
        $("#inspiration-add-submit").attr("disabled", "disabled").prepend("<i class='fa fa-circle-o-notch fa-spin'></i>")
        form.submit();
      }
    });
  },
  function () { }
);
