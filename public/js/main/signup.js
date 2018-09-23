requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    jqueryScrollbar: "plugin/jquery.scrollbar.min"
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryScrollbar: ['jquery']
  }
});

requirejs(
  ["jquery", "bootstrap", "common", "jqueryScrollbar"],
  function ($) {
    // upload the image on click
    $('#upload').change(function () {
      var file = this.files[0];
      var reader = new FileReader();
      reader.onloadend = function () {
        $('#image-view').css('background-image', 'url("' + reader.result + '")');
      }
      if (file) {
        reader.readAsDataURL(file);
      } else {
        console.log('not done');
      }
    });

    $(document).ready(function () {

      //show pop up on from select options
      $('select').change(function () {
        if ($(this).val() == "business") {
          $('.wholesaler-field').css('display', 'none');
        }
      });

      //show pop up on from select options
      $('select').change(function () {
        if ($(this).val() == "wholesaler") {
          $('.wholesaler-field').css('display', 'block');
          $('.installer-field').css('display', 'none');
        }
      });

      //show pop up on from select options
      $('select').change(function () {
        if ($(this).val() == "installer") {
          $('.installer-field').css('display', 'block');
          $('.wholesaler-field').css('display', 'none');
        }
      });

    })
  },
  function () {

  }
);
