<!--main wrapper close-->
</div>
<footer>
    &copy; ArchiveZ 2018
</footer>

<!-- JS Plugin -->
<script src="<?php echo base_url()?>public/js/plugin/bootstrap.min.js"></script>
<script src="<?php echo base_url()?>public/js/plugin/bootstrap-select.js"></script>
<script src="<?php echo base_url()?>public/js/plugin/jquery.validate.min.js"></script>
<script src="<?php echo base_url()?>public/js/global-msg.js"></script>
<script src="<?php echo base_url()?>public/js/custom.js"></script>
<script src="<?php echo base_url() ?>public/js/common.js"></script>
</body>
</html>

    <!-- Delete Modal -->
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

    <!--Block Modal -->
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
                
                <div class="button-wrap">
                    <button type="button" class="commn-btn cancel" data-dismiss="modal">Cancel</button>
                    <button type="button" class="commn-btn save" id="action" onclick="changeStatusToBlock($('#for').val(),$('#new_status').val(),$('#new_id').val(),$('#new_url').val())">Block</button>
                </div>
            </div>
        </div>
    </div>
    
    <!--Logout Modal -->
    <div id="myModal-logout" class="modal fade" role="dialog">
        <input type="hidden" id="uid" name="uid" value="">
        <input type="hidden" id="ustatus" name="ustatus" value="">
        <div class="modal-dialog modal-sm">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header modal-alt-header">
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                    <h4 class="modal-title modal-heading">Logout</h4>
                </div>
                <div class="modal-body">
                    <p class="modal-para">Are you sure want to logout from admin panel?</p>
                </div>
                
                <div class="button-wrap">
                    <button type="button" class="commn-btn cancel" data-dismiss="modal">No</button>
                    <button type="button" class="commn-btn save" onclick="window.location='<?php echo base_url()?>admin/Logout'" >Yes</button>
                </div>

            </div>
        </div>
    </div>
    
    <script>
    $(document).ready(function(){
        $('.alert-success').fadeOut(5000);
    });    
    </script>
