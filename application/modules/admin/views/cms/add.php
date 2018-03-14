
<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/cms"> Content Management</a></li>
            <li class="breadcrumb-item active">Add Content</li>
        </ol>
    </div>
    <!--breadcrumb wrap close-->

    <!--Filter Section -->
    <?php echo form_open_multipart('',array('id'=>'cms_add_form'));?>
        <div class="section">

            <div class="row">
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="admin-label">Page Title</label>
                        <div class="input-holder">
                            <!--<input type="password" class="form-control material-control" value="johndoe@gmail.com">-->
                            <?php echo form_error('title','<label class="alert-danger">','</label>');?>
                            <input type="text" class="form-control material-control" maxlength="150" name="title" placeholder="Enter the title" value="<?php echo set_value('title');?>">

                            <!-- <span class="error_wrap"></span> -->
                        </div>
                    </div>
                </div>                    
                <div class="col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="admin-label">Description</label>
                        <div class="input-holder">
                            <?php echo form_error('page_desc','<label class="alert-danger">','</label>');?>
                            <textarea class="custom-textarea editor1" name="page_desc" placeholder="Enter the description" maxlength="5000" id="page_desc"><?php echo set_value('page_desc');?></textarea>
                            <!-- <span class="error_wrap"></span> -->
                        </div>
                    </div>
                </div>
                <div class="col-sm-4 col-xs-8">
                    <div class="form-group">
                        <label class="admin-label">Status</label>
                        <div class="commn-select-wrap">
                            <?php echo form_error('status','<label class="alert-danger">','</label>');?>
                            <select class="selectpicker" name="status">
                                    <option value="">Select</option>
                                    <option value="<?php echo ACTIVE;?>">Active</option>
                                    <option value="<?php echo INACTIVE;?>">Inactive</option>
                                </select>
                        </div>
                        <!-- <span class="error_wrap"></span> -->
                    </div>
                </div>
            </div>
            <div class="form-ele-action-bottom-wrap btns-center clearfix">
                <div class="button-wrap text-center">
                    <button type="button"  onclick="window.location.href='<?php echo base_url()?>admin/cms'"class="commn-btn cancel">Cancel</button>
                    <button type="submit" class="commn-btn save">Save</button>
                </div>
            </div>

        </div>
        <!--close form view   -->

    <?php echo form_close();?>
    <!--Filter Section Close-->
</div>

<script src="<?php echo base_url() ?>public/ckeditor/ckeditor.js"></script>

<script>
    $(document).ready(function(){

        CKEDITOR.replace('page_desc');

    });
</script>
