requirejs.config({
	baseUrl: 'public/adminpanel/js',
	paths: { //path to files
		jquery: "jquery",
		bootstrap: "bootstrap.min",
		momentjs:"https://cdnjs.cloudflare.com/ajax/libs/moment.js/2.17.1/moment.js",
		jqueryValidate: "jqvalidate/dist/jquery.validate",
		jqueryAdditional: "additionalmethods",
		ddatepicker: "bootstrap-datepicker",
		bootstrapselect:"bootstrap-select",
		select:"select",
		custom: "custom",
		globalmsg: "globalMsg",
		adminjs: "adminjs",
		customdatepicker: "custom-datepicker",
		user: "user",
		validation: "validationn",
		hightchartexport: "exporting",
		highchart: "highcharts",
		customdashboard: "custom-dashboard"
		
	},
	shim: { //dependencies
		"bootstrap": ['jquery'],
		"jqueryValidate": ['bootstrap'],
		"jqueryAdditional": ['jqueryValidate'],
		"ddatepicker": ['jqueryAdditional','jquery'],
		"bootstrapselect": ['ddatepicker'],
		"select": ['bootstrapselect'],
		"custom": ['select'],
		"globalmsg": ['custom'],
		"adminjs": ["globalmsg"],
		"customdatepicker": ['adminjs'],
		"user": ["customdatepicker"],
		"validation": ["user"],
                "hightchartexport": ["highchart"],
                "customdashboard": ["highchart"]
        
	}
});

requirejs(["jquery",
	"bootstrap",
	"jqueryValidate",
	"jqueryAdditional",
	"ddatepicker",
	"bootstrapselect",
	"select",
	"custom",
	"globalmsg",
	"adminjs",
	"customdatepicker",
	"user",
	"validation",
	"hightchartexport",
	"highchart",
	"customdashboard"
    
    
    
], function ($) {
	
});

