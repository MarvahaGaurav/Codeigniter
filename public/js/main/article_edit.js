requirejs.config( {
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
//        helper: 'web/helpers/article'

    },
    shim: {
        //dependencies
        bootstrap: [ 'jquery' ],
        common: [ 'bootstrap' ],
        jqueryScrollbar: [ 'jquery' ],
//        helper: [ 'jquery' ]
    }
} );

requirejs(
        [ "jquery", "bootstrap", "common", "jqueryScrollbar" ],
        function ( $ ) {
            $( document ).on( "click", ".image-gallery", function () {
                let src = $( this ).attr( "data-src" );
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
                let data = {
                    articel_id: articel_id,
                    product_id: product_id,
                    type: product_type,
                    product_name: $( "#product_name" ).val()
                };
                eraseCookie();
                setCookie( "quick_cal_selectd_room", JSON.stringify( data ), 7 );
                openNewWindow();
            };

            /**
             *
             * @returns {undefined}
             */
            var openNewWindow = function () {
                let application_id = $( "#application_id" ).val();
                let project_room_id = $( "#project_room_id" ).val();
                let url = window.location.protocol + "//" + window.location.hostname + "/home/projects/" + application_id + "/room-edit/" + project_room_id;
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
        },
        function () {

        }
);
