requirejs.config( {
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
//        helper: 'web/helpers/select_product_quick'

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

            /**
             *  Get Products According Mounting Type
             */
            $( "#mounting_type" ).change( function () {

                let data = {
                    mounting: $( this ).val(),
                    room_id: $( "#room_id" ).val(),
                    csrf_token: $( "#token" ).val()
                };
                setCookie( "quick_mounting", $( this ).val(), 7 );
                let application_id = $( "#application_id" ).val();
                let room_id = $( "#room_id" ).val();
                let url = window.location.protocol + "//" + window.location.hostname + "/home/projects/get-porduct";
                $.ajax( {
                    url: url,
                    data: data,
                    type: "post",
                    beforeSend: function () {

                    },
                    success: function ( res ) {
                        let obj = JSON.parse( res );

                        let html = "";
                        $( "#product_div" ).empty();
                        $( obj.data ).each( function ( i, v ) {
                            console.log( v );
                            let redirect_url = window.location.protocol + "//" + window.location.hostname + "/home/applications/" + application_id + "/rooms/" + room_id + "/mounting/" + v.type + "/articles/" + v.product_id;
                            html += '<div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-for-thumb redirectable" data-redirect-to="' + redirect_url + '">';
                            html += '<div class="thumb-box">';
                            html += '<div class="thumb-view-wrapper thumb-view-contain thumb-view-contain-pd thumb-view-fullp img-viewbdr-radius4">';
                            html += '<div class="thumb-view thumb-viewfullheight-1" style="background:url(\'' + v.images[0] + '\')"></div>';
                            html += '</div>';
                            html += '<div class="thumb-caption clearfix">';
                            html += '<h3 class="thumb-light-name">' + v.title + '</h3>';
                            html += '<a href="javascript:void(0)" class="thumb-light-moreinfo">More info</a>';
                            html += '</div>';
                            html += '</div>';
                            html += '</div>';
                        } );
                        $( "#product_div" ).append( html );
                    },
                    error: function ( err ) {

                    }
                } );
            } );

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
        },
        function () {

        }
);

