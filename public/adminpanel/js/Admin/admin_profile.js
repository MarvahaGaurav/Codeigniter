requirejs.config({
	baseUrl: 'public/adminpanel/js',
	paths: { //path to files
		jquery: "jquery",
		bootstrap: "bootstrap.min",
		custom: "custom",
		adminjs: "adminjs",
		user: "user",
		validation: "validationn"
	},
	shim: { //dependencies
		"bootstrap": ['jquery'],
		"custom": ['bootstrap'],
		"adminjs": ['custom'],
		"user": ["adminjs"],
                "validation": ["user"],
	}
});

requirejs(["jquery",
	"bootstrap",
	"custom",
	"adminjs",
	"user",
        "validation"
], function ($) {
	

});


