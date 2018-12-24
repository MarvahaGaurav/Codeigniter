requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    jqueryValidator: "jquery.validate.min",
    jqueryScrollbar: "plugin/jquery.scrollbar.min"
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryValidator: ['jquery'],
    jqueryScrollbar: ['jquery']
  }
});

requirejs(
  ["jquery", "bootstrap", "common", "jqueryValidator", "jqueryScrollbar"],
  function ($) {
    var $discountPrice = $("input[name='discount_price']");
    
    setTimeout(function () {
      $("div.error").fadeOut(250);
    }, 3000);

    if ($("#password-error").attr("data-message").trim().length > 0) {
      $("#old-password").focus();
    }

    $("#old-password").on("keydown", function () {
      var $self = $(this),
        selfValue = $(this).val().trim();

      if (selfValue.length >= 7) {
        console.log('8');
        $("#new-password").rules("add", {
          minlength: 8,
          maxlength: 30
        });
      }
    });

    $("#settings-form").validate({
      rules: {
        discount_price: {
          required: true,
          min: 0,
          max: 100
        },
        langauge: "required",
        currency: "required",
        old_password: {
          minlength: 8,
          maxlength: 30
        },
        new_password: {
          required: function () {
            var $oldPassword = $("#old-password");
            if ($oldPassword.val().trim().length < 8) {
              $("#new-password").val('');
            }
            return $oldPassword.val().trim().length >= 8;
          }
        },
        confirm_password: {
          required: function () {
            var $oldPassword = $("#old-password");
            if ($oldPassword.val().trim().length < 8) {
              $("#confirm-password").val('');
            }
            return $oldPassword.val().trim().length >= 8;
          },
          equalTo: '#new-password'
        }
      },
      errorPlacement: function (error, $element) {
        if ($element.attr("name") === "discount_price") {
          $("#discount-price-error").html(error);
        } else {
          $element.after(error);
        }
      }
    });

    $(document).on('click', '.plus', function () {
      $('.count').val(parseInt($('.count').val()) + 5);
      $discountPrice.trigger("keyup");
    });
    $(document).on('click', '.minus', function () {
      $('.count').val(parseInt($('.count').val()) - 5);
      if ($('.count').val() == 0) {
        $('.count').val(1);
      }
      $discountPrice.trigger("keyup");
    });
  },
  function ($error) {

  }
);