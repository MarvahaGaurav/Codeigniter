requirejs.config({
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
      jquery: "jquery.min",
      bootstrap: "bootstrap.min",
      common: "web/common",
      jqueryScrollbar: "plugin/jquery.scrollbar.min",
      searchAssessoriesProduct: "web/helpers/select_product_search_assessories"
    },
    shim: {
      //dependencies
      bootstrap: ['jquery'],
      common: ['bootstrap'],
      jqueryScrollbar: ['jquery'],
      searchAssessoriesProduct: ['helper']
    }
  });
  
  requirejs(
    ["jquery", "bootstrap", "common", "jqueryScrollbar", 'searchAssessoriesProduct' ],
    function($) {
  
    },
    function() {
      
    }
  );
  