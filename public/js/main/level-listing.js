requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    jqueryScrollbar: "plugin/jquery.scrollbar.min",
    jqueryStickyTable: "jquery.stickytable.min",
    datetime: "bootstrap-datetimepicker",
    moment_js: "moment"
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryScrollbar: ['jquery'],
    jqueryStickyTable: ['bootstrap'],
    datetime: ['bootstrap'],
    moment_js: ["datetime"]
  }
});

requirejs(
  ["jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryStickyTable", "datetime", "moment"],
  function ($) {
    //jquery table here  
    $('#dateTimePicker').datetimepicker({
      format: 'L'
    });
    $(".levels-listing-wrapper").on('click', function (event) {
      var targetEvent = $(event.target);
      if (targetEvent.hasClass('level-btn')) {
        event.preventDefault();
      } else {
        window.location.href = $(this).attr('data-redirect-to');
      }
    });

    $("#final-price-submit").on("click", function () {
      var self = this,
        $self = $(this),
        $installerSubmitPrice = $("#installer-submit-price");

      var formData = getFormData($installerSubmitPrice);

      var text = $self.text();
      $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/installer/price",
        method: "POST",
        dataType: "json",
        data: formData,
        beforeSend: function () {
          var html = $self.html().trim();
          $self.html("<i class='fa fa-circle-o-notch fa-spin'></i> " + html);
        },
        success: function (response) {
          if (response.success) {
            window.location.reload();
          }
        },
        error: function (error) {
          $self.html(text);
        }
      })
    });

    $("#final-quote-email-now").on("click", function () {
      var self = this,
        $self = $(this),
        $installerSubmitPrice = $("#installer-submit-price");


      var formData = getFormData($installerSubmitPrice);
      var text = $self.text();

      console.log(formData);
      //return false;

      $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/installer/quotePrice",
        method: "POST",
        dataType: "json",
        data: formData,
        beforeSend: function () {
          var html = $self.html().trim();
          $self.html("<i class='fa fa-circle-o-notch fa-spin'></i> " + html);
        },
        success: function (response) {
          if (response.success) {
            //window.location.reload();
            $('#project-final-price-modal').modal('hide');
            $('#send-email-to-customer').modal('show');

          }
        },
        error: function (error) {
          $self.html(text);
        }
      })
    });

    $("#send-email-to-customer").on("click", function () {
      var self = this,
        $self = $(this),
        $installerSubmitData = $("#installer-send-email");

      console.log($('#send-email-to-customer').text().trim());

      var formData = getFormData($installerSubmitData);
      var text = $self.text();
      var email = $("#contact-email").val();

      var emailptrn = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;


      if (email.length == 0) {
        document.getElementById('emails').innerHTML = "Please fill this field";
        $("#emails").css("color", "red");

        setTimeout(function () {
          $('#emails').text('');
        }, 2000);
      } else if (email.match(emailptrn)) {
        $.ajax({
          url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/installer/sendmail",
          method: "POST",
          dataType: "json",
          data: formData,
          beforeSend: function () {
            var html = $self.html().trim();
            console.log(html);
            $self.html("<i class='fa fa-circle-o-notch fa-spin'></i> " + html);
          },
          success: function (response) {
            if (response.success) {
              //window.location.reload();
              window.location.href = window.location.protocol + "//" + window.location.host + "/home/quotes/awaiting";
            }
          },
          error: function (error) {
            console.log(error);
            $self.html(text);
          }
        })

      } else if (!email.match(emailptrn)) {
        document.getElementById('emails').innerHTML = "Please enter valid email";
        $("#emails").css("color", "red");

        setTimeout(function () {
          $('#emails').text('');
        }, 2000);
      } else {
        alert("11111")
      }




    });








    var $cloneSource = $("#clone-source"),
      $cloneDestination = $("#clone-destination"),
      $levelCloneBtn = $("#level-clone-submit"),
      $levelCloneModal = $("#level-clone-modal");

    $(".level-clone-btn").on('click', function () {
      var self = this,
        $self = $(self),
        levelCloneSource = $self.attr("data-source-levels"),
        levelCloneDestination = JSON.parse($self.attr("data-destination-levels")),
        optionSelect = $levelCloneBtn.attr('data-text');

      // var option = "<option>"+ optionSelect +"</option>";

      var cloneSourceHTML = "<option value='" + levelCloneSource + "'>" + levelCloneSource + "</option>";

      var destinationSourceHTML = levelCloneDestination.reduce(function (acc, current) {
        return acc + "<option value='" + current + "'>" + current + "</option>";
      }, '');

      $cloneSource.html(cloneSourceHTML);
      $cloneDestination.html(destinationSourceHTML);

      $levelCloneModal.modal('show');
    });

    $("#level-clone-submit").on('click', function () {
      var self = this,
        $self = $(this),
        data = JSON.parse($self.attr("data-csrf"));

      data.reference_level = $cloneSource.val();
      data.destination_levels = $cloneDestination.val();
      $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/levels/clone",
        method: "POST",
        data: data,
        dataType: "json",
        beforeSend: function () {
          var html = $self.html();
          $self.html("<i class='fa fa-circle-o-notch fa-spin'></i> " + html);
          $self.attr("disabled", "disabled");
        },
        success: function (response) {
          $self.find('.fa').remove();
          $self.removeAttr("disabled");
          if (response.success) {
            window.location.reload();
          } else {
            displayErrorMessage(response.msg);
          }
        },
        error: function (error) {
          $self.find('.fa').remove();
          $self.removeAttr("disabled");
          window.alert("Error please try again");
        }
      })
    });

    $(".mark-as-done").on("click", function () {
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
          $self.html("<i class='fa fa-circle-o-notch fa-spin'></i> " + html);
        },
        success: function (response) {
          if (response.success) {
            window.location.reload();
            // var $levelsListingWrapper = $self.closest(".levels-listing-wrapper");
            // $levelsListingWrapper.next().removeClass("disabled-level");
            // if ($(".disabled-level").length == 0) {
            //   window.location.reload();
            // }
            // var $li = $self.closest("li");
            // $li.attr("title", response.message);
            // $li.addClass("level-done-li");
            // $li.html("<i class='fa fa-check-circle level-done-check'></i>");
            // $self.find(".fa").remove();
            // $self.html(response.button_message);
            // $self.attr("disabled", "disabled");
          }
        },
        error: function (error) {
          window.location.reload();
          $self.find(".fa").remove();
        }
      });

    });

  },
  function () {

  }


);