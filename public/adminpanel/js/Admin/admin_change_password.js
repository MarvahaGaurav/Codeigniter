requirejs.config({
	baseUrl: 'public/adminpanel/js',
	paths: { //path to files
		jquery: "jquery",
		bootstrap: "bootstrap.min",
		custom: "custom",
		adminjs: "adminjs",
		user: "user",
		validation: "validation",
                change_password:"change_password",
	},
	shim: { //dependencies
		"bootstrap": ['jquery'],
		"custom": ['bootstrap'],
		"adminjs": ['custom'],
		"user": ["adminjs"],
                "validation": ["user"],
                "change_password":['validation']
	}
});

requirejs(["jquery",
	"bootstrap",
	"custom",
	"adminjs",
	"user",
        "validation",
        "change_password"
], function ($) {
	

});



