requirejs.config({
	baseUrl: 'public/adminpanel/js',
	paths: { //path to files
		jquery: "jquery",
		bootstrap: "bootstrap.min",
		jqueryValidate: "jqvalidate/dist/jquery.validate",
		custom: "custom",
		globalmsg: "globalMsg",
		adminjs: "loginjs",
		
	},
	shim: { //dependencies
		"bootstrap": ['jquery'],
		"jqueryValidate": ['bootstrap'],
		"custom": ['jqueryValidate'],
		"globalMsg": ["custom"],
		"loginjs": ["globalMsg"]
        
	}
});

requirejs(["jquery",
	"bootstrap",
	"jqueryValidate",
	"custom",
	"globalMsg",
	"loginjs"
    
    
    
], function ($) {
	
});

