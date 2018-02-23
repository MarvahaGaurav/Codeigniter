requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    jqueryValidator: "jquery.validate.min",
    viewBuilder: "lib/view-builder",
    location: "lib/location",
    jqueryScrollbar: "plugin/jquery.scrollbar.min"
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
  ["jquery", "bootstrap", "common", "jqueryScrollbar", "viewBuilder","jqueryValidator", "location"],
  function($) {
    fetchLocation("xhttp/cities");
  },
  function() {

  }
);
