<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="javascript:void(0)">Projects</a></li>
            <li><a href="javascript:void(0)">Create New Project</a></li>
            <li class="active">Select Application</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Select Application</h1>
            <p class="prj-description">SG Lighting has vast experience of and expertise in a wide range of different types of projects, such as schools, hospitals, offices, industry, retail and outdoor lighting. Under
                each type of project in the overview below, there are references to the various areas, as well as product recommendations.</p>
        </div>

        <!-- tabs section -->
        <div class="tabs">
            <ul>
                <li  class="redirectable <?php echo $type === APPLICATION_RESIDENTIAL ? "active" : "" ?>" data-redirect-to="<?php echo base_url(uri_string() . '?type=' . APPLICATION_RESIDENTIAL) ?>">
                    <a href="javascript:void(0)" data-toggle="tab">Residential lighting</a>
                </li>
                <li class="redirectable <?php echo $type === APPLICATION_PROFESSIONAL ? "active" : "" ?>" data-redirect-to="<?php echo base_url(uri_string() . '?type=' . APPLICATION_PROFESSIONAL) ?>">
                    <a href="javascript:void(0)" data-toggle="tab">Professional lighting</a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
        <!-- tabs section end -->

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3>Residential Lighting</h3>
        </div>
        <!-- Caption before section -->

        <!-- thumb wrapper section -->
        <div class="thumb-wrapper">
            <?php foreach ($applicationChunks as $applications) { ?>
                <div class="row">
                    <?php foreach ($applications as $application) { ?>
                        <div class="col-lg-3 col-md-3 col-sm-4 col-xs-12 col-for-thumb redirectable" data-redirect-to="<?php echo base_url('home/projects/' . encryptDecrypt($application['application_id']) . '/rooms') ?>">
                            <div class="thumb-box">
                                <div class="thumb-view-wrapper thumb-view-fullp img-viewbdr-radius4">
                                    <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo ! empty($application['image']) ? $application['image'] : base_url('public/images/placeholder/no-found-ico-2.svg') ?>')"></div>
                                </div>
                                <div class="thumb-caption">
                                    <h3><?php echo $application['title'] ?></h3>
                                    <p><?php echo $application['subtitle'] ?></p>
                                </div>
                            </div>
                        </div>
                    <?php } ?>
                </div>
            <?php } ?>
        </div>
        <!-- thumb wrapper section end -->

        <!-- no record found -->
        <!-- <div class="no-record text-center">
            <img src="../images/no-found-note.png" alt="Note Paper">
            <p>You have no project.</p>
            <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
        </div> -->
        <!-- no record found end -->

    </div>
</div>