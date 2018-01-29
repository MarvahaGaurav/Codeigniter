var emailreg = /^(([^<>()\[\]\\.,;:\s@"]+(\.[^<>()\[\]\\.,;:\s@"]+)*)|(".+"))@((\[[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}\.[0-9]{1,3}])|(([a-zA-Z\-0-9]+\.)+[a-zA-Z]{2,}))$/;
var defaultimg = '/public/images/default.png';
var admininfo = {}, token, obj;
var imgtypes = ["jpg", "png", "jpeg"];
/*
 * Ready Events to be executed here
 */
$(document).ready(function () {

    $('input').keypress(function (e) {
        var inp = $.trim($(this).val()).length;
        if (inp == 0 && e.which === 32) {
            return false;
        }
    });
    $('#message-text').keypress(function (e) {
        var inp = $.trim($(this).val()).length;
        if (inp == 0 && e.which === 32) {
            return false;
        }
    });
    $('.username,.password,.email,.phone').keypress(function (e) {
        if (e.which === 32) {
            return false;
        }
    });

    $('.loginbtn').click(function () {

        var username = $.trim($('#username').val());
        var password = $.trim($('#password').val());
        var flag = 0;
        if (username.length == 0) {
            $('.usernameErr').text('Please provide you username');
        } else {
            flag++;
            $('.usernameErr').text('');
        }
        if (password.length == 0) {
            $('.passwordErr').text('Please provide you password');
        } else {
            flag++;
            $('.passwordErr').text('');
        }
        if (flag == 2) {
            $('#loginform').submit();
        }
    });

//    $('.username').focus(function () {
//        if (e.which != 32) {
//            $('.usernameErr').text('');
//        }
//    });
//    $('.password').keypress(function (e) {
//        if (e.which != 32) {
//            $('.passwordErr').text('');
//        }
//    });

    /*
     * Send the forgot password link after validation
     */
    $('.forgotbtn').click(function () {
        var email = $.trim($('#email').val());
        var flag = 0;
        if (email.length == 0) {
            $('.usernameErr').text('Please enter your email');
        } else {
            if (!emailreg.test(email)) {
                $('.usernameErr').text('Please enter a valid email');
            } else {
                $('.usernameErr').text('');
                flag++;
            }
        }
        if (flag == 1) {
            sendforgotemail(email);
        }
    });

    $('.resetbtn').click(function () {
        var password = $.trim($('#password').val());
        var cnfpassword = $.trim($('#cnfpassword').val());
        var token = $.trim($('#token').val());
        var flag = 0;
        if (password.length == 0 || password.length < 8) {
            $('.passwordErr').text('Please enter new password of minimum 8 character');
        } else {
            $('.passwordErr').text('');
            flag++;
        }
        if (cnfpassword.length == 0 || (password != cnfpassword)) {
            $('.cnfpasswordErr').text('Please enter confirm password same as password');
        } else {
            $('.cnfpasswordErr').text('');
            flag++;
        }

        if (flag == 2) {
            resetpassword(token, password);
        }
    });


    $('.email,.username,.password').focus(function () {
        $('.emailErr').text('');
        $('.usernameErr').text('');
        $('.passwordErr').text('');
        $('.cnfpasswordErr').text('');
    });
    $('.userfilterbtn').click(function () {
        var status = $('#userstatus').find(":selected").val();
        var url = $(this).attr('url');
        window.location.href = url + '?status=' + status;
    });
    $('#adminimage').change(function () {
        var adminimg = $(this).val();
        var ext = adminimg.split('.').pop();
        if ($.inArray(ext, imgtypes) == -1) {
            customalert('Please select a valid file');
            return false;
        } else {
            return true;
        }

    });
    $('#updatebtn').click(function () {
        var adminname = $('.adminname').val();
        if (adminname.length == 0) {
            customalert('Name must be filled');
            return false;
        }
        $('#updateadminform').submit()
    });



    $('.appfilterbtn').click(function () {
        var dep = $('#filterdep').val();
        var country = $('#filtercountry').val();
        var doa = $('#doa').val();
        if (!dep && !country && !doa) {
            customalert('Please select atleast a filter to apply');
            return false;
        }
        var params = {}
        if (dep.length != 0) {
            params['dep'] = dep;
        }
        if (country.length != 0) {
            params['country'] = country;
        }
        if (doa.length != 0) {
            params['doa'] = doa;
        }

        var queryparams = $.param(params);
        window.location.href = '/appointments?' + queryparams;
    });

});



function sort(sortby, sorttype, url) {

    var filter = "";
    if (($('#filterparams').length) > 0) {
        var params = $('#filterparams').val();
        params = JSON.parse(params);
        if (params.length != 0)
            filter = '&' + params;
    }

    window.location.href = url + '?sortby=' + sortby + '&sorttype=' + sorttype + filter;
}

function validatepassword() {

    var pass1 = $("#oldpassword").val().trim();
    var pass2 = $("#newpassword").val().trim();
    var pass3 = $("#cnfpassword").val().trim();

    flag = 0;
    if (pass1.length == 0) {
        $('.oldpasswordErr').text('Please enter old Password');
    } else {
        $('.oldpasswordErr').text('');
        flag++;
    }
    if (pass2.length < 8) {
        $('.newpasswordErr').text('Please enter new Password of minimum 8 character');
    } else {
        $('.newpasswordErr').text('');
        flag++;
    }
    if (pass3.length < 8) {
        $('.cnfpasswordErr').text('Please enter Confirm Password same as new password');
    } else {
        $('.cnfpasswordErr').text('');
        flag++;
    }
    if (flag == 3) {
        if (pass2 !== pass3) {
            customalert('New and Confirm password must be same');
        } else {
            flag++;
        }
    } else {
        return false;
    }

    if (flag == 4) {
        changepassword(pass1, pass2);
    }


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

function customalert(alerttext, btntext = false, btncustomcls = false) {
    if (btntext == false || btntext == '') {
        btntext = 'OK';
    }
    if (btncustomcls == false || btncustomcls == '') {
        btncustomcls = 'btn-default';
    }
    $.alert({
        title: 'Alert!',
        content: alerttext,
        buttons: {
            successbtn: {
                text: btntext,
                btnClass: btncustomcls,
            }
        }
    });
}

function CheckforNum(e) {
    //console.log(String.fromCharCode(e.keyCode));
    // Allow: backspace, delete, tab, escape, enter and  +
    if ($.inArray(e.keyCode, [46, 8, 9, 27, 13]) !== -1 || (e.which === 187) || (e.keyCode == 65 && e.ctrlKey === true) || (e.keyCode == 86 && e.ctrlKey === true) || (e.keyCode == 67 && e.ctrlKey === true) || (e.keyCode == 88 && e.ctrlKey === true) || (e.keyCode >= 35 && e.keyCode <= 39)) {
        // let it happen, don't do anything
        return;
    }
    // Ensure that it is a number and stop the keypress
    if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
        e.preventDefault();
    }
}


