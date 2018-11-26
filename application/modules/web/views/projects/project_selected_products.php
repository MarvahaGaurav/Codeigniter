<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId) ?>">Details</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels') ?>">Levels</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels/' . $level .'/rooms') ?>">Rooms</a></li>
            <li class="active">Selected Products</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Accessory Products</h1>
        </div>

        <!-- Title Section and Search -->
        <div class="section-title clearfix">
            <h3 class="pull-left">Bathroom Products</h3>
            <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <input type="text" class="search-box" id="search-box" placeholder="Search Project">
                <input type="submit" value="Search" class="search-btn" />
            </div>
        </div>
        <!-- Title Section and Search end -->
    
        <!-- thumb wrapper section -->
        <div class="thumb-wrapper">
            <div class="row">
                <?php foreach($products as $product) { ?>
                <div class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-for-thumb">
                    <div class="thumb-box">
                        <div class="thumb-view-wrapper thumb-view-contain thumb-view-contain-pd thumb-view-fullp img-viewbdr-radius4 redirectable" data-redirect-to="<?php echo base_url("/home/projects/{$projectId}/levels/{$level}/rooms/{$roomId}/project-rooms/{$projectRoomId}/accessory-products/" . encryptDecrypt($product['product_id'])) ?>">
                            <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo isset($product['images'][0])?$product['images'][0]:'' ?>')"></div>
                            <?php if ($product['is_selected']) {?>
                                <span id="delete-thumb" class="selected-accessory-product"><i class="fa fa-check"></i></span>
                            <?php }?>
                        </div>
                        <div class="thumb-caption clearfix">
                            <h3><?php echo $product['title'] ?></h3>
                        </div>
                    </div>
                </div>
                <?php } ?>
            </div>
        </div>
        <!-- thumb wrapper section end -->

        <!-- no record found -->
        <!-- <div class="no-record text-center">
            <img src="../../images/no-found-note.png" alt="Note Paper">
            <p>You have no project.</p>
            <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
        </div> -->
        <!-- no record found end -->

    </div>
</div>