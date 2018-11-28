requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    jqueryScrollbar: "plugin/jquery.scrollbar.min",
    jqueryValidator: 'jquery.validate.min'
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryScrollbar: ['jquery'],
    jqueryValidator: ['jquery']
  }
});

requirejs(
  ["jquery", "bootstrap", "common", "jqueryScrollbar", 'jqueryValidator'],
  function ($) {

    var $addPriceForm = $("#add-price-form");

    $addPriceForm.validate({
      rules: {
        price_per_luminaries: {
          required: true,
          number: true,
          maxlength: 12
        },
        installation_charges: {
          required: true,
          number: true,
          maxlength: 12
        },
        discount_price: {
          required: true,
          number: true,
          maxlength: 12
        }
      }
    });

    var $pricePerLuminaries = $("#price-per-luminaries"),
      $installationCharges = $("#installation-charges"),
      $discountPrice = $("#discount-price"),
      $projectRoomIdField = $("#project-room-id"),
      $targetHandler = $("#target-handler"),
      $subTotal = $("#subtotal"),
      $total = $("#total"),
      $modalPirceText = $(".modal-price-text");

    $(".installer-add-price").on("click", function () {
      var self = this,
        $self = $(self),
        priceData = $self.attr("data-room-price"),
        projectRoomId = $self.attr("data-project-room-id"),
        targetKey = $self.attr("data-target-value"),
        modalText = $self.attr('data-modal-text'),
        action = $self.attr('data-action');

      $modalPirceText.html(modalText);

      $projectRoomIdField.val(projectRoomId);
      $targetHandler.attr("data-target", "#add-price-" + targetKey);

      if (priceData.trim().length > 0) {
        priceData = JSON.parse(priceData);
        if (
          ("price_per_luminaries" in priceData) &&
          ("installation_charges" in priceData) &&
          ("discount_price" in priceData)
        ) {
          $pricePerLuminaries.val(priceData.price_per_luminaries);
          $installationCharges.val(priceData.installation_charges);
          $discountPrice.val(priceData.discount_price);
          $subTotal.html(priceData.subtotal);
          $total.html(priceData.total);
        } else {
          $pricePerLuminaries.val('');
          $installationCharges.val('');
          $discountPrice.val('');
          $subTotal.html('');
          $total.html('');
        }
      }

      $("#add-price-modal").modal('show');
    });

    $("#add-price-submit").on("click", function () {
      var self = this,
        $self = $(self),
        target = $targetHandler.attr('data-target');

      if ($addPriceForm.valid()) {
        var formData = getFormData($addPriceForm);

        $.ajax({
          url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/rooms/add-price",
          method: "POST",
          data: formData,
          dataType: "json",
          beforeSend: function () {
            var html = $self.html();
            $self.html("<i class='fa fa-circle-o-notch fa-spin'></i>" + html);
            $self.attr("disabled", "disabled");
          },
          success: function (response) {
            $self.find(".fa").remove();
            $self.removeAttr("disabled");
            if (response.success) {
              window.location.reload();
              displaySuccessMessage(response.msg);
              $(target).attr("data-room-price", JSON.stringify(response.data));
              $("#add-price-modal").modal('hide');
            } else {
              displayErrorMessage(response.msg);
            }
          },
          error: function (error) {
            $self.removeAttr("disabled");
            $self.find(".fa").remove();
            window.alert(error.status);
          }

        });
      }
    });

    $(".change-room-count").on('click', function () {
      var self = this,
        $self = $(self),
        url = $self.attr("data-url")
      data = JSON.parse($self.attr("data-json"));

      var html = $self.html();
      $.ajax({
        url: url,
        data: data,
        dataType: 'json',
        method: "POST",
        beforeSend: function () {
          $self.html("<i class='fa fa-circle-o-notch fa-spin' aria-hidden='true'></i>");
        },
        success: function (response) {
          if (response.success) {
            $self.html(html);
            $("#room-count").val(response.count);
          } else {
            $self.html(html);
            displayErrorMessage(response.error);
          }
        }, error: function (error) {
          $self.html(html);
          displayErrorMessage('error 500');
        }
      });
    });

    $("#mark-as-done-btn").on("click", function () {
      var self = this,
        $self = $(self),
        data = JSON.parse($self.attr('data-level-data'));

      $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/mark-as-done",
        method: "POST",
        data: data,
        dataType: "json",
        beforeSend: function () {
          var html = $self.html().trim();
          $self.html("<i class='fa fa-circle-o-notch fa-spin'></i>" + html);
        },
        success: function (response) {
          if (response.success) {
            window.location.href = $self.attr("data-redirect-to");
          }
        },
        error: function (error) {
          $self.find(".fa").remove();
        }
      });
    });
  },
  function () {

  }
);
