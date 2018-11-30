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
  common: ['bootstrap'],
  owl: ['common'],
	jqueryScrollbar: ['jquery']
  }
});

requirejs(
  ["jquery", "bootstrap", "common", "jqueryScrollbar", "owl"],
  function($) {
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
