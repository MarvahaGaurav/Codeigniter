<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="javascript:void(0)">Home</a>
            </li>
            <li>
                <a href="<?php echo base_url("home/inspirations") ?>">Inspirations</a>
            </li>
            <li class="active">Inspiration Details</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Inspiration Details</h1>
        </div>

        <div class="section-title clearfix">
            <h3><?php echo ucwords($inspiration['title']) ?></h3>
        </div>

        <!-- thumb view -->
        <?php if (!empty($inspiration['media'])) { ?>
        <div class="thumbview-table">
            <div class="thumbview-tablecell thumbview-tablecell-one">
                <div class="thumb-view-evenly">
                    <!-- thumbnail -->
                    <?php foreach ($inspiration['media'] as $key => $media) :?>
                    <?php if (CONTENT_TYPE_IMAGE === (int)$media['media_type']) { ?>
                    <div id="thumb-tab<?php echo $key ?>" class="thumb-view-wrapper thumb-view-fullp active">
                        <div class="thumb-view thumb-viewfullheight-2" style="background-image:url('<?php echo $media['media'] ?>')"></div>
                    </div>
                    <?php } else if (CONTENT_TYPE_VIDEO === (int)$media['media_type']) { ?>
                    <div id="thumb-tab<?php echo $key ?>" class="thumb-view-wrapper thumb-view-fullp">
                        <div class="thumb-view thumb-viewfullheight-2">
                            <video poster="<?php echo $media['video_thumbnail'] ?>" autoplay loop>
                                <source src="<?php echo $media['media'] ?>" type="video/mp4"></source>
                            </video>
                        </div>
                    </div>
                    <?php }?>
                    <?php endforeach ?>
                    <!-- //thumbnail -->
                </div>
            </div>
            <div class="thumbview-tablecell thumbview-tablecell-two">
                <!-- thumbnail list -->
                <div class="thumb-view-listing-wrapper thumb-tower-listing thumb-tab">
                    <ul>
                        <?php foreach ($inspiration['media'] as $key => $media) :?>
                        <li class="active">
                            <a href="#thumb-tab<?php echo $key ?>" data-toggle="tab">
                                <div class="thumb-view-wrapper thumb-view-fullp">
                                <?php if (CONTENT_TYPE_IMAGE === (int)$media['media_type']) { ?>
                                    <div class="thumb-view thumb-viewfullheight-1" style="background-image:url('<?php echo $media['media'] ?>')"></div>
                                <?php } else if (CONTENT_TYPE_VIDEO === (int)$media['media_type']) { ?>
                                    <div class="thumb-view thumb-viewfullheight-1" style="background-image:url('<?php echo $media['video_thumbnail'] ?>')"></div>
                                <?php }?>
                                </div>
                            </a>
                        </li>
                        <?php endforeach?>
                    </ul>
                </div>
                <!-- //thumbnail list -->
            </div>
        </div>
        <?php }?>

        <div class="section-title section-subtitute clearfix">
            <h3>Details</h3>
        </div>

        <div class="thumb-view-footer">

            <!-- thumb-footer-detail -->
            <div class="row thumb-footer-detail">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h3 class="thumb-footer-title">Basic Details</h3>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="rd-property">Company</p>
                            <p class="rd-value"><?php echo $inspiration['company_name'] ?></p>
                        </div>
                        <div class="col-xs-12">
                            <p class="rd-property">Project Location:</p>
                            <p class="rd-value"><?php echo $inspiration['city_name'] ?>, <?php echo $inspiration['country_name'] ?></p>
                        </div>
                        <div class="col-xs-12">
                            <p class="rd-property">Products:</p>
                            <p class="rd-value">
                                <?php foreach($inspiration['products'] as $product) :?>
                                <span class="bullet-list"><?php echo $product['product_title'] ?></span>
                                <br>
                                <?php endforeach?>
                            </p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h3 class="thumb-footer-title">Description</h3>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="rd-description">
                                <?php echo $inspiration['description'] ?>
                            </p>
                        </div>
                    </div>
                </div>
            </div>
            <!-- thumb-footer-detail -->
        </div>
        <!-- thumb view end -->

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <div class="button-wrapper">
                <a href="javascript:void(0)" class="custom-btn btn-width save" data-toggle="modal" data-target="#editPrice">
                    Add Price
                </a>
            </div>
        </div>
        <!-- Caption before section -->

    </div>
</div>