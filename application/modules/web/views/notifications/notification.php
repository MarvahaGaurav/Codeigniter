<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li class="active">Notifications</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Notifications</h1>
            <p class="prj-description"></p>
        </div>
    
        <?php foreach ($notifications as $notification) { ?>
            <div class="well well-lg">
                <a href="<?php echo !empty($notification['redirection'])?$notification['redirection']:'javascript:void(0)' ?>">
                    <div>
                        
                        <span><img src="<?php echo empty($notification['sender']['image'])?base_url('public/images/user-placeholder.png'):$notification['sender']['image'] ?>" alt="<?php $notification['id'] ?>" height="200px" width="200"></span>
                        <span><?php echo $notification['message'] ?></span>
                        
                    </div>
                    <div class="clearfix col-md-12">
                        <div class="pull-right col-md-3"><?php echo convert_date_time_format("Y-m-d H:i:s", $notification['created_at'], 'g:i A, M d Y') ?></div>
                    </div>
                </a>
            </div>
        <?php }?>

        <?php if (empty($notifications)) {?>
            <div class="no-record text-center">
                <img src="<?php echo base_url("public/images/svg/notification.svg"); ?>" alt="Note Paper">
                <p>You have no notifications</p>
            </div>
        <?php }?>

         <div class="pagination-wrap">
            <?php echo $links ?>
        </div>
    </div>
</div>