<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li class="active">Evaluations Result</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Result</h1>
            <p class="prj-description">SG Lighting has vast experience of and expertise in a wide range of different types of projects, such as schools, hospitals, offices, industry, retail and outdoor lighting. Under
                each type of project in the overview below, there are references to the various areas, as well as product recommendations.</p>
        </div>

        <div class="section-title section-subtitute clearfix">
            <h3>Details</h3>
        </div>

        <div class="thumb-view-footer">

            <!-- thumb-footer-detail -->
            <div class="row thumb-footer-detail">
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h3 class="thumb-footer-title">Technican Data</h3>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="rd-property">Article Code</p>
                            <p class="rd-value"><?php echo(json_decode($serachProductData)->articlecode) ?></p>
                        </div>
                        <div class="col-xs-12">
                            <p class="rd-property">Color Temprature</p>
                            <p class="rd-value"><?php echo($article_additional_detail['colour_temperature']) ?></p>
                        </div>
                        <div class="col-xs-12">
                            <p class="rd-property">Beam Angle</p>
                            <p class="rd-value"><?php echo($article_additional_detail['beam_angle']) ?></p>
                        </div>
                        <div class="col-xs-12">
                            <p class="rd-property">Colour Rendering</p>
                            <p class="rd-value"><?php echo($article_additional_detail['colour_rendering']) ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <h3 class="thumb-footer-title">Recommended Data</h3>
                    <div class="row">
                        <div class="col-xs-12">
                            <p class="rd-property">Lux</p>
                            <p class="rd-value"><?php
                                $temp = json_decode($serach_Product_Dialux_Response, true);
                                echo round($temp['illuminance'], 2);
                                ?>
                            </p>
                        </div>
                        <div class="col-xs-12">
                            <p class="rd-property">Number of Luminaries</p>
                            <p class="rd-value"><?php echo $temp['luminaireCountInX'] * $temp['luminaireCountInY']; ?></p>
                        </div>

                        <!--
                                                <div class="col-xs-12">
                                                    <p class="rd-property">Product Family:</p>
                                                    <p class="rd-value">Occuldas</p>
                                                </div>
                                                <div class="col-xs-12">
                                                    <p class="rd-property">Beam Angle:</p>
                                                    <p class="rd-value">1100</p>
                                                </div>
                                                <div class="col-xs-12">
                                                    <p class="rd-property">IP Class</p>
                                                    <p class="rd-value"><?php echo $specifications['ingress_protection_rating']; ?></p>
                                                </div>-->
                    </div>
                </div>
            </div>
            <!-- thumb-footer-detail -->
        </div>

        <div class="section-title clearfix">
            <div class="button-wrapper">
                <!--                <a name="quickCal" id="quickCal"  href="javascript:void(0)" class="custom-btn btn-width save" data-toggle="modal" data-target="#editPrice">
                                    Save
                                </a>-->
                <a name="quickCal" id="quickCal"  href="javascript:void(0)" class="custom-btn btn-width save" data-toggle="modal" data-target="#editPrice">
                    Email
                </a>
            </div>
        </div>

        <div class="thumbview-tablecell thumbview-tablecell-two">
            <!-- thumbnail list -->
            <div class="thumb-view-listing-wrapper thumb-tab">
                <ul>
                    <?php if($temp['projectionTop']){ ?>
                        <li class="active">
                            <a href="#thumb-tab1" data-toggle="tab">
                                <div class="thumb-view-list clearfix">
                                    <figure>
                                        <?php
                                        echo $temp['projectionTop'];
                                        ?>
                                    </figure>
                                    <span class="thumbnail-sideview">Top View</span>
                                </div>
                            </a>
                        </li>
                    <?php } ?>

                    <?php if($temp['projectionFront']){ ?>
                        <li>
                            <a href="#thumb-tab2" data-toggle="tab">
                                <div class="thumb-view-list clearfix">
                                    <figure>
                                        <?php
                                        echo $temp['projectionFront'];
                                        ?>
                                    </figure>
                                    <span class="thumbnail-sideview">Front View</span>
                                </div>
                            </a>
                        </li>
                    <?php } ?>
                    
                    <?php if($temp['projectionSide']){ ?>
                    <li>
                        <a href="#thumb-tab3" data-toggle="tab">
                            <div class="thumb-view-list clearfix">
                                <figure>
                                    <?php
                                    echo $temp['projectionSide'];
                                    ?>
                                </figure>
                                <span class="thumbnail-sideview">Side View</span>
                            </div>
                        </a>
                    </li>
                    <?php } ?>
                </ul>
            </div>
            <!-- //thumbnail list -->
        </div>
        <!-- thumb view -->
        <div class="thumbview-table">
            <div class="thumbview-tablecell thumbview-tablecell-one">
                <div class="thumb-view-evenly">
                    <!-- thumbnail -->
                    
                    <div id="thumb-tab1" class="thumb-view-wrapper thumb-view-fullp active">
                        <?php
                        echo $temp['projectionTop'];
                        ?>
                    </div>
                    

                    <div id="thumb-tab2" class="thumb-view-wrapper thumb-view-fullp">
                        <?php
                        echo $temp['projectionFront'];
                        ?>
                    </div>

                    <div id="thumb-tab3" class="thumb-view-wrapper thumb-view-fullp">
                        <?php
                        echo $temp['projectionSide'];
                        ?>
                    </div>
                    <!-- //thumbnail -->
                </div>
            </div>
        </div>

        <!-- thumb view end -->
        <!-- <input type="hidden" name="token" id ="token" value="<?php echo $csrfToken; ?>"> -->
        <!-- <input type="hidden" name="token_nme" id ="token_nme" value="<?php echo $csrfName; ?>"> -->
        <!-- Caption before section -->
        <!-- Caption before section -->

    </div>
</div>


<style>
    .thumb-view-list figure {
        width: 100%;
        height:auto;
        border-radius: 3px;
        padding: 2px;
        margin: 0 auto;
    }
    .thumb-view-list figure svg {
        width: 100%;
        height: 200px;
    }



    .thumb-view-wrapper svg{
        margin: 0 auto;
        width: 100%;
    }

    .thumbview-table {
        width: 100%;
        display: flex;
        flex-flow: column;
    }
    .thumbview-tablecell-one {
        width: 100%;
        padding: 0 15px 0 0;
        display: flex;
        flex-flow: column;
    }
    .thumbview-tablecell-two {
        width: 100%;
        margin: 0 0 0 0;
    }
    .thumb-view-listing-wrapper ul li {
        width: 32.7%;
        display: inline-block;
        margin: 18px 0;
    }
</style>