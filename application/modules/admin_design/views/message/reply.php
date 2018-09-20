<!-- Include Required Prerequisites -->
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<!-- Include Date Range Picker -->
<script type="text/javascript" src="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.js"></script>
<link rel="stylesheet" type="text/css" href="//cdn.jsdelivr.net/bootstrap.daterangepicker/2/daterangepicker.css" />

<div class="inner-right-panel">
    <!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/notification">Notifications</a></li>
        <li class="breadcrumb-item active">Edit Notification</li>
    </ol>
</div>
<!--breadcrumb wrap close-->
    <!--Filter Section -->
    <div class="white-wrapper">
        <div class="form-item-title clearfix">
            <h3 class="title">Add Notification</h3>
        </div>
        <!-- title and form upper action end-->
        <form method="post">
            <?php// echo form_open_multipart();?>
            <div class="form-ele-wrapper clearfix">
                <div class="row">

                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="admin-label">Title</label>
                            <div class="input-holder">
                                <input type="text" name="title" name="title" value="<?php //echo $detail['title'] ?>" id="title" placeholder="Notification title">
                                <span class="titleErr error"></span>
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-6 col-xs-6">
                        <div class="form-group">
                            <label class="admin-label">External Link</label>
                            <div class="input-holder">
                                <input type="text" name="link" value="<?php //echo $detail['link'] ?>" id="link" placeholder="Enter link">
                            </div>

                        </div>
                    </div>

                    <div class="col-sm-12">
                        <div class="form-group">
                            <label class="admin-label">Message</label>
                            <div class="input-holder">
                                <textarea class="custom-textarea" style="resize:none;" value="<?php //echo $detail['message'] ?>" maxlength="255" name="message" id="message-text"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="col-sm-12 col-xs-12">
                        <div class="form-ele-action-bottom-wrap btns-center clearfix">
                            <div class="button-wrap text-center">
                                <button type="submit" class="commn-btn save">Submit</button>
                                <button type="submit" class="commn-btn cancel">Cancel</button>

                            </div>
                        </div>
                    </div>

                </div>
            </div>
            <?php //echo form_close();?>
        </form>
    </div>
</div>

<script>
    $(document).ready(function () {
        $('#regDate').daterangepicker(
         {
            locale: {
            format: 'DD/MM/YYYY'
            },
            autoApply:true
        }
      );
        <?php if(empty($detail['date_range'])) {?>
        $('#regDate').val('');
        <?php } ?>
    })

</script>
