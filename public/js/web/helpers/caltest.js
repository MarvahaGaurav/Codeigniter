(function ( $ ) {
    $( "#quickCal" ).click( function () {
        $.ajax( {
            url: "http://smartguide-staging.applaurels.com/xhttp/QuickCalController/quickCal",
            data: { "csrf_token": $( "#token" ).val() },
            method: "POST",
            success: function ( res ) {
                let obj = JSON.parse( res );
                console.log( obj );
                $( "#frontView" ).html( obj.projectionFront );
                $( "#thumb-tab1" ).html( obj.projectionTop );
            }
        } );
    } );


    /**
     *
     */
    $( "#evaluate_btn" ).click( function () {
        let project_id = $( this ).attr( "data-id" );
        let data = {
            csrf_token: $( "#csrf_token" ).val(),
            project_id: project_id
        };

        $.ajax( {
            url: "http://smartguide-staging.applaurels.com/xhttp/QuickCalController/evaluate",
            method: "POST",
            data: data,
            beforeSend: function ( brfs ) {

            },
            success: function ( res ) {
                location.reload();
            },
            error: function () {

            }
        } );
    } );



    $( "#request_btn" ).click( function () {
        let project_id = $( this ).attr( "data-spid" );
        let data = {
            csrf_token: $( "#csrf_token" ).val(),
            project_id: project_id
        };

        $.ajax( {
            url: "http://smartguide-staging.applaurels.com/xhttp/QuickCalController/request_quote",
            method: "POST",
            data: data,
            beforeSend: function ( brfs ) {

            },
            success: function ( res ) {
                setCookie( "selectd_room", "0" );
                setCookie( "mounting", "0" );
                setCookie( "add_room_form_data", "0" );
                $( "#request-send" ).modal( "show" );

            },
            error: function () {

            }
        } );
    } );

    /**
     *
     * @param {type} name
     * @returns {undefined}
     */
    function eraseCookie( name ) {
        document.cookie = name + '=; Max-Age=-99999999;';
    }

    $( "#close_modal" ).click( function () {
        $( "#request-send" ).modal( "hide" );
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
})( $ );
