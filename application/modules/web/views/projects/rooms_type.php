<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId) ?>">Details</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels') ?>">Levels</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels/' . $level . '/rooms') ?>">Rooms</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels/' . $level . '/rooms/applications') ?>">Applications</a></li>
            <li class="active">Room Type</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Room Type</h1>
            <p class="prj-description">SG Lighting has vast experience of and expertise in a wide range of different types of projects, such as schools, hospitals, offices, industry, retail and outdoor lighting. Under
                each type of project in the overview below, there are references to the various areas, as well as product recommendations.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3><?php echo $application['title'] ?></h3>
        </div>
        <!-- Caption before section -->

        <!-- thumb wrapper section -->
        <div class="thumb-wrapper">
            <?php foreach ($roomChunks as $rooms) { ?>
                <div class="row">
                    <?php foreach ($rooms as $room) { ?>
                        <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-for-thumb redirectable" data-redirect-to="<?php echo base_url(uri_string() . "/{$room['encrypted_room_id']}/dimensions") ?>">
                            <div class="thumb-box">
                                <div class="thumb-view-wrapper thumb-view-fullp img-viewbdr-radius4">
                                    <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo ! empty($room['image']) ? $room['image'] : base_url('public/images/placeholder/no-found-ico-2.svg') ?>')"></div>
                                </div>
                                <div class="thumb-caption">
                                    <h3><?php echo $room['title'] ?></h3>
                                    <p><?php ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <!-- thumb wrapper section end -->

        <?php
        if ( ! count($roomChunks)) {
            ?>
            <!-- no record found -->
            <div class="no-record text-center">
                <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg"); ?>" alt="Note Paper">
                <p>You have no Room Type in this Application.</p>
                <!--<p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>-->
            </div>
            <!-- no record found end -->
            <?php
        }
        ?>
        <!-- no record found end -->

    </div>
</div>