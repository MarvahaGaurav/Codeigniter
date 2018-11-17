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
  function($) {
    $(".check-marker").on("click", function() {
      var self = this,
        $self = $(self),
        $checkMark = $self.find(".check-mark"),
        title = $self.attr('data-title'),
        $selectedInstaller = $("#selected-installers"),
        $requestQuotationBtn = $("#request-quotation-btn"),
        companyId = $self.attr("data-company-id");
      
      $checkMark.toggleClass("done-check not-done-check");

      if ($checkMark.hasClass("done-check")) {
        $self.attr('title', title);
        $selectedInstaller.append($("<input>", {
          "id": "selected-installer-" + companyId,
          "type": "hidden",
          "value": companyId,
          "name": "selected_installers[]"
        }));
      } else if ($checkMark.hasClass("not-done-check")) {
        $selectedInstaller.find("#selected-installer-" + companyId).remove();
        $self.attr('title', '');
      }

      if ($("input[name='selected_installers[]']").length == 0) {
        $requestQuotationBtn.attr("disabled", "disabled");
        $requestQuotationBtn.attr("title", $requestQuotationBtn.attr("data-title"))
      } else {
        $requestQuotationBtn.removeAttr("disabled");
        $requestQuotationBtn.attr("title", "");
      }

    });
  },
  function() {

  }
);
