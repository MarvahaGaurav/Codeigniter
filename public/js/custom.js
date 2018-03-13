// sidebar in out
$('.toggleBtn').click(function(e){
    e.stopPropagation();
    if($(this).hasClass('active')) {
        $(this).removeClass('active');
        $('body').removeClass('section-caught');
        // $('.right-panel').removeClass('fluid-rightpanel');
        // $('.right-panel').removeClass('fluid-rightpanel');
    }
    else {
        $(this).addClass('active');
        $('body').addClass('section-caught');
        // $('.section-caught').addClass('open-sidebar')
        // $('.right-panel').addClass('fluid-rightpanel');
    }
})

// sidebar
$('.closeSidebar768').click(function(){
    $('.toggleBtn').removeClass('active');
    $('body').removeClass('section-caught');
})

$('.search786').click(function(e){
    $('.search-wrapper').addClass('show');
    e.stopPropagation();
})
$(document).on("click", function(e) {  
    if (!($(e.target).is(".search-wrapper") === false)) {
        $(".search-wrapper").addClass('show');
    }
    else {
        $(".search-wrapper").removeClass('show');
    }
});

/* dropdown */
//hide and show dropdowns
$('.drp').on('click', function(e){
    e.stopPropagation();
    if($(this).hasClass('active')) {
        $('.fncy-drp').removeClass('fncy-drp-opened');
        $('.drp').removeClass('active');
    }
    else {
        $('.fncy-drp').removeClass('fncy-drp-opened');
        $('.drp').removeClass('active');
        $(this).addClass('active');
        $(this).find('.fncy-drp').addClass('fncy-drp-opened');
    }
})
$(document).on("click", function(e) {  
    if (!($(e.target).is(".fncy-drp") === true)) {
        $(".fncy-drp").removeClass('fncy-drp-opened');
        $('.drp').removeClass('active');
    }
});


//Action Tool tip js Start
$(".user-td").click(function (e) {
    e.stopPropagation();
    $(".user-call-wrap").hide();
    $(this).find(".user-call-wrap").show();
});
$("body").click(function () {
    $(".user-call-wrap").hide();
});

//Action Tool tip js Close  


//Filter Show or hide JS
$("#filter-side-wrapper").click(function (e) {
    e.stopPropagation();
    $(".filter-wrap").addClass("active");
});
// $(document).click(function (e) {
//     if (!$(e.target).is('.filter-wrap, .filter-wrap *')) {
//         $(".filter-wrap").removeClass("active");
//         $(".search-wrapper").removeClass("show");
//     }
// });
$(".flt_cl").click(function (e) {
    $(".filter-wrap").removeClass("active");
});
//Filter Show or hide JS Close


//Select Picker Js Start
$('.selectpicker').selectpicker();

//Select Picker Js Close

$(".search-box").keyup(function () {
    var char_length = $(this).val().length;
    if (char_length > 0) {
        $(".close-ico").addClass("show-close-ico");
    } else {
        $(".close-ico").removeClass("show-close-ico");
    }
});

$(".close-ico").click(function () {
    $(".close-ico").removeClass("show-close-ico");
    $(".search-box").val('');

});

$(".selectpicker").selectpicker({});

$("form#create-room-template").validate({
    rules : {
        lighting: {
            required: true,
        },
        category: {
            required: true,
        },
        room_type: {
            required: true,
        },
        room_length: {
            required: true,
            number: true,
            maxlength: 10
        },
        room_length_unit: {
            required: true,
        },
        room_breath: {
            required: true,
            number: true,
            maxlength: 10
        },
        room_breath_unit: {
            required: true,
        },
        room_height: {
            required: true,
            number: true,
            maxlength: 10
        },
        room_height_unit: {
            required: true,
        },
        workplane_height: {
            required: true,
            number: true,
            maxlength: 10
        },
        workplane_height_unit: {
            required: true
        },
        room_shape: {
            required: true,
        },
        lux_value: {
            required: true,
            number: true,
            maxlength: 10
        }
    }
});