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
                <div>
                    <span><img src="<?php echo $notification['sender']['image'] ?>" alt="<?php $notification['id'] ?>" height="200px" width="200"></span>
                    <span><?php echo $notification['message'] ?></span>
                </div>
                <div class="clearfix col-md-12">
                    <div class="pull-right col-md-3"><?php echo convert_date_time_format("Y-m-d H:i:s", $notification['created_at'], 'g:i A, M d Y') ?></div>
                </div>
            </div>
        <?php }?>

        <?php if (empty($notifications)) {?>
            <div class="col-md-12 text-center">
                No notifications found
            </div>
        <?php }?>

         <div class="pagination-wrap">
            <?php echo $links ?>
        </div>
    </div>
</div>