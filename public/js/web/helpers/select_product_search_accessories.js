(function ($) {
    var $productSearchText = $("#product-search-text-accessories"),
        $productSearchBtn = $("#product-search-button-assessories"),
        $productSearch = $("#product-search-accessories"),
        $productModal = $("#productModal");

    $productSearchBtn.on("click", function () {
        var productSearch = $productSearchText.val();
        
        

        productSearch = productSearch.trim();
        

        if (productSearch.length == 0) {
            displayErrorMessage("Search cannot be empty");
        } else { 
            $productSearch.val(productSearch);
            $productSearch.trigger("keyup");
            $productModal.modal('show');
        }
    });

    $productSearchText.on("keyup", function (event) {
        var self = this,
            $self = $(self),
            productSearch = $self.val();
        productSearch = productSearch.trim();

        if (productSearch.length == 0 && event.keyCode == 13) {
            displayErrorMessage("Search cannot be empty");
        } else if (productSearch.length > 0 && event.keyCode == 13) {
            $productSearch.val(productSearch);
            $productSearch.trigger("keyup");
            $productModal.modal('show');
        }
    });

    var checkArticle = function(product_id) {
        
    }

    window.checkArticle = checkArticle;
})($);