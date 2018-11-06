requirejs.config( {
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        selectPicker: "bootstrap-select.min",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
        jqueryValidator: 'jquery.validate.min',
        autocomplete: 'jquery.autocomplete.min',
        location: 'lib/location',
        helper: 'web/helpers/room_js'
    },
    shim: {
        //dependencies
        bootstrap: [ 'jquery' ],
        selectPicker: [ 'bootstrap' ],
        common: [ 'bootstrap' ],
        jqueryScrollbar: [ 'jquery' ],
        jqueryValidator: [ 'jquery' ],
        helper: [ 'jqueryValidator' ],
        autocomplete: [ 'jquery' ],
        location: [ 'autocomplete' ]
    }
} );

requirejs(
        [ "jquery", "bootstrap", "common", "jqueryScrollbar", "jqueryValidator", "helper", "autocomplete", "location", "selectPicker" ],
        function ( $ ) {
            var normalizer = function ( value ) {
                return $.trim( value );
            };

            var validationRules = {
                // ignore: ":hidden:not(.selectpicker)",
                ignore: [ ],
                room_refrence: {
                    required: true,
                    normalizer: normalizer
                }
            };

            $( "#add_room_form" ).validate( {
                rules: validationRules,
                submitHandler: function ( form ) {
                    $( form ).submit();
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
                let formData = $( "#add_room_form" );
                let form_data = $( formData ).serialize();
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
                let application_id = $( "#application_id" ).val();
                let room_id = $( "#room_id" ).val();
                let project_room_id = $( "#project_room_id" ).val();
                let url = window.location.protocol + "//" + window.location.hostname + "/home/projects/" + application_id + "/room-edit/" + room_id + "/select-porduct/" + project_room_id;
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
                let formData = $( "#add_room_form" );
                let form_data = $( formData ).serialize();
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
                let x = $( "#room_luminaries_x" ).val();
                let y = $( "#room_luminaries_y" ).val();
                let total = 0;
                if ( '' != x && '' != y ) {
                    total = x * y;
                    if ( total > 500 ) {
                        total = 0;
                        $( "#xy_total_error" ).html( " !>500" );
                    }
                }
                $( "#xy_total" ).val( total );
            };


        }
);