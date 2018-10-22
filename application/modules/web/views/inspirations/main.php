<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="active">Inspirations</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Inspirations</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are
                energy efficient and environmental friendly, in combination with a creation of the ambiance that you need,
                always keeping in mind that luminaires have a great impact on the environment.
            </p>
        </div>

        <!-- technician list search -->
        <div class="project-list-wrapper technician-list clearfix">
            <h2 class="project-listtxt">Inspiration</h2>
            <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <form id="search-form" method="GET" action="">
                    <input type="text" name="search" class="search-box" value="<?php echo $search ?>" data-redirect="<?php echo base_url("home/inspirations") ?>" id="search-box" placeholder="Search Inspirations">
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
                    <?php
                    foreach ($inspirations as $key => $inspiration) :
                        ?>
                        <tr>
                            <td class="td-thumb-carousel text-nowrap">
                                <div class="thumb-carousel">
                                    <!-- td carousel -->
                                    <div class="owl-carousel inspiration_carousel">
                                        <?php if (is_array($inspiration['media'])) { ?>
                                            <?php foreach ($inspiration['media'] as $media) : ?>
                                                <?php if (CONTENT_TYPE_IMAGE === (int) $media['media_type']) { ?>
                                                    <div class="item">
                                                        <div class="thumb-view-wrapper thumb-view-fullp img-viewbdr-radius4">
                                                            <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo $media['media'] ?>')"></div>
                                                        </div>
                                                    </div>
                                                    <?php
                                                }
                                                else {
                                                    ?>
                                                    <div class="item">
                                                        <div class="thumb-view-wrapper thumb-view-fullp img-viewbdr-radius4">
                                                            <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo $media['video_thumbnail'] ?>')"></div>
                                                        </div>
                                                    </div>
                                                <?php } ?>
                                            <?php endforeach ?>
                                            <?php
                                        }
                                        else {
                                            ?>
                                            <div class="item">
                                                <div class="thumb-view-wrapper thumb-view-fullp img-viewbdr-radius4">
                                                    <div class="thumb-view thumb-viewfullheight-1" style="background:url('<?php echo $inspiration['media'] ?>')"></div>
                                                </div>
                                            </div>
                                        <?php } ?>
                                    </div>

                                    <!-- td carousel end -->
                                    <!--<span class="fa fa-heart faa-like heart-position1" aria-hidden="true"></span>-->
                                </div>
                            </td>
                            <td class="inspiration-description">
                                <h3 class="op-semibold"><?php echo ucwords($inspiration['title']) ?></h3>
                                <h5><?php echo $inspiration['company_name'] ?></h5>
                                <p><?php echo $inspiration['description'] ?>
                                </p>
                            </td>
                            <td><?php echo $inspiration['city_name'] ?>, <?php echo $inspiration['country_name'] ?></td>
                            <td>
                                <ul class="inspiration-product">
                                    <?php foreach ($inspiration['products'] as $key => $product) : ?>
                                        <li><?php echo $product['product_title'] ?></li>
                                        <?php
                                        if ((int) $key > 3) {
                                            break;
                                        }
                                        ?>
                                    <?php endforeach ?>
                                </ul>
                            </td>
                            <td class="text-nowrap action-user">
                                <a href="<?php echo base_url("home/inspirations/" . $inspiration['inspiration_id']) ?>" class="tb-view-list" title="View">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <?php if ($inspiration['edit_inspiration']) { ?>
                                    <a href="<?php echo base_url("home/inspirations/{$inspiration['inspiration_id']}/edit") ?>" class="tb-view-list" title="Edit">
                                        <i class="fa fa-pencil" aria-hidden="true"></i>
                                    </a>
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endforeach ?>
                    <?php if (empty($inspirations)) { ?>
                        <tr>
                            <td colspan="5">No result found</td>
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