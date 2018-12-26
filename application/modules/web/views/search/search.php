<div class="inner-container">
    <div class="container">

    	<div class="project-list-wrapper clearfix">
            <h2 class="project-listtxt">Search Products</h2>
            <div class="search-wrapper search-wrapper-width-2 fawe-icon-position">
            </div>
        </div>

        <div class="thumb-wrapper">
            <div class="row" id="search_product_div">
                <div class="searchWrapper">
                    <div class="searchBar">
                        <div class="row">
                            <div class="col-md-12">
                                <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                                    <span class="fawe-icon fawe-icon-position-right close-ico">
                                        <i class="fa fa-times"></i>
                                    </span>
                                    <form id="search-form" method="GET" action="/home/search">
                                    <input type="text" name="search" class="search-box" value="<?php echo html_escape($search) ?>" data-redirect="<?php echo base_url("home/search") ?>" id="search-box"  placeholder="Search Projects">
                    <input type="submit" value="Search" class="search-btn" />
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- searchBar close -->
                <?php if (!empty($data)) { ?>
                    <div class="row">
                        <?php foreach ($data as $key => $product) { ?>
                        <div class="col-sm-6 col-md-4">
                            <a href="<?php echo strlen(trim((string)$product['uld']))>0?base_url('home/fast-calc/product/').encryptdecrypt($product['product_id'])."/article/".encryptdecrypt($product['ps_articlecode']):'javascript:void(0)'?>">
                                <div class="thumbnail">
                                    <img src="<?php echo $product['ps_image'] ?>" alt="CircLED_Single_prodpic">
                                    <div class="caption">
                                        <h3><?php echo $product['title'] ?></h3>
                                        <p><?php echo $product['ps_title']  ?></p>
                                    </div>
                                </div>
                            </a>
                        </div>
                        <?php } ?>
                    </div>
                    <!-- row close -->
                    

				<?php } else { ?>

					<div class="no-record text-center no-article-found-container">
                        <img src="<?php echo base_url('public/images/svg/sg_logo_placeholder.svg') ?>" alt="Note Paper">
                        <p><?php echo $this->lang->line('no_product_found') ?></p>
                        <p><?php echo $this->lang->line('search_products_prompt') ?></p>
                    </div>
				<?php } ?>
            </div>
        </div>

        <?php if(count($data)) { ?>
            <div class="pagination-wrap">
                <ul>
                    <?php echo $this->pagination->create_links(); ?>
                </ul>    
            </div>
        <?php } ?>
    </div>
</div>