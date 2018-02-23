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
        $flashCard.addClass("alert alert-" + $flashCard.attr("data-type"));
        $flashCard.find(".strong-message").html(flashMessage);
        $flashCard.find(".strong-message").css({
            width: "100%",
            textAlign: "center"
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

    /* on type close icon show in search field */
    $("#search-box").keyup(function () {
        if ($(this).val()) {
            $('.close-ico').show();
        } else {
            $('.close-ico').hide();
        }
    });

    $(".close-ico").on("click", function () {
        $("#search-box").val('');
        $('.close-ico').hide();
    });
    
    /* on type close icon show in search field end */
})