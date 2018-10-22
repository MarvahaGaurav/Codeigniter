(function ( $ ) {
    var normalizer = function ( value ) {
        return $.trim( value );
    };

    var validationRules = {
        // ignore: ":hidden:not(.selectpicker)",
        ignore: [ ],
        project_number: {
            normalizer: normalizer
        },
        project_name: {
            required: true,
            normalizer: normalizer
        },
        levels: {
            required: true,
            normalizer: normalizer
        },
        address: {
            required: true,
            normalizer: normalizer,
            minlength: 3,
            maxlength: 100
        }
    }

    $( "#add_project" ).validate( {
        rules: validationRules,
        submitHandler: function ( form ) {
            $( form ).submit();
        }
    } );




})( $ );