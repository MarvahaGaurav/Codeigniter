<!--main wrapper close-->
<script data-main="public/adminpanel/js/Admin/<?php echo isset($js)?$js:"";?>" src="public/adminpanel/js/require.js"></script>
</body>

</html>
<!--data  Wrap close-->
    <!--Delete  Modal Close-->
    <!-- Modal -->
    <!-- Modal -->
    <div id="myModal-trash" class="modal fade" role="dialog">
        <input type="hidden" id="uid" name="uid" value="">
        <input type="hidden" id="ustatus" name="ustatus" value="">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-alt-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title modal-heading">Delete </h4>
                </div>
                <div class="modal-body">
                    <p class="modal-para">Are you sure want to delete this user?</p>
                </div>
                
                <input type="hidden" id="new_status" name="new_status">
                <input type="hidden" id="new_id" name="new_id">
                <input type="hidden" id="new_url" name="new_url">
                <input type="hidden" id="new_msg" name="new_msg">
                <input type="hidden" id="for" name="for">
                <div class="modal-footer">
                    <div class="button-wrap">
                        <button type="button" class="commn-btn cancel" data-dismiss="modal">Cancel</button>
                        <button type="button" class="commn-btn save" onclick="changeStatusToDelete($('#for').val(),$('#new_status').val(),$('#new_id').val(),$('#new_url').val())">Delete</button>
                    </div>
                </div>

            </div>
        </div>
    </div>
    <!--delete Modal Close-->
    <!--Block  Modal Close-->
    <!-- Modal -->
    <!-- Modal -->
    <div id="myModal-block" class="modal fade" role="dialog">
        <input type="hidden" id="userid" name="userid" value="">
        <input type="hidden" id="udstatus" name="udstatus" value="">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-alt-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title modal-heading">BLOCK</h4>
                </div>
                <div class="modal-body" >
                    <p class="modal-para">Are you sure want to block this user?</p>
                </div>
                <input type="hidden" id="new_status" name="new_status">
                <input type="hidden" id="new_id" name="new_id">
                <input type="hidden" id="new_url" name="new_url">
                <input type="hidden" id="new_msg" name="new_msg">
                <input type="hidden" id="for" name="for">
                
                
                <div class="modal-footer">
                    <div class="button-wrap">
                        <button type="button" class="commn-btn cancel" data-dismiss="modal">Cancel</button>
                        <button type="button" id="action" class="commn-btn save" onclick="changeStatusToBlock($('#for').val(),$('#new_status').val(),$('#new_id').val(),$('#new_url').val())">Block</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    
     <div id="myModal-logout" class="modal fade" role="dialog">
        <input type="hidden" id="uid" name="uid" value="">
        <input type="hidden" id="ustatus" name="ustatus" value="">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-alt-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title modal-heading">Delete </h4>
                </div>
                <div class="modal-body">
                    <p class="modal-para">Are you sure want to logout from admin panel?</p>
                </div>
                
                <div class="modal-footer">
                    <div class="button-wrap">
                        <button type="button" class="commn-btn cancel" data-dismiss="modal">No</button>
                        <button type="button" onclick="window.location='<?php echo base_url()?>admin/Logout'" class="commn-btn save">Yes</button>
                    </div>
                </div>

            </div>
        </div>
    </div>