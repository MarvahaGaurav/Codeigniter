<div class="container">
    <ul class="breadcrumb"> 
    <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id) ?>">Details</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels') ?>">Levels</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms') ?>">Rooms</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms/applications') ?>">Applications</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms/applications/' . $application_id . '/rooms') ?>">Room Type</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms/applications/' . $application_id . '/rooms/' . $room_id . '/dimensions') ?>">Add Room</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level . '/rooms/applications/' . $application_id . '/rooms/' . $room_id . '/dimensions/products') ?>">Products</a></li>
            <li class="active">Details</li>
    </ul>
    <div class="">
        <h1 class="heading-red"><?php echo $product_name = $product['title']; ?></h1>
    </div>
    
</div>



<?php
$first        = '';
if (count($images)) {
    $first = $images[0]['image'];
    ?>
    <!--Image Slider-->
    <div class="container">
        <div class="image-slider row">
            <div class="image-preview col-xs-12 col-md-9">
                <div class="image-preview-content">
                    <img id="gellary-main-image" src="<?php echo $first; ?>" alt="<?php echo $product['title']; ?>" title="<?php echo $product['title']; ?>" class="img-responsive center-block">
                </div>
            </div>

            <div class="pagination-content col-xs-12 col-md-3">
                <ul class="pagination">
                    <?php
                    for ($i = 0; $i <= 2; $i ++) {
                        if (! isset($images[$i])) {
                            continue;
                        }
                        ?>
                        <li class="col-xs-3 col-md-12">
                            <img class="image-gallery" data-src="<?php echo $images[$i]['image']; ?>" src="<?php echo $images[$i]['image']; ?>" alt="<?php echo $product['title']; ?>" title="<?php echo $product['title']; ?>" class="img-responsive">
                        </li>
                        <?php
                    }
                    ?>

                </ul>
            </div>
        </div>
    </div>
    <!--Image Slide Close-->

<?php } ?>

<!--Description-->
<div class="container">
    <div class="product-description">
        <div>
            <p><?php echo $product['body']; ?></p>
        </div>
        <div>
            <img  class ="warrenty-img" src="https://www.sg-as.com/sites/default/files/data/Ikoner/garanti/en/systemgaranti_5y.png" title="" class="article-data-icon">
        </div>
    </div>


</div>
<!--Description Box-->



<!--Table Wrapper-->

<div class="container">
    <div id="no-more-tables">
        <table class="articles table table-striped table-hover table-condensed">
            <thead>
                <tr>
                    <th class="image"></th>
                    <th class="light-technic" style="width:35%"><b>Article</b></th>
                    <th class="protection">Protection</th>
                    <th class="driver">Control gear</th>
                    <th class="connection">Mounting/Connection</th>
                    <th class="dimensions">Dimensions (mm)</th>
                    <th class="download"></th>
                </tr>
            </thead>

            <tbody>
                <tr>
                    <!--<td colspan="7" class="group">2700K</td>-->
                </tr>
                <?php
                foreach ($articles as $key => $article) :
                    ?>
                <tr>
                    <td colspan="10" class="group"><?php echo $key ?></td>
                </tr>
                <?php foreach($article as $specification) : ?>
                <tr class="">

                    <td class="image"><a>
                        <img data-redirect-to="<?php echo base_url("home/projects/{$project_id}/levels/{$level}/rooms/applications/{$application_id}/rooms/{$room_id}/dimensions/products/" . encryptDecrypt($product_id) . "/mounting/{$mounting}/articles/{$specification['articlecode']}") ?>" style="width:100%" src="<?php echo $specification['image']; ?>" title="<?php echo $specification['articlecode']; ?>" class="img-responsive redirectable clickable"></a>
                    </td>
                    <td data-title="Article" class="light-technic">
                        <a name="<?php echo $specification['articlecode']; ?>"></a>
                        <span id="5046905201" class="articlecode"><strong><?php echo $specification['articlecode']; ?></strong></span>
                        <!----> <br>
                        <span>
                            <?php echo $specification['title']; ?>
                        </span>
                    </td>

                    <td data-title="Protection" class="protection"><span style="font-weight: bold;"><?php echo isset($technical_data[2], $technical_data[2]['info'])?$technical_data[2]['info']:'' ?></span></td>
                    <td data-title="Control gear" class="driver"><span style="font-weight: bold;"><?php echo isset($technical_data[1], $technical_data[1]['info'])?$technical_data[1]['info']:'' ?></span><br></td>
                    <td data-title="Mounting/Connection" class="connection"><?php echo $specification['mounting'] ?></td>
                    <td data-title="Dimensions (mm)" class="dimensions"><?php echo isset($specification['length'], $specification['width'], $specification['height'])?$specification['length']. 'X'.$specification['width']. 'X'.$specification['height']:'' ?></td>

                    <td class="download text-right">
                        <div>
                            <div class="btn-group">
                                <button title="Select Product"  class="outline-btn" onclick="select_product( '<?php echo $specification['articlecode']; ?>', '<?php echo $specification['product_id']; ?>', <?php echo $mounting ?>)">Select</button>
                                <button title="More info"  class="outline-btn redirectable" data-redirect-to="<?php echo base_url("home/projects/{$project_id}/levels/{$level}/rooms/applications/{$application_id}/rooms/{$room_id}/dimensions/products/" . encryptDecrypt($product_id) . "/mounting/{$mounting}/articles/{$specification['articlecode']}") ?>">Info</button>
                            </div>
                        </div>
                    </td>
                </tr>
                <?php endforeach ?>
                <?php endforeach ?>
            </tbody>
        </table>
    </div>
