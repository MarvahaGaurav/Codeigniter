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
        <div class="notificationDetails">
            <a href="<?php echo !empty($notification['redirection'])?$notification['redirection']:'javascript:void(0)' ?>">
                <div class="media">    
                    <div class="media-left">
                    <img src="<?php echo empty($notification['sender']['image'])?base_url('public/images/user-placeholder.png'):$notification['sender']['image'] ?>" alt="<?php $notification['id'] ?>" class="media-object">
                    </div>
                    <div class="media-body">
                    <h4 class="media-heading"><?php echo $notification['name'] ?> <small><i><?php echo convert_date_time_format("Y-m-d H:i:s", $notification['created_at'], 'g:i A, M d Y') ?></i></small></h4>
                    <p><?php echo $notification['message'] ?></p>
                    </div>
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