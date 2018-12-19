<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id) ?>">Details</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels') ?>">Levels</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms') ?>">Rooms</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms/applications') ?>">Applications</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms/applications/' . $application_id . '/rooms') ?>">Room Type</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms/applications/' . $application_id . '/rooms/' . $room_id . '/dimensions') ?>">Add Room</a></li>
            <li class="active">Products</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title"><?php echo $room['title']; ?>: Products</h1>
            <div class="search-wrapper search-wrapper-width-2 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <div class="searchBtn">
                    <input type="text" id="product-search-text" name="search" class="search-box" value="" placeholder="Search Companies">
                    <input type="button" id="product-search-button" value="Search" class="search-btn">
                </div>
            </div>
            <!-- <p>Lots of possibilities for bathroom lighting</p> -->
            <p class="prj-description">This room requires different forms of lighting. When we go to the bathroom at night, we want as little light as possible, whereas when we’re doing our makeup, etc., we want
                very good lighting. The tiles are key when it comes to bathroom lighting. For example, if dark floor tiles are used, the lighting must take this into account.</p>
        </div>

        <!-- project list search -->
        <div class="project-list-wrapper clearfix">
            <h2 class="project-listtxt">Recommended Products</h2>
            <div class="search-wrapper search-wrapper-width-2 fawe-icon-position clearfix">
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
                <input type="hidden" name="level" id="level" value="<?php echo $level; ?>">
                <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id; ?>">
                <input type="hidden" name="token" id ="token" value="<?php echo $csrfToken; ?>">
                <input type="hidden" name="token_nme" id ="token_nme" value="<?php echo $csrfName; ?>">
                <input type="hidden" name="project_room_id" id ="project_room_id" value="<?php echo isset($project_room_id)?$project_room_id:''; ?>">

                <input type="hidden" name="application_id" id="application_id" value="<?php echo $application_id; ?>">
                <!--<input type="submit" value="Search" class="search-btn" />-->
            </div>
        </div>
        <!-- //project list search -->

        <!-- thumb wrapper section -->
        <div class="thumb-wrapper">
            <div class="row" id="product_div">
                <!-- no record found -->
            
        <!-- no record found -->
            </div>
        </div>

        <div class="no-record text-center no-product-found-container">
            <img src="<?php echo base_url('public/images/svg/sg_logo_placeholder.svg') ?>" alt="Note Paper">
            <p><?php echo $this->lang->line('no_product_found') ?></p>
            <p><?php echo $this->lang->line('select_mounting_type') ?></p>
        </div>

        <hr>
    </div>
</div>

<div id="productModal" class="modal fade" role="dialog">    
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <!-- <form id="search-form" method="GET" action=""> -->
                    <input type="text" data-search-data='<?php echo $searchData ?>' name="search" class="search-product-box search-box" value="" id="product-search"  placeholder="Search Products...">
                <!-- </form> -->
                <!-- <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button> -->
            </div>
            <div class="modal-body">
                <div class="thumb-wrapper">
                    <div class="row" id="search_product_div">
                    <!-- no record found -->
                    <!-- no record found -->
                    </div> 
                            <div class="no-record text-center no-article-found-container">
            <img src="<?php echo base_url('public/images/svg/sg_logo_placeholder.svg') ?>" alt="Note Paper">
            <p><?php echo $this->lang->line('no_product_found') ?></p>
            <p><?php echo $this->lang->line('search_products_prompt') ?></p>
        </div>                   
                </div>
            </div>            
        </div>
    </div>
</div>


