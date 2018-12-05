 //------- dropdown  menu  --- 
 /*profile js   */
 $(document).on('click', '.account-menu-wrap', function(event) {
     event.stopPropagation();
     if ($('.dropdown-menu-bar').hasClass('active')) {
         $(".dropdown-menu-bar").removeClass('open');
     } else {
         $(".dropdown-menu-bar").removeClass('open');
         $(this).parent().find('.dropdown-menu-bar').addClass('open');
     }
 });
 $('html').click(function() {
     $(".dropdown-menu-bar").removeClass('open');
 });
 /*left side js      */
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
 });
 //------- side filter  toggle --- 
 $(document).on('click', '.fillter-bttn', function(event) {
     event.stopPropagation();
     if ($(this).hasClass('open_filer_wrap')) {
         $(".filter-right_panel").removeClass('open_filer_wrap');
     } else {
         $(".filter-right_panel").removeClass('open_filer_wrap');
         $('.filter-right_panel').addClass('open_filer_wrap');
     }
 });
 $('.filter_close').click(function() {
     $(".filter-right_panel").removeClass('open_filer_wrap');
 });
 // select input table js
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