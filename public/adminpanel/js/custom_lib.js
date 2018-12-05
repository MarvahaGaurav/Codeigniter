function customalert(alerttext) {
    $.alert({
        title: 'Alert!',
        content: alerttext,
    });
}
function customconfirmbox(btnname, fnname, alerttext) {
    $.confirm({
        title: 'Alert!',
        content: alerttext,
        buttons: {
            successbtn: {
                text: btnname,
                btnClass: 'btn-green',
                action: function () {
                    fnname();
                }
            },
            close: function () {
            }
        }
    });
}
