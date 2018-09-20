<!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/cms"> Content</a></li>
        <li class="breadcrumb-item active">Edit Content</li>
    </ol>
</div>
<!--breadcrumb wrap close-->
<div class="inner-right-panel">
    <!--Filter Section -->
    <?php //echo form_open_multipart(base_url().'admin/cms/edit?id='.encryptDecrypt($page_id),array('id'=>'cms_add_form'));?>

    <!-- form -->
    <div class="section">
        <div class="row">
            <div class="col-sm-3 col-xs-12">
                <div class="form-group">
                    <label class="admin-label">Title</label>
                    <div class="commn-select-wrap">
                        <?php echo form_error('status', '<label class="alert-danger">', '</label>');?>
                        <select class="selectpicker" name="status">
                            <option value="">Terms & Conditions</option>
                            <option value="">Privacy</option>
                            <option value="">About Us</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="form-group">
                    <label class="admin-label">Platform</label>
                    <div class="commn-select-wrap">
                        <?php echo form_error('status', '<label class="alert-danger">', '</label>');?>
                        <select class="selectpicker" name="status">
                            <option value="">All</option>
                            <option value="">All</option>
                            <option value="">All</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="form-group">
                    <label class="admin-label">Language</label>
                    <div class="commn-select-wrap">
                        <?php echo form_error('status', '<label class="alert-danger">', '</label>');?>
                        <select class="selectpicker" name="status">
                            <option value="">English</option>
                            <option value="">Franch</option>
                            <option value="">Chinese</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-sm-3 col-xs-12">
                <div class="form-group">
                    <label class="admin-label">Status</label>
                    <div class="commn-select-wrap">
                        <?php echo form_error('status', '<label class="alert-danger">', '</label>');?>
                        <select class="selectpicker" name="status">
                            <option value="">Select</option>
                            <option <?php echo($pages['status'] == ACTIVE)?  "selected='selected'":""; ?> value="<?php echo ACTIVE;?>">Active</option>
                            <option <?php echo($pages['status'] == INACTIVE)?  "selected='selected'":""; ?> value="<?php echo INACTIVE;?>">Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="col-xs-12">
                <div class="form-group">
                    <label class="admin-label">Description</label>
                    <div class="input-holder">
                        <?php echo form_error('txteditor1', '<label class="alert-danger">', '</label>');?>
                        <textarea id="txteditor1" class="custom-textarea" name="txteditor1" placeholder="Enter the description"><?php echo isset($pages['content'])?$pages['content']:set_value('txteditor1');?></textarea>
                        <!-- <span class="error_wrap"></span> -->
                    </div>
                </div>
            </div>
        </div>
        <div class="button-wrap">
            <button type="button" class="commn-btn cancel" onclick="window.location.href='<?php echo base_url()?>admin/cms'">Cancel</button>
            <button type="submit" class="commn-btn save">Submit</button>
        </div>
    </div>
    <!--form element wrapper end-->

    <?php //echo form_close();?>
    <!--Filter Section Close-->
</div>
<!--Table listing-->

<script src="<?php echo base_url() ?>public/ckeditor/ckeditor.js"></script>

<script>
    $(document).ready(function(){

        CKEDITOR.replace('txteditor1');

    });
</script>
