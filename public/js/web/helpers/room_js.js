(function ( $ ) {
    var normalizer = function ( value ) {
        return $.trim( value );
    };

    var validationRules = {
        // ignore: ":hidden:not(.selectpicker)",
        room_refrence: {
            required: true,
            normalizer: normalizer
        },
        length: {
            required: true,
            normalizer: normalizer,
            number: true
        },
        width: {
            required: true,
            normalizer: normalizer,
            number: true
        },
        height: {
            required: true,
            normalizer: normalizer,
            number: true
        },
        room_plane_height: {
            required: true,
            normalizer: normalizer,
            number: true
        },
        room_luminaries_x: {
            required: true,
            normalizer: normalizer,
            number: true
        },
        room_luminaries_y: {
            required: true,
            normalizer: normalizer,
            number: true
        },
        rho_wall:{
            number: true  
        },
        rho_ceiling:{
            number: true  
        },
        rho_floor:{
            number: true  
        },
        rho_floor:{
            number: true  
        },
        lux_values:{
            number: true  
        }
    };

    $("#advanced-option-div").on("click", function(){
        var self = this,
            $self = $(self);

        $self.toggleClass("dropup");
    });

    $( "#add_room_form" ).validate( {
        rules: validationRules,
        submitHandler: function ( form ) {
            $("#final-room-submission").attr("disabled", "disabled");
            form.submit();
        }
    } );


    $( ".is_number" ).keypress( function ( e ) {
        console.log( e.which );
        //if the letter is not digit then display error and don't type anything
        if ( e.which != 46 && e.which != 8 && e.which != 0 && (e.which < 48 || e.which > 57) ) {
            return false;
        }
    } );

    /**
     *
     */
    $( "#choose_product" ).click( function () {
        var formData = $( "#add_room_form" );
        var form_data = $( formData ).serialize();
        console.log( form_data );
        /*Creating cookie with all form element*/
        eraseCookie( "add_room_form_data" );
        setCookie( "add_room_form_data", form_data, 7 );
        openNewWindow();
    } );


    /**
     *
     * @param {type} name
     * @param {type} value
     * @param {type} days
     * @returns {undefined}
     */
    function setCookie( name, value, days ) {
        var expires = "";
        if ( days ) {
            var date = new Date();
            date.setTime( date.getTime() + (days * 24 * 60 * 60 * 1000) );
            expires = "; expires=" + date.toUTCString();
        }
        document.cookie = name + "=" + (value || "") + expires + "; path=/";
    }


    /**
     *
     * @returns {undefined}
     */
    var openNewWindow = function () {
        var project_id = $( "#project_id" ).val();
        var level = $( "#level" ).val();
        var application_id = $( "#application_id" ).val();
        var room_id = $( "#room_id" ).val();
        var url = window.location.protocol + "//" + window.location.hostname + "/home/projects/" + project_id + "/levels/" + level + "/rooms/applications/" + application_id + "/rooms/" + room_id + "/dimensions" + "/products";
        window.location = url;
    };


    /**
     *
     * @param {type} name
     * @returns {unresolved}
     */
    function getCookie( name ) {
        var nameEQ = name + "=";
        var ca = document.cookie.split( ';' );
        for ( var i = 0; i < ca.length; i++ ) {
            var c = ca[i];
            while ( c.charAt(0) == ' ' )
                c = c.substring( 1, c.length );
            if ( c.indexOf( nameEQ ) == 0 )
                return c.substring( nameEQ.length, c.length );
        }
        return null;
    }

    /**
     *
     * @param {type} name
     * @returns {undefined}
     */
    function eraseCookie( name ) {
        document.cookie = name + '=; Max-Age=-99999999;';
    }



    /**
     *
     */
    $( ":input" ).bind( "keyup change", function ( e ) {
        var formData = $( "#add_room_form" );
        var form_data = $( formData ).serialize();
        /*Creating cookie with all form element*/
        eraseCookie( "add_room_form_data" );
        setCookie( "add_room_form_data", form_data, 7 );
    } );


    /**
     *
     */
    $( "#room_luminaries_x" ).keyup( function () {
        calculate();
    } );


    /**
     *
     */
    $( "#room_luminaries_y" ).keyup( function () {
        calculate();
    } );


    /**
     *
     * @returns {undefined}
     */
    var calculate = function () {
        $( "#xy_total_error" ).html( "" );
        var x = $( "#room_luminaries_x" ).val();
        var y = $( "#room_luminaries_y" ).val();
        var total = 0;
        if ( '' != x && '' != y ) {
            total = x * y;
            if ( total > 500 ) {
                total = 0;
                $( "#xy_total_error" ).html( " !>500" );
            }
        }
        $( "#xy_total" ).val( total );
    };


})( $ );
