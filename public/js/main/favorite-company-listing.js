requirejs.config({
  baseUrl: "public/js",
  waitSeconds: 60,
  paths: {
    jquery: "jquery.min",
    bootstrap: "bootstrap.min",
    common: "web/common",
    owl: 'owl.carousel.min',
    jqueryScrollbar: "plugin/jquery.scrollbar.min"
  },
  shim: {
	//dependencies
  bootstrap: ['jquery'],
  owl: ['bootstrap'],
	common: ['bootstrap'],
	jqueryScrollbar: ['jquery']
  }
});

requirejs(
  ["jquery", "bootstrap", "owl", "common", "jqueryScrollbar"],
  function($) {
      /* on click filter button filter section show */
      $('#filter-btn').click(function(e){
          e.stopPropagation();
          $('#filter-section').addClass('filtersection-Open');
      });

      $('#close-filter').click(function(e){
          e.stopPropagation();
          $('#filter-section').removeClass('filtersection-Open');
      });

      $(".heart-position1").on("click", function () {
        var self = this,
            $self = $(self),
            $wrapper = $self.parent(),
            favoriteData = JSON.parse($self.attr('data-favorite'));
        
        $.ajax({
          url: window.location.protocol + '//' + window.location.hostname + '/xhttp/companies/favorite',
          method: 'POST',
          data: favoriteData,
          beforeSend: function () {
            $self.removeClass('fa-heart').addClass('fa-circle-o-notch fa-spin');
          },
          success: function (response) {
            $self.addClass('fa-heart').removeClass('fa-circle-o-notch fa-spin');
            if (response.success) {
              window.location.reload();
            }
          },
          error: function (response) {

          }
        });
      });

      $('.inspiration_carousel').owlCarousel({
          items:1,
          loop:true,
          margin:30,
          autoplay:false,
          dots: true
      });

  },
  function() {

  }
);
