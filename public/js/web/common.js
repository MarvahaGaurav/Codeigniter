// header up and down on scroll
var prev = 0;
var $window = $(window);
var header = $('header');

$window.on('scroll', function () {
    var scrollTop = $window.scrollTop();
    header.toggleClass('scrollhidden', scrollTop > prev);
    prev = scrollTop;
});
// header up and down on scroll end

// on hover menu open after and on click menu open before 992 
if ($(window).width() > 992) {
    $('ul.nav li.dropdown').hover(function () {
            $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeIn(300);
        },
        function () {
            $(this).find('.dropdown-menu').stop(true, true).delay(200).fadeOut(300);
        });
}
if ($(window).width() < 992) {
    $("ul.nav li.dropdown").off("hover");
}

$(document).ready(function () {

    /* on search click search expand */
    $('#search-default, #searchico-for-mob').click(function (e) {
        e.stopPropagation();
        $('.app-search').addClass('sb-searchopen');
    });

    $("#search-input-field").keyup(function () {
        if ($(this).val()) {
            $('#search-default').hide();
            $('#search-ico-close').show();
        } else {
            $('#search-default').show();
            $('#search-ico-close').hide();
        }
    });

    $("#search-ico-close").on("click", function () {
        $("#search-input-field").val('');
        $('#search-ico-close').hide();
        $('#search-default').show();
    })

    $("#searchico-for-mob").on("click", function () {
        $('.navbar-collapse').removeClass('in');
    })

    $(document).on('click', function (e) {
        $("#search-input-field").val('');
        if (!($(e.target).parents('.app-search').length)) {
            $('.app-search').removeClass('sb-searchopen');
            $('#search-default').show();
            $('#search-ico-close').hide();
        }
    })
    /* // on search click search expand */
    $("#user-logout").on("click", function () {
        $("#myModal-logout").modal("show");
    });

    
    var $flashCard = $("#flash-card");
    var flashMessage = $flashCard.attr("data-message").trim();
    
    if ( flashMessage.length > 1 ) {
        displayFlashCard(flashMessage);
    }

    function displayFlashCard(message) {
        
        $flashCard.addClass("alert alert-" + $flashCard.attr("data-type"));
        $flashCard.find(".strong-message").html(message);
        $flashCard.find(".strong-message").css({
            width: "100%",
            textAlign: "center",
            display: "block"
        });
        $flashCard.css({
            position: "fixed",
            top: "15%",
            left: "33.33%",
            zIndex: "9999",
            display: "block",
            width: "30%"
        });

        setTimeout(function(){
            $flashCard.fadeOut(300);
        }, 5000);
    }

    var $searchBox = $("#search-box");
    if ( $searchBox.length > 0 ) {
        var searchBoxVal = $searchBox.val().trim();
        if ( searchBoxVal.length > 0) {
            var $submitBtn = $searchBox.siblings("input[type='submit']");
            $submitBtn.prop("disabled", true);
            $searchBox.prop('readonly', true);
            $('.close-ico').show();
        } else {
            $('.close-ico').hide();
        }
    }

    $("#search-box").keyup(function () {
        if ($(this).val()) {
            $('.close-ico').show();
        } else {
            $('.close-ico').hide();
        }
    });

    $(".close-ico").on("click", function () {
        $("#search-box").val('');
        window.location = $("#search-box").attr("data-redirect");
        $('.close-ico').hide();
    });

    var $searchForm = $("form#search-form");

    if ( $searchForm.length > 0 ) {
        var $searchBoxInput = $searchForm.find("input[type='text']");
        $searchForm.on("submit", function() {
            var search = $searchBoxInput.val().trim();
            if ( search.length < 1 ) {
                $flashCard.attr("data-type", "danger");
                displayFlashCard("Search field cant be empty");
                return false;
            }
            return true;
        });
    }

    $(".back-button").on("click", function(){
        var $self = $(this),
            redirectTo = $self.attr("data-redirect");
        if ( redirectTo.length > 0 ) {
            window.location = redirectTo;
        }
    });

    var $confirmationModal = $("#myModal-confirmation");
    var $confirmationActionXhttp = $(".confirmation-action-xhttp");
    if ( $confirmationActionXhttp.length > 0 ) {
        $confirmationActionXhttp.on("click", function(){
            var $self = $(this),
                dataJson = $self.attr("data-json"),
                dataUrl = $self.attr("data-url"),
                dataAction = $self.attr("data-action"),
                dataRedirect = $self.attr("data-redirect"),
                dataTitle = $self.attr("data-title"),
                dataTarget = $self.attr("data-target"),
                dataMessage = $self.attr("data-message");
            
            var $modalTitle = $confirmationModal.find(".modal-header h4"),
                $modalMessage = $confirmationModal.find(".modal-body p"),
                $modalActionButton = $confirmationModal.find(".modal-footer button.yes");
            
            $modalTitle.html(dataTitle);
            $modalMessage.html(dataMessage);
            $modalActionButton.attr("data-json", dataJson);
            $modalActionButton.attr("data-url", dataUrl);
            $modalActionButton.attr("data-action", dataAction);
            $modalActionButton.attr("data-redirect", dataRedirect);
            $modalActionButton.attr("data-target", dataTarget);

            $confirmationModal.modal("show");

        });

        $("#confirmation-ok").on("click", function(){
            var $self = $(this),
                dataJson = $self.attr("data-json"),
                dataUrl = $self.attr("data-url"),
                dataAction = $self.attr("data-action"),
                dataRedirect = $self.attr("data-redirect"),
                dataTarget = $self.attr("data-target");

            $.ajax({
                url: dataUrl,
                method: "POST",
                data: JSON.parse(dataJson),
                dataType: 'json', 
                beforeSend: function () {

                },
                success: function(response) {
                    if (response.success) {
                        if ( dataAction == "remove" ) {
                            $confirmationModal.modal("hide");
                            $(dataTarget).remove();
                            $flashCard.addClass("alert alert-success");
                            // displayFlashCard(response.message);
                            window.location = dataRedirect;
                        }
                    }
                }
            });
        });
    }
    
    /* on type close icon show in search field end */
});