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
