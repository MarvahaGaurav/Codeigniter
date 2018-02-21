//$(document).ready(function(){
/*
    // Date Picker
    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    // =============== Linked Datepicker =============== //
    var add_start = $('#dpd1').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        onRender: function(date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() < add_end.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate());
            add_end.setValue(newDate);
            add_start.hide();
        }
        add_start.hide();
        $('#dpd2')[0].focus();
    }).data('datepicker');

    var add_end = $('#dpd2').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        onRender: function(date) {
            return date.valueOf() < add_start.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        add_end.hide();
    }).data('datepicker');

    // =============== Linked Datepicker =============== //
    var add_start = $('#dpd3').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        onRender: function(date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() < add_end.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate());
            add_end.setValue(newDate);
            add_start.hide();
        }
        add_start.hide();
        $('#dpd4')[0].focus();
    }).data('datepicker');

    var add_end = $('#dpd4').datepicker({
        format: 'dd/mm/yyyy',
        todayHighlight:'TRUE',
        autoclose: true,
        onRender: function(date) {
            return date.valueOf() < add_start.date.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        add_end.hide();
    }).data('datepicker');
*/

$(function () {
   $('#dpd3,#dpd4').datetimepicker({
       viewMode: 'days',
       //format: 'DD-MM-YYYY',
       format: 'DD/MM/YYYY',
       useCurrent: true,
       maxDate: moment()
   });
   $('#dpd3').datetimepicker().on('dp.change', function (e) {
       var incrementDay = moment(new Date(e.date));
       incrementDay.add(1, 'days');
       $('#dpd4').data('DateTimePicker').minDate(incrementDay);
       $(this).data("DateTimePicker").hide();
   });

   $('#dpd4').datetimepicker().on('dp.change', function (e) {
       var decrementDay = moment(new Date(e.date));
       decrementDay.subtract(1, 'days');
       $('#dpd3').data('DateTimePicker').maxDate(decrementDay);
       $(this).data("DateTimePicker").hide();
   });
   
});
    $("#dashboard-form").submit(function(){
        if ( $("#dpd3").val().trim().length == 0 || $("#dpd4").val().trim().length == 0 ) {
            return false;
        }

        return true;
    });

//});