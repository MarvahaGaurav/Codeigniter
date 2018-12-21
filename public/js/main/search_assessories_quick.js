requirejs.config({
    baseUrl: "public/js",
    waitSeconds: 60,
    paths: {
        jquery: "jquery.min",
        bootstrap: "bootstrap.min",
        common: "web/common",
        jqueryScrollbar: "plugin/jquery.scrollbar.min",
        searchProduct: "web/helpers/select_product_search_accessories"
        //        helper: 'web/helpers/select_product_quick'

    },
    shim: {
        //dependencies
        bootstrap: ['jquery'],
        common: ['bootstrap'],
        jqueryScrollbar: ['jquery'],
        searchProduct: ['jquery']
        //        helper: [ 'jquery' ]
    }
});

requirejs(
    ["jquery", "bootstrap", "common", "jqueryScrollbar", "searchProduct"],
    function ($) {

        /**
         *  Get Products According Mounting Type
         */

        $("#product-search-accessories").on('keyup change', function () { 
            console.log($(this).attr("data-search-data"));
            var self = this,
                $self = $(self),
                searchData = JSON.parse($self.attr("data-search-data")),
                searchValue = $self.val();
                

                
            var application_id = $("#application_id").val();
            var room_id = $("#room_id").val();

            searchData.search = searchValue.trim();

            $.ajax({
                url: window.location.protocol + "//" + window.location.host + "/xhttp/projects/rooms/products/allarticles",
                method: "GET",
                data: searchData,
                dataType: 'json',
                success: function (response) {
                    var html = "";
                    if (response.code == 200 && response.data.length > 0) {
                        $(".no-article-found-container").addClass('concealable');
                        html = response.data.reduce(function (previousValue, currentValue) {
                           
                            var temp = '';
                            var redirect_url = window.location.protocol + "//" + window.location.hostname + "/home/application-detail/" + application_id + "/rooms/" + room_id + "/mounting/" + 1 + "/articles/" + currentValue.product_id + "/code/" + currentValue.articlecode;
                            temp += '<div class="col-sm-6 col-md-4">' +
                                "<a href='javascript:void(0)' onclick='checkArticle(\""+currentValue.product_id+"\")' id='article_"+ currentValue.product_id +"'>" +
                                "<div class='thumbnail'>" +
                                
                                '<img src="' + currentValue.image + '" alt="' + currentValue.product_name + '">' +
                                '<div class="caption">' +
                                
                                // '<h2>' + currentValue.product_name + '</h2>' +
                                '<p><div class="article-code-div">' + currentValue.articlecode + '</div></p>' +
                                '<p>' + currentValue.title + '</p>' +
                                '</div>' +
                                '</div>' +
                                '</a>' +
                                '</div>';

                            return previousValue + temp;
                        }, html);
                        $("#search_product_div").html(html);
                    } else {
                        $("#search_product_div").empty();
                        $(".no-article-found-container").removeClass('concealable');
                    }
                },
                error: function (error) {

                }
            })
        });

        

    
    },
    function () {

    },

    
);