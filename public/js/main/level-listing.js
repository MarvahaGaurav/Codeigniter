requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    jqueryScrollbar: "plugin/jquery.scrollbar.min",
    jqueryStickyTable: "jquery.stickytable.min"  
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryScrollbar: ['jquery'],
    jqueryStickyTable: ['bootstrap']      
  }
});

requirejs(
  ["jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryStickyTable"],
  function ($) {
    //jquery table here  
      
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
        success: function(response) {
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

      
  
      $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/installer/quotePrice",
        method: "POST",
        dataType: "json",
        data: formData,
        beforeSend: function () {
          var html = $self.html().trim();
          $self.html("<i class='fa fa-circle-o-notch fa-spin'></i> " + html);
        },
        success: function(response) {
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

        //console.log($installerSubmitData);

      var formData = getFormData($installerSubmitData);
      var text = $self.text();
      
      var email = $('contact-email').val();

      if(formData.email=='') {
        console.log("please enter email");
        return false;
      }
      $.ajax({
        url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/installer/sendmail",
        method: "POST",
        dataType: "json",
        data: formData,
        beforeSend: function () {
          var html = $self.html().trim();
          $self.html("<i class='fa fa-circle-o-notch fa-spin'></i> " + html);
        },
        success: function(response) {
          if (response.success) {
              //window.location.reload();
              window.location.href=window.location.protocol + "//" + window.location.host+"/home/quotes/awaiting";
          }
        },
        error: function (error) {
          console.log(error);
          $self.html(text);
        }
      })
    });

    $( function() {
      $( "#datepicker" ).datepicker();
    } );

    



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
          $self.html("<i class='fa fa-circle-o-notch fa-spin'></i> "+html);
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
