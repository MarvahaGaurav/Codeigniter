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
    $(".levels-listing-wrapper").on('click', function (event) {
      var targetEvent = $(event.target);
      if (targetEvent.hasClass('level-btn')) {
        event.preventDefault();
      } else {
        window.location.href = $(this).attr('data-redirect-to');
      }
    });

    $(".level-btn").on("click", function () {
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
            var $levelsListingWrapper = $self.closest(".levels-listing-wrapper");
            $levelsListingWrapper.next().removeClass("disabled-level");
            if ($(".disabled-level").length == 0) {
              window.location.reload();
            }
            var $li = $self.closest("li");
            $li.attr("title", response.message);
            $li.html("<i class='fa fa-check-circle level-done-check'></i>");
            $self.find(".fa").remove();
            $self.html(response.button_message);
            $self.attr("disabled", "disabled");
          }
        },
        error: function(error) {
          $self.find(".fa").remove();
        }
      });

    });

  },
  function () {

  }
);
