$(function() {
    //toggle social item action nav
    $('body').on('click', '.delete-modal-data-action', function(e) {
        e.stopPropagation();
        if ($(this).hasClass('active')) {
            $('.delete-modal-data-action').removeClass('active');
            $('.smdaction-nav').removeClass('in');
        } else {
            $('.delete-modal-data-action').removeClass('active');
            $('.smdaction-nav').removeClass('in');
            $(this).addClass('active');
            $(this).next().addClass('in');
        }
    });




    $(document).click(function() {
        $('.delete-modal-data-action').removeClass('active');
        $('.smdaction-nav').removeClass('in');
    });

});