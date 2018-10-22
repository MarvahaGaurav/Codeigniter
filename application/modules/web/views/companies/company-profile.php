<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/companies') ?>">Company</a></li>
            <li class="active">Company Profile</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Company Profile</h1>
        </div>

        <!-- Technician Profile Detail -->
        <div class="technician-profile-wrapper">
            <div class="row">
                <div class="col-sm-3 col-xs-12">
                    <div class="profile-thumb">
                        <div class="thumb-like-dislike">
                            <!-- Thumb Wrapper -->
                            <div class="thumb-view-wrapper thumb-view-contain thumb-view-p5 img-viewbdr-radius">
                                <?php if (!empty($company->company_image)) {?>
                                <img class="thumb-absolute" src="<?php echo $company->company_image ?>" alt="Company Logo" />
                                <?php } else { ?>
                                <div class="thumb-absolute image-blank"></div>
                                <?php } ?>
                            </div>
                            <!-- //Thumb Wrapper -->
                            <?php if (isset($userInfo, $userInfo['user_id']) && (int)$userInfo['company_id'] !== (int)$company->company_id) { ?>
                                <div class="favorite-wrapper">
                                    <span data-favorite='<?php echo $company->favorite_data ?>' class="fa fa-heart faa-like clickable heart-position2 <?php echo (int)$company->is_favorite === 1?"faa-like":"faa-dislike" ?>" aria-hidden="true"></span>
                                </div>
                            <?php } ?>
                        </div>
                        <h3 class="profile-username"><?php echo $company->company_name ?></h3>
                    </div>
                </div>
                <div class="col-sm-9 col-xs-12">
                    <!-- User detail Block wise -->
                    <div class="user-detail-block">
                        <div class="row">
                            <div class="col-xs-12">
                                <h3 class="userblock-caption">Basic Details</h3>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Email ID</label>
                                    <div class="input-holder">
                                        <span class="p-label-value"><?php echo $company->email ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="p-label">Mobile Number</label>
                                    <div class="input-holder">
                                        <span class="p-label-value">+<?php echo $company->prm_user_countrycode ?><?php echo $company->phone ?></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">    
                                    <label class="p-label">Alternate Number</label>
                                    <div class="input-holder">
                                        <span class="p-label-value">+<?php echo $company->alt_user_countrycode ?><?php echo $company->alt_userphone ?></span>
                                    </div>
                                </div>
                            </div>
                            <!-- button wrapper -->
                            <div class="col-xs-12">
                                <div class="button-wrapper">
                                    <!-- <a href="javascript:void(0)" class="custom-btn btn-margin btn-width save">Edit Profile</a> -->
                                </div>
                            </div>
                            <!-- button wrapper end -->
                        </div>
                    </div>
                    <!-- User detail Block wise end -->
                </div>
            </div>
        </div>
        <!-- Technician Profile Detail End -->

        <!-- technician list search -->
        <div class="project-list-wrapper technician-list clearfix">
            <h2 class="project-listtxt">Inspiration</h2>
            <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <form id="search-form" method="GET" action="">
                    <input type="text" name="search" class="search-box" value="<?php echo html_escape($search) ?>" id="search-box" placeholder="Search Inspirations" data-redirect="<?php echo base_url(uri_string()) ?>">
                    <input type="submit" value="Search" class="search-btn" />
                </form>
            </div>
        </div>
        <!-- //technician list search -->

        <!-- Technician list table -->
        <div class="table-responsive table-wrapper inspiration-table scrollbar-inner" id="scrollbar-inner">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>Inspiration</th>
                        <th>Inspiration Description</th>
                        <th>Location</th>
                        <th>Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($inspirations as $inspiration) { ?>
                    <tr>
                        <td class="td-thumb-carousel text-nowrap">
                            <div class="thumb-carousel">
                                <!-- td carousel -->
                                <div class="owl-carousel inspiration_carousel">
                                    <?php foreach ($inspiration['media'] as $media) { ?>
                                    <div class="item">
                                        <div class="thumb-view-wrapper thumb-view-fullp img-viewbdr-radius4">
                                            <!-- <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo $media['media'] ?>')"></div> -->
                                            <?php if ((int)$media['media_type'] === CONTENT_TYPE_IMAGE) { ?>
                                            <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo !empty($media['media'])?$media['media']:base_url('public/images/logo.png') ?>')"></div>
                                            <?php } elseif ((int)$media['media_type'] === CONTENT_TYPE_VIDEO) { ?>
                                            <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo !empty($media['video_thumbnail'])?$media['video_thumbnail']:base_url('public/images/logo.png') ?>')"></div>
                                            <?php } ?>
                                        </div>
                                    </div>
                                    <?php } ?>
                                    <?php if (empty($inspiration['media'])) { ?>
                                    <div class="item">
                                        <div class="thumb-view-wrapper thumb-view-fullp img-viewbdr-radius4">
                                            <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo base_url('public/images/logo.png') ?>')"></div>
                                        </div>
                                    </div>
                                    <?php } ?>
                                </div>
                                <!-- td carousel end -->
                            </div>
                        </td>
                        <td class="inspiration-description">
                            <h3 class="op-semibold"><?php echo $inspiration['title'] ?></h3>
                            <p><?php echo $inspiration['description'] ?></p>
                        </td>
                        <td><?php echo $inspiration['city_name'] ?>, <?php echo $inspiration['country_name'] ?></td>
                        <td>
                            <ul class="inspiration-product">
                                <?php foreach ($inspiration['products'] as $key => $product) {?>
                                <?php if ((int)$key >= 2) { ?>
                                <span>And more...</span>
                                <?php break ?>
                                <?php } ?>
                                <li><?php echo $product['title'] ?></li>
                                <?php }?>
                            </ul>
                        </td>
                        <td  class="text-nowrap action-user">
                            <a href="<?php echo base_url('home/inspirations/' . encryptDecrypt($inspiration['id'])) ?>" class="tb-view-list" title="View">View</a>
                        </td>
                    </tr>
                    <?php } ?>
                    <?php if (empty($inspirations)) { ?>
                        <tr>
                            <td class="text-center" colspan="10">No results found</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>   	
        <div class="pagination-wrap">
            <?php echo $links ?>
        </div>
        <!-- //Technician list table -->

        <!-- no record found -->
        <!-- <div class="no-record text-center">
            <img src="../../images/no-found-note.png" alt="Note Paper">
            <p>You have no project.</p>
            <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
        </div> -->
        <!-- no record found end -->

    </div>
</div>
