$(document).ready(function() {

    var nowTemp = new Date();
    var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

    var checkin = $('#datepicker_1').datepicker({
        onRender: function(date) {
            return date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        if (ev.date.valueOf() < checkout.date.valueOf()) {
            var newDate = new Date(ev.date)
            newDate.setDate(newDate.getDate());
            checkout.setValue(newDate);
        }
        checkin.hide();
        $('#datepicker_2')[0].focus();
    }).data('datepicker');
    var checkout = $('#datepicker_2').datepicker({
        onRender: function(date) {
            return date.valueOf() < checkin.date.valueOf() || date.valueOf() > now.valueOf() ? 'disabled' : '';
        }
    }).on('changeDate', function(ev) {
        checkout.hide();
    }).data('datepicker');


    //on datepicker 2 focus
    $('#datepicker_2').focus(function() {
        if ($('#datepicker_1').val() == '') {
            checkout.hide();
        }
    });
    //prevent typing datepicker's input
    $('#datepicker_2, #datepicker_1').keydown(function(e) {
        e.preventDefault();
        return false;
    });

});
