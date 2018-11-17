(function ( $ ) {


    $( document ).on( "click", ".image-gallery", function () {
        var src = $( this ).attr( "data-src" );
        $( "#gellary-main-image" ).attr( "src", src );
    } );

    /**
     *
     * @param {type} articel_id
     * @param {type} product_id
     * @param {type} type
     * @returns {undefined}
     */
    var select_product = function ( articel_id, product_id, product_type ) {
        var data = {
            articel_id: articel_id,
            product_id: product_id,
            type: product_type,
            product_name: $( "#product_name" ).val()
        };
        eraseCookie();
        setCookie( "selectd_room", JSON.stringify( data ), 7 );
        openNewWindow();
    };

    /**
     *
     * @returns {undefined}
     */
    var openNewWindow = function () {
        var project_id = $( "#project_id" ).val();
        var level = $( "#level" ).val();
        var application_id = $( "#application_id" ).val();
        var room_id = $( "#room_id" ).val();
        var url = window.location.protocol + "//" + window.location.hostname + "/home/projects/"+project_id+"/levels/"+level+"/rooms/applications/"+application_id+"/rooms/"+room_id+"/dimensions";
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

    window.select_product = select_product;
})( $ );
