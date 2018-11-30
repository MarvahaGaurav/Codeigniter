requirejs.config( {
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",

    },
    shim: {
        //dependencies
        bootstrap: [ 'jquery' ],
        common: [ 'bootstrap' ],
        jqueryScrollbar: [ 'jquery' ]
    }
} );

requirejs(
        [ "jquery", "bootstrap", "common", "jqueryScrollbar" ],
        function ( $ ) {


            $( document ).on( "click", ".image-gallery", function () {
                let src = $( this ).attr( "data-src" );
                $( "#gellary-main-image" ).attr( "src", src );
            } );

            $(".select-project-accessory").on('click', function(){
                
                var self = this,
                    $self = $(self),
                    selected = $self.attr("data-selected");
                var $somethingWentWrong = $("#something-went-wrong");
                    message = $somethingWentWrong.attr("data-message");
                try {
                    var accessoryData = JSON.parse($self.attr("data-accessory"));
                } catch (e) {
                    displayErrorMessage(message);
                    return 0;
                }

                $.ajax({
                    url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/add/accessory-products",
                    data: accessoryData,
                    method: "POST",
                    dataType: "json",
                    beforeSend: function() {
                        var html = $self.html();
                        $self.html(html+"<i class='fa fa-circle-o-notch fa-spin'></i>");
                    },
                    success: function (response) {
                        $self.find('.fa').remove();
                        if (response.success) {
                            $self.html(selected);
                            $self.removeAttr("data-selected");
                            $self.removeAttr("data-accessory");
                            $self.attr('disabled', "disabled");
                            $self.removeClass('outline-btn');
                            $self.addClass('inverse-outline-btn');
                        } else {
                            displayErrorMessage(response.error);
                        }
                    },
                    error: function (error) {
                        $self.find('.fa').remove();
                        displayErrorMessage(message);
                    }
                })
                
            });

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

        }
);
