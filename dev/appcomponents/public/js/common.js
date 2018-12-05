function changepassword(oldpassword, newpassword) {
    $.ajax({
        type: "post",
        url: "/ajax/changepassword",
        dataType: 'json',
        data: {password: newpassword, oldpassword: oldpassword},
        success: function (respdata) {
            if (respdata.code == 200) {
                customalert(respdata.msg);
                $('#oldpassword').val('');
                $('#newpassword').val('');
                $('#cnfpassword').val('');
            } else {
                customalert(respdata.msg);
            }
        }
    });
}

function resetpassword(token, password) {
    $.ajax({
        type: "post",
        url: baseUrl + "ajax/reset",
        dataType: 'json',
        data: {token: token, 'password': password},
        success: function (respdata) {
            if (respdata.code == 200) {
                $('.password').val('');
            }
            customalert(respdata.msg);
        }
    });
}

function sendforgotemail(email) {
    $.ajax({
        type: "post",
        url: "/ajax/forgot",
        dataType: 'json',
        data: {email: email},
        success: function (respdata) {
            if (respdata.code == 200) {
                $('#email').val('');
            }
            customalert(respdata.msg);
        }
    });
}