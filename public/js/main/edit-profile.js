requirejs.config({
  baseUrl: 'public',
  waitSeconds: 60,
  paths: {
    jquery: 'js/jquery.min',
    bootstrap: 'js/bootstrap.min',
    common: 'js/web/common',
    jqueryValidator: 'js/jquery.validate.min',
    viewBuilder: 'js/lib/view-builder',
    location: 'js/lib/location',
    jqueryScrollbar: 'js/plugin/jquery.scrollbar.min',
    autocomplete: 'js/jquery.autocomplete.min'
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    viewBuilder: ['jquery'],
    jqueryValidator: ['jquery'],
    location: ['autocomplete'],
    jqueryScrollbar: ['jquery'],
    autocomplete: ['jquery']
  }
});

requirejs(
  [
    'jquery',
    'bootstrap',
    'common',
    'jqueryScrollbar',
    'viewBuilder',
    'jqueryValidator',
    'location',
    'autocomplete'
  ],
  function($) {
    fetchLocation('/xhttp/cities');
    var $companyName = $("input[name='company_name']"),
        $companyRegNumber = $("input[name='company_reg_number']");

    $('#signupwebform').validate({
      rules: {
        name: {
          required: true,
          maxlength: 255
        },
        prmccode: {
          required: true
        },
        phone: {
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          number: true,
          maxlength: 20
        },
        altccode: {
          required: true
        },
        alt_phone: {
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          number: true,
          maxlength: 20
        },
        country: {
          required: true
        },
        city: {
          required: true
        },
        zip_code: {
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          number: true,
          maxlength:10
        }
      }
    });



    if ( $companyName.length > 0 ) {
      $companyName.rules("add", {
        required: true,
        maxlength: 255
      });
      $companyRegNumber.rules("add", {
        required: true,
        maxlength: 30
      });
  }
  },
  function() {}
);