</div>
<!--Table Wrapper Close-->



<!--Related Products-->
<div class="container">
    <h3 class="Product_heading">Related products</h3>
    <div class="row view">
        <?php
        foreach ($related_products as $related_product) {
            ?>
            <div class="product-view-item col-xs-12 col-sm-6 col-md-3">
                <a href="javascript:void(0)">
                    <img src="<?php echo $related_product['images'][0]; ?>" alt="Junistar Gyro" title="Junistar Gyro" class="img-responsive">
                    <p class="title"><?php echo ucwords($related_product['title']); ?></p></a>
            </div>
            <?php
        }
        ?>

    </div>
    <input type="hidden" name="level" id="level" value="<?php echo $level; ?>">
    <input type="hidden" name="project_id" id="project_id" value="<?php echo $project_id; ?>">
    <input type="hidden" name="application_id" id="application_id" value="<?php echo isset($application_id)?$application_id:''; ?>">
    <input type="hidden" name="room_id" id="room_id" value="<?php echo isset($room_id)?$room_id:''; ?>">
    <input type="hidden" name="product_name" id="product_name" value="<?php echo isset($product_name)?$product_name:''; ?>">
    <input type="hidden" name="project_room_id" id="project_room_id" value="<?php echo isset($project_room_id)?$project_room_id:''; ?>">
</div>
<!--Related Products close-->


<style>
    .articles thead tr th {
        position: -webkit-sticky;
        position: sticky;
        top: 0px;
        background-color: #fff;
        border-bottom: 2px solid #ddd;
        font-weight: bold;
    }
    .articles {
        font-size: .8em;
    }
    table {
        border-collapse: collapse;
        border-spacing: 0;
    }


    .application-type-view-item a img, .case-view-item a img, .inspiration-view-item a img, .page-view-item a img, .product-category-view-item a img, .product-view-item a img, .room-view-item a img, .view-item a img {
        border: 2px solid #f1f1f1;
    }


    .application-type-view-item a .title, .case-view-item a .title, .inspiration-view-item a .title, .page-view-item a .title, .product-category-view-item a .title, .product-view-item a .title, .room-view-item a .title, .view-item a .title {
        font-size: 18px;
        margin: 10px 0 25px;
    }

    .Product_heading {
        border-bottom: 2px solid #e00016;
        font-size: 25px;
        margin: 0 0 25px 0;
        color: #000;
    }


    .image-slider {
        overflow: hidden;
        margin-bottom: 30px;
    }

    .image-slider .image-preview {
        background-color: #fff;
        border-right: 1px solid #e00016;
        position: relative;
    }

    .image-slider .pagination-content .pagination {
        list-style: none;
        overflow: hidden;
        margin-top: 0;
    }

    .pagination {
        display: inline-block;
        padding-left: 0;
        margin: 20px 0;
        border-radius: 0;
    }
    .image-slider .pagination-content .pagination li {
        padding: 10px;
    }
    .pagination>li {
        display: inline;
    }
    .image-slider .pagination-content .pagination img {
        width: 100%;
        cursor: pointer;
        border: 1px solid #f1f1f1;
    }

    .image-slider .image-preview .image-preview-content {
        position: relative;
    }
    .image-slider .image-preview .image-preview-content img {
        position: relative;
        background-color: #fff;
    }

    .center-block {
        display: block;
        margin-left: auto;
        margin-right: auto;
    }


    .image-slider .pagination-content .pagination li.active, .image-slider .pagination-content .pagination li:hover {
        background-color: #f1f1f1;
    }


    .product-description {
        display: -webkit-box;
        display: -ms-flexbox;
        display: flex;
        -webkit-box-pack: justify;
        -ms-flex-pack: justify;
        justify-content: space-between;
    }
    .product-description p {
        padding-right: 25px;
        margin: 0 0 10px 0;
        color:#363636;
    }

    .warrenty-img {
        width: 235px;
        padding-left: 15px;
    }
    .page-title {
        padding: 40px 15px 20px;
    }
    .heading-red {
        color: #2e2e2e;
        padding-left: 1rem;
        padding-bottom: 0;
        border-left-style: solid;
        border-left-width: 0.4rem;
        border-left-color: #e00016;
        border-bottom: 0 solid transparent;
        margin: 0 0 17px 0px;
    }


    .outline-btn{
        background-color: #fff;
        border: 1px solid #e00016;
        color: #e00016;
        padding: 5px 20px;
    }
    .outline-btn:hover{
        color: #fff;
        background-color: #ad0011;
        border-color: transparent;
    }
</style>
