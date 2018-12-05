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

      $(".heart-position1, .heart-position2").on("click", function () {
        var self = this,
            $self = $(self),
            $wrapper = $self.parent(),
            favoriteData = JSON.parse($self.attr('data-favorite'));
        
        $.ajax({
          url: window.location.protocol + '//' + window.location.hostname + '/xhttp/companies/favorite',
          method: 'POST',
          data: favoriteData,
          dataType: 'json',
          beforeSend: function () {
            $self.removeClass('fa-heart').addClass('fa-circle-o-notch fa-spin');
          },
          success: function (response) {
            $self.addClass('fa-heart').removeClass('fa-circle-o-notch fa-spin');
            if (response.success) {
              favoriteData['is_favorite'] = response.status;
              $self.attr('data-favorite', JSON.stringify(favoriteData));
              if (response.status == 1) {
                $self.removeClass('faa-dislike');
                $self.addClass('faa-like');
              } else {
                $self.removeClass('faa-like');
                $self.addClass('faa-dislike');
              }
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
