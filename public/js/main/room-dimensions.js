requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    jqueryScrollbar: "plugin/jquery.scrollbar.min",
    dimensionHelper: "web/helpers/room-dimension"
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryScrollbar: ['jquery'],
    dimensionHelper: ['dimensionHelper']
  }
});

requirejs(
  ["jquery", "bootstrap", "common", "jqueryScrollbar", "dimensionHelper"],
  function ($) {
    
  },
  function () {

  }
);
