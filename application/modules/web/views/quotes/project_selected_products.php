<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId.'/'.$request_id) ?>">Project Details</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId .'/'.$request_id. '/levels') ?>">Levels</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId .'/'.$request_id. '/levels/' . $level .'/rooms') ?>">Rooms</a></li>
            <li class="active">Selected Products</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Selected Products</h1>
        </div>

        <!-- Title Section and Search -->
        <div class="section-title clearfix">
            <h3 class="pull-left"></h3>
            <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <form id="search-form" method="GET" action="">
                    <input type="text" name="search" class="search-box" value="<?php echo html_escape($search) ?>" data-redirect="<?php echo base_url(uri_string()) ?>" id="search-box" placeholder="Search Products">
                    <input type="submit" value="Search" class="search-btn" />
                </form>
            </div>
        </div>
        <!-- Title Section and Search end -->

        <!-- thumb wrapper section -->
        <div class="thumb-wrapper">
            <div class="row">
                <?php foreach($products as $key => $product) { ?>
                <div id="product-<?php echo $key ?>" class="col-lg-3 col-md-3 col-sm-6 col-xs-12 col-for-thumb" title="<?php echo strip_tags($product['title']) ?>">
                    <div class="thumb-box">
                        <div class="thumb-view-wrapper thumb-view-contain thumb-view-contain-pd thumb-view-fullp img-viewbdr-radius4" data-redirect-to="">
                            <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo isset($product['image'])?$product['image']:'' ?>')"></div>
                            <?php if ((int)$product['product_type'] === PROJECT_ROOM_ACCESSORY_PRODUCT && 
                                (
                                    (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                                    (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasAddedFinalPrice) ||
                                    (in_array((int)$userInfo['user_type'], [WHOLESALER, ELECTRICAL_PLANNER], true)) 
                                )
                            ) { ?>
                            <span id="delete-thumb" class="selected-accessory-product confirmation-action-xhttp" title="<?php echo $this->lang->line('remove_product') ?>" data-url="<?php echo base_url('xhttp/projects/article/remove') ?>" data-target="#product-<?php echo $key ?>" data-json='<?php echo $product['remove_data'] ?>' data-action="remove-element" data-redirect="<?php echo base_url(uri_string()) ?>" data-title="<?php echo $this->lang->line('remove_product_title') ?>" data-message="<?php echo sprintf($this->lang->line("remove_product_message"), $product['product_name']) ?>"><i class="fa fa-trash"></i></span>
                            <?php } ?>
                        </div>
                        <div class="thumb-caption clearfix">
                            <h3><?php echo $product['product_name'] ?></h3>
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