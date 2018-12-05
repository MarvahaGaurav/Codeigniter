/*select picker js   */
$('.selectpicker').selectpicker();
$(function() {
    $('.trigger-side-menu').click(function() {
        if ($(this).hasClass('on')) {
            $(this).removeClass('on');
            $('body').removeClass('nav-xs');
        } else {
            $(this).addClass('on');
            $('body').addClass('nav-xs');
        }
    });
    $('.trigger-account-menu').click(function(e) {
        e.stopPropagation();
        $(this).prev().addClass('active');
    });
    $(document).click(function() {
        $('header .drop-menu').removeClass('active');
    })
});
$(".fillter-bttn").click(function() {
    $(".filter-wrapper").slideToggle();
    // $(".overlay").show();
});

$(".clear-bttn").click(function() {
    $(".filter-wrapper").slideUp();
    // $(".overlay").hide();
});
// checkbox
$(document).ready(function() {
    $(".check").change(function() {
        if (this.checked) {
            $(".select-comm").each(function() {
                this.checked = true;
            })
        } else {
            $(".select-comm").each(function() {
                this.checked = false;
            })
        }
    });

    $(".select-comm").click(function() {
        if ($(this).is(":checked")) {
            var isAllChecked = 0;
            $(".select-comm").each(function() {
                if (!this.checked)
                    isAllChecked = 1;
            })
            if (isAllChecked == 0) { $(".check").prop("checked", true); }
        } else {
            $(".check").prop("checked", false);
        }
    });
});
//close checkbox

/*tab jas */
$(function() {
    var isTouchDevice = false;
    var isClicked = false;
    var mouseXposition = 0;
    var innerXposition = 0;
    var newPosition = 0;
    var updatePosition = function updatePosition(e) {
        if (!isTouchDevice) {
            newPosition = innerXposition + (mouseXposition - e.pageX);
            $('.tab-view').scrollLeft(newPosition);
        }
    };
    $('.tabs-view-wrapper').on('mousedown', function(e) {
        e.preventDefault ? e.preventDefault() : e.returnValue = false
        isClicked = true;
        mouseXposition = e.pageX;
        innerXposition = $('.tab-view').scrollLeft();
    });
    $(document).on('mousemove', function(e) {
        isClicked && updatePosition(e);
    });
    $(document).on('mouseup', function() {
        isClicked = false;
    });
    $('.tabs-view-wrapper').css({
        'max-height': $('.tab-view li').outerHeight()
    });
});

/*table sorting js*/
$(function() {
    var asc = false;
    $('.sortable th.sorting').click(function() {

        if ($(this).hasClass('sorting_asc')) {
            $(this).addClass('sorting_desc');
            $(this).removeClass('sorting_asc');
        } else {
            $('.sortable th.sorting').each(function() {
                $(this).removeClass('sorting_asc');
                $(this).removeClass('sorting_desc');
                $(this).addClass('sorting');
            });
            $(this).addClass('sorting_asc');
            $(this).removeClass('sorting_desc');
        }
    });

});
/*table sorting js close*/