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


    $(".check-marker").on("click", function () {
      var self = this,
        $self = $(self),
        $checkMark = $self.find(".check-mark"),
        title = $self.attr('data-title'),
        $selectedInstaller = $("#selected-installers"),
        $requestQuotationBtn = $("#request-quotation-btn"),
        companyId = $self.attr("data-company-id");

      if ($checkMark.hasClass("not-done-check")) {
        if ($selectedInstaller.children().length >= parseInt($selectedInstaller.attr('data-max-count'))) {
          displayErrorMessage($selectedInstaller.attr("data-maximum-message"));
          return 0;
        }
        $self.attr('title', title);
        $selectedInstaller.append($("<input>", {
          "id": "selected-installer-" + companyId,
          "type": "hidden",
          "value": companyId,
          "name": "selected_installers[]"
        }));
        $checkMark.toggleClass("done-check not-done-check");
      } else if ($checkMark.hasClass("done-check")) {
        $selectedInstaller.find("#selected-installer-" + companyId).remove();
        $self.attr('title', '');
        $checkMark.toggleClass("done-check not-done-check");
      }

      if ($("input[name='selected_installers[]']").length == 0) {
        $requestQuotationBtn.attr("disabled", "disabled");
        $requestQuotationBtn.attr("title", $requestQuotationBtn.attr("data-title"))
      } else {
        $requestQuotationBtn.removeAttr("disabled");
        $requestQuotationBtn.attr("title", "");
      }

    });

    $("#distance").on("change", function () {
      var self = this,
        $self = $(self)
      distance = $self.val(),
        url = $self.attr('data-redirect-to');

      var data = {
        search_radius: distance
      };

      var query = encodeQueryData(data);

      window.location.href = url + "?" + query;

    });
  },
  function () {

  }
);
