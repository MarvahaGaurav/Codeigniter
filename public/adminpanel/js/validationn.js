/*
 * change status of user to block and unblock
 */
/*change status user block and unblock*/
var reqid, reqobj;
 function changestatus(id, obj){
  reqid = id;
  reqobj = obj;
     var block_status = $(reqobj).attr('data-block-status');
 $('#userid').val(reqid);
 $('#udstatus').val(block_status);
 }
var domain = window.location.origin;
var reqid, reqobj;
function changeuserstatus() {
    reqid = $('#userid').val();;
    var block_status = $('#udstatus').val();
    var url = domain + '/ajax/changestatus';
    $.ajax({
        url: "req/block-user",
        method: "POST",
        data: {id: reqid, is_blocked: block_status,csrf_token: $("#csrf_token").val()},
        dataType: "json",
        success: function (resparr) {
            if (resparr.code == 200) {
                if (block_status == 1) {
                     $('#myModal-block').modal('hide');
                    var ind = parseInt($(reqobj).parent().index()) - 1;
                    $(reqobj).parent().parent().find('td:eq(' + ind + ')').text('Active');
                    $(reqobj).text('Block');
                    $(reqobj).attr('data-block-status', 2);
                } else {
                     $('#myModal-block').modal('hide');
                    var ind = parseInt($(reqobj).parent().index()) - 1;
                    $(reqobj).parent().parent().find('td:eq(' + ind + ')').text('Blocked');
                    $(reqobj).text('Unblock');
                    $(reqobj).attr('data-block-status', 1);
                }
                $("#csrf_token").val(resparr.csrf_token);
            } else {
                customalert(resparr.msg);
            }

        }
    });
}

/*Delete user sure popup*/
var requid, requobj;
function deleteuser(id,obj){
 requid = id;
   requobj = obj;
 var delete_status = $(requobj).attr('data-delete-status'); 
 $('#uid').val(requid);
 $('#ustatus').val(delete_status);
 

}
/*Delete User*/
var domain = window.location.origin;
var requid, requobj;
function deletemerchant() {
     requid = $('#uid').val();;
    var url = domain + '/ajax/changestatus';
    var delete_status = $('#ustatus').val();
    $.ajax({
        url: "req/delete-user",
        method: "POST",
        data: {id: requid, is_deleted: delete_status,csrf_token: $("#csrf_token").val()},
        dataType: "json",
        success: function (resparr) {
            console.log(resparr);
            if (resparr.code == 200) {
                if (delete_status == 3) {
            $('#myModal-trash').modal('hide');
                window.location.reload(true);
                } else {
//                    var ind = parseInt($(reqobj).parent().index()) - 1;
//                    $(reqobj).parent().parent().find('td:eq(' + ind + ')').text('Blocked');
//                    $(reqobj).text('Unblock');
//                    $(reqobj).attr('data-block-status', 1);
                }
                $("#csrf_token").val(resparr.csrf_token);
            } else {
                customalert(resparr.msg);
            }

        }
    });
}

/*Close model*/
function closemodel(){
  $('#myModal-trash').modal('hide');
$('#myModal-block').modal('hide');
}