<?php
$userPermission = isset($permission[1])?$permission[1]:array();
$versionPermission = isset($permission[2])?$permission[2]:array();
$notiPermission = isset($permission[3])?$permission[3]:array();
?>
<body>
    <!-- Content -->
    <section class="content-wrapper clearfix">

        <!--breadcrumb wrap-->
            <div class="breadcrumb-wrap">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="<?php echo base_url() ?>admin/message">Message</a></li>
                <li class="breadcrumb-item active">View Message</li>
            </ol>
        </div>
        <!--breadcrumb wrap close-->	

        <div class="clear"></div>
        <div class="col-sm-12">
            <div class="adminRoles-wrapper">
                <div class="form-item-title clearfix">
                    <h3 class="title">View Message</h3>
                </div>
                <!-- title and form upper action end-->
                <div class="form-ele-wrapper clearfix">

                    <div class="row">
                        <div class="user-detail-panel">
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Title</label>
                                    <div class="input-holder">
                                        <span class="text-detail">It is a long establish fact that a reader will be distracted</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Sent On</label>
                                    <div class="input-holder">
                                        <span class="text-detail">12-12-2017</span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-lg-6 col-sm-6 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Sent By</label>
                                    <div class="input-holder">
                                        <span class="text-detail">robert_lewandowsi@gmail.com</span>
                                    </div>
                                </div>
                            </div>
                            <div class="clearfix"></div>
                            <div class="col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Message</label>
                                    <div class="input-holder">
                                        <span class="text-detail">Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. It has survived not only five centuries, but also the leap into electronic typesetting, remaining essentially unchanged. It was popularised in the 1960s with the release of Letraset sheets containing Lorem Ipsum passages, and more recently with desktop publishing software like Aldus PageMaker including versions of Lorem Ipsum.12-11-2018</span>
                                    </div>
                                </div>
                            </div>

                        </div>
                        <div class="col-sm-12 col-xs-12">
                            <div class="form-ele-action-bottom-wrap btns-center clearfix">
                                <div class="button-wrap text-center">
                                    <a href="<?php echo base_url();?>admin/message/edit" class="commn-btn save">Reply</a>
                                    <button type="submit" class="commn-btn cancel">Delete</button>

                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!--form ele wrapper end-->
    </section>
</body>