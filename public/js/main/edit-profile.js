requirejs.config({
  baseUrl: 'public/js',
  waitSeconds: 60,
  paths: {
    jquery: 'jquery.min',
    bootstrap: 'bootstrap.min',
    common: 'web/common',
    jqueryValidator: 'jquery.validate.min',
    viewBuilder: 'lib/view-builder',
    location: 'lib/location',
    jqueryScrollbar: 'plugin/jquery.scrollbar.min'
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    viewBuilder: ['jquery'],
    jqueryValidator: ['jquery'],
    location: ['viewBuilder'],
    jqueryScrollbar: ['jquery']
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
    'location'
  ],
  function($) {
    fetchLocation('xhttp/cities');
    var $companyName = $("input[name='company_name']"),
        $companyRegNumber = $("input[name='company_reg_number']");

    $('#signupwebform').validate({
      rules: {
        name: {
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          maxlength: 255
        },
        prmccode: {
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          }
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
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          }
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
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          }
        },
        city: {
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          }
        },
        zip_code: {
          required: {
            depends: function() {
              $(this).val($.trim($(this).val()));
              return true;
            }
          },
          maxlength:10
        }
      }
    });

    if ( $companyName.length > 0 ) {
      $companyName.rules("add", {
        required: {
          depends: function() {
            $(this).val($.trim($(this).val()));
            return true;
          }
        },
        maxlength: 255
      });
      $companyRegNumber.rules("add", {
        required: {
          depends: function() {
            $(this).val($.trim($(this).val()));
            return true;
          }
        },
        maxlength: 30
      });
  }
  },
  function() {}
);
