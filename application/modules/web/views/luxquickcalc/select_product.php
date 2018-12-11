<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('/home/fast-calc/lux') ?>">Lux Values</a></li>
            <li class="active">Select Product</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title"><?php echo $room['title']; ?> Products</h1>
            <div class="search-wrapper search-wrapper-width-2 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <form id="search-form" method="GET" action="">
                    <input type="text" data-search-data='<?php echo $searchData ?>' name="search" class="search-product-box search-box" value="" id="product-search"  placeholder="Search Products...">
                </form>
            </div>
            <p class="prj-description">This room requires different forms of lighting. When we go to the bathroom at night, we want as little light as possible, whereas when we’re doing our makeup, etc., we want
                very good lighting. The tiles are key when it comes to bathroom lighting. For example, if dark floor tiles are used, the lighting must take this into account.</p>
        </div>

        <!-- project list search -->
        <div class="project-list-wrapper clearfix">
            <h2 class="project-listtxt">Recommended Products</h2>
            <div class="button-wrapper-two button-wrapper-right">
                <!-- <button type="button" id="filter-btn" class="custom-btn btn-width save">
                    <i class="fa fa-filter fa-common"></i> Filter
                </button> -->
                <!-- filter section -->
                <div id="filter-section" class="filter-section section-arrow">
                    <!-- form wrapper -->
                    <div class="form-wrapper">

                        <!-- Caption before section -->
                        <!-- <div class="section-title clearfix">
                            <h3>Filter</h3>
                        </div> -->
                        <!-- Caption before section -->

                        <!-- <div class="row form-inline-wrapper">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="labelTxt">Find your Product</label>
                                    <div class="form-group-field">
                                        <select>
                                            <option>Select</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                        </select>
                                        <span class="customArrow"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="labelTxt">Find your Application</label>
                                    <div class="form-group-field">
                                        <select>
                                            <option>Select</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                        </select>
                                        <span class="customArrow"></span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="labelTxt">Find your Sector</label>
                                    <div class="form-group-field">
                                        <select>
                                            <option>Select</option>
                                            <option>2</option>
                                            <option>3</option>
                                            <option>4</option>
                                        </select>
                                        <span class="customArrow"></span>
                                    </div>
                                </div>
                            </div>
                        </div> -->

                        <!-- Caption before section -->
                        <!-- <div class="section-title clearfix">
                            <div class="button-wrapper text-right">
                                <input type="submit" value="Apply" class="custom-btn btn-width save">
                                <button type="button" id="close-filter" class="custom-btn btn-width cancel">Cancel</button>
                            </div>
                        </div> -->
                        <!-- Caption before section -->
                    </div>
                    <!-- //form wrapper -->
                </div>
            </div>
            <div class="search-wrapper search-wrapper-width-2 search-wrp-992 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <select  name="mounting_type" id="mounting_type">
                    <option value="">Select Mounting</option>
                    <option value="<?php echo MOUNTING_SUSPENDED ?>">SUSPENDED</option>
                    <option value="<?php echo MOUNTING_RECESSED ?>">RECESSED</option>
                    <option value="<?php echo MOUNTING_SURFACE ?>">SURFACE</option>
                    <option value="<?php echo MOUNTING_DOWNLIGHT ?>">DOWN LIGHT</option>
                    <option value="<?php echo MOUNTING_DOWNLIGHT_ISOSAFE ?>">DOWN LIGHT ISO SAFE</option>
                    <option value="<?php echo MOUNTING_PENDANT ?>">PENDANT</option>
                    <option value="<?php echo MOUNTING_TRACKS ?>">TRACKS</option>
                </select>
                <span class="customArrow"></span>
                <input type="hidden" name="room_id" id="room_id" value="<?php echo $room_id; ?>">
                <input type="hidden" name="token" id ="token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="token_nme" id ="token_nme" value="<?php echo $csrfName; ?>">

                <input type="hidden" name="application_id" id="application_id" value="<?php echo $application_id; ?>">
                <!--<input type="submit" value="Search" class="search-btn" />-->
            </div>
        </div>
        <!-- //project list search -->

        <!-- thumb wrapper section -->
        <div class="thumb-wrapper">
            <div class="row" id="product_div">

            </div>
        </div>

        <div class="no-record text-center no-product-found-container">
            <img src="<?php echo base_url('public/images/svg/sg_logo_placeholder.svg') ?>" alt="Note Paper">
            <p><?php echo $this->lang->line('no_product_found') ?></p>
            <p><?php echo $this->lang->line('select_mounting_type') ?></p>
        </div>

        <hr>
        <div class="project-list-wrapper clearfix">
            <h2 class="project-listtxt">Search Products</h2>
        </div>

        <div class="thumb-wrapper">
            <div class="row" id="search_product_div">
                <!-- no record found -->
                <!-- no record found -->
            </div>
        </div>
        <div class="no-record text-center no-article-found-container">
            <img src="<?php echo base_url('public/images/svg/sg_logo_placeholder.svg') ?>" alt="Note Paper">
            <p><?php echo $this->lang->line('no_product_found') ?></p>
            <p><?php echo $this->lang->line('search_products_prompt') ?></p>
        </div>

    </div>
</div>