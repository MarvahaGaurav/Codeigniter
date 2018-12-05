requirejs.config({
	baseUrl: 'public/adminpanel/js',
	paths: { //path to files
		jquery: "jquery",
		bootstrap: "bootstrap.min",
		custom: "custom",
		adminjs: "adminjs",
		user: "user",
                map:"https://maps.googleapis.com/maps/api/js?key=AIzaSyAUdM25XniwRXioDGorgj4hBFztMC_NcRo&libraries=places",
		validation: "validationn",
                myeditmap:"myeditmap",
                valid: "validation",
                add_merchant_valid:"add_merchant_valid",
            
	},
	shim: { //dependencies
		"bootstrap": ['jquery'],
		"custom": ['bootstrap'],
		"adminjs": ['custom'],
		"user": ["adminjs"],
                "validation": ["user"],
                "myeditmap":["validation"],
                "map":["myeditmap"],
                "valid":["map"],
                "add_merchant_valid":["valid"],
               
	}
});

requirejs(["jquery",
	"bootstrap",
	"custom",
	"adminjs",
	"user",
        "validation",
        "myeditmap",
        "map",
        "add_merchant_valid",
        "valid",
 
], function ($) {
	
$("#update-location").on("click", function () {
		$("#maps-modal").modal('show');
	});
      $("#savelatlong").on("click", function () {
		$("#maps-modal").modal('hide');
	});
        //Mobile Menu Js close
/*profile upload js*/
$(document).ready(function() {
    $('.click-to-file').click(function() {
        $('#upload').click();
    });
    
    // $('#upload').on('change', function() {
    //     previewImage(this, $('.profilePic'));
    // })
});

/*profile upload js close*/
/*file browse*/
$(document).on('click', '.browse', function() {
    var file = $(this).parent().parent().parent().find('.file');
    file.trigger('click');
});
$(document).on('change', '.file', function() {
    $(this).parent().find('.form-control').val($(this).val().replace(/C:\\fakepath\\/i, ''));
});

});


