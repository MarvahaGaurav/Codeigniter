requirejs.config({
	baseUrl: 'public/adminpanel/js',
	paths: { //path to files
		jquery: "jquery",
		bootstrap: "bootstrap.min",
		jqueryValidate: "jqvalidate/dist/jquery.validate",
		jqueryAdditional: "additionalmethods",
		bootstrapselect:"bootstrap-select",
		custom: "custom",
		globalmsg: "globalMsg",
		ckeditor: "ckeditor/ckeditor",
		adminjs: "adminjs",
		
	},
	shim: { //dependencies
		"bootstrap": ['jquery'],
		"jqueryValidate": ['bootstrap'],
		"jqueryAdditional": ['jqueryValidate'],
		"bootstrapselect": ['jqueryAdditional'],
		"custom": ['bootstrapselect'],
		"globalmsg": ['custom'],
		"ckeditor": ["globalmsg"],
		"adminjs": ["ckeditor"],
        
	}
});

requirejs(["jquery",
	"bootstrap",
	"jqueryValidate",
	"jqueryAdditional",
	"bootstrapselect",
	"custom",
	"globalmsg",
	"ckeditor",
	"adminjs",
    
    
    
], function ($) {
	

});

