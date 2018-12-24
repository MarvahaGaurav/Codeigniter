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

        var $projectId = $("#project-id"),
            $projectRoomId = $("#project-room-id")
        $searchProductDiv = $("#search_product_div");
        /**
         *  Get Products According Mounting Type
         */

        $("#product-search-accessories").on('keyup change', function () {
            var self = this,
                $self = $(self),
                searchData = JSON.parse($self.attr("data-search-data")),
                searchValue = $self.val();

            var application_id = $("#application_id").val();
            var room_id = $("#room_id").val();
            var projectRoomId = $projectRoomId.val();
            var projectId = $projectId.val();

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
                        var csrf = response.csrf;
                        html = response.data.reduce(function (previousValue, currentValue) {
                            var temp = '';
                            var redirect_url = window.location.protocol + "//" + window.location.hostname + "/home/application-detail/" + application_id + "/rooms/" + room_id + "/mounting/" + 1 + "/articles/" + currentValue.product_id + "/code/" + currentValue.articlecode;
                            temp += '<div class="col-sm-6 col-md-4 selected-products-div">';
                            var productData = {
                                project_room_id: projectRoomId,
                                product_id: currentValue.product_id,
                                article_code: currentValue.articlecode,
                                project_id: projectId,
                            }

                            productData['' + csrf.name + ''] = csrf.token;

                            var jsonProductData = JSON.stringify(productData);

                            if (currentValue.is_selected) {
                                temp += "<span class='select-product-action' data-is-selected='" + currentValue.is_selected + "' data-json='" + jsonProductData + "'><i class='fa fa-check-square-o text-red selected-product-check clickable'></i></span>";
                            } else {
                                temp += "<span class='select-product-action' data-is-selected='" + currentValue.is_selected + "' data-json='" + jsonProductData + "'><i class='fa fa-square-o selected-product-check clickable'></i></span>";
                            }

                            temp += "<a href='javascript:void(0)' onclick='checkArticle(\"" + currentValue.product_id + "\")' id='article_" + currentValue.product_id + "'>" +
                                "<div class='thumbnail'>" +

                                '<img src="' + currentValue.image + '" alt="' + currentValue.product_name + '">' +
                                '<div class="caption">' +

                                // '<h2>' + currentValue.product_name + '</h2>' +
                                '<p><div class="article-code-div">' + currentValue.articlecode + '</div></p>' +
                                '<p><div class="article-code-div">' + currentValue.product_name + '</div></p>' +
                                '<p>' + currentValue.title + '</p>' +
                                '</div>' +
                                '</div>' +
                                '</a>' +
                                '</div>';

                            return previousValue + temp;
                        }, html);
                        $searchProductDiv.html(html);
                    } else {
                        $searchProductDiv.empty();
                        $(".no-article-found-container").removeClass('concealable');
                    }
                },
                error: function (error) {

                }
            })
        });


        $searchProductDiv.on("click", ".select-product-action", function () {
            var self = this,
                $self = $(self),
                productData = $self.attr("data-json"),
                isSelected = $self.attr("data-is-selected"),
                $iconElement = $self.children("i");

            try {
                productData = JSON.parse(productData);
            } catch (e) {
                displayErrorMessage($("#something-went-wrong").attr('data-message'));
                return 0;
            }

            var url = window.location.protocol + "//" + window.location.hostname;

            if (isSelected == "true") {
                url += "/xhttp/projects/article/remove";
            } else if (isSelected == "false") {
                url += "/xhttp/projects/add/accessory-products";
            }

            console.log(url, isSelected, typeof isSelected);

            $.ajax({
                url: url,
                method: "POST",
                dataType: "json",
                data: productData,
                beforeSend: function () {
                    if (isSelected == "true") {
                        $iconElement.removeClass("fa-check-square-o text-red");
                    } else if (isSelected == "false") {
                        $iconElement.removeClass("fa-square-o");
                    }
                    $iconElement.addClass("fa-circle-o-notch fa-spin");
                },
                success: function (response) {
                    $iconElement.removeClass("fa-circle-o-notch fa-spin");
                    console.log(response);
                    if (response.success) {
                        if (isSelected == "true") {
                            $iconElement.addClass("fa-square-o");
                            $self.attr('data-is-selected', "false");
                        } else if (isSelected == "false") {
                            $iconElement.addClass("fa-check-square-o text-red");
                            $self.attr("data-is-selected", "true");
                        }
                    } else {
                        displayErrorMessage($("#something-went-wrong").attr('data-message'));
                        if (isSelected == "true") {
                            $iconElement.addClass("fa-check-square-o text-red");
                        } else if (isSelected == "false") {
                            $iconElement.addClass("fa-square-o");
                        }
                    }
                },
                error: function (error) {
                    $iconElement.removeClass("fa-circle-o-notch fa-spin");
                    displayErrorMessage($("#something-went-wrong").attr('data-message'));
                    if (isSelected == "false") {
                        $iconElement.addClass("fa-check-square-o text-red");
                    } else if (isSelected == "false") {
                        $iconElement.addClass("fa-square-o");
                    }
                }
            })
        });



    },
    function () {

    },


);