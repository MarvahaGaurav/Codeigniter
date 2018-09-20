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
    $(document).ready(function(){
      /* on click filter button filter section show */
      $('#filter-btn').click(function(e){
          e.stopPropagation();
          $('#filter-section').addClass('filtersection-Open');
      })

      $('#close-filter').click(function(e){
          e.stopPropagation();
          $('#filter-section').removeClass('filtersection-Open');
      })
    })
  },
  function() {

  }
);
