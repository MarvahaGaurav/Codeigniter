<div class="container">
    <ul class="breadcrumb">
        <li><a href="javascript:void(0)">Home</a></li>
        <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
        <li><a href="<?php echo base_url('home/projects/' . $project_id) ?>">Details</a></li>
        <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels') ?>">Levels</a></li>
        <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level .'/rooms') ?>">Rooms</a></li>
        <li><a href="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level .'/rooms/'. $room_id . '/project-rooms/' . $project_room_id . '/accessory-products') ?>">Accessory Products</a></li>
        <li class="active">Accessory Product Details</li>
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
                    <th class="image" style="width:200px"></th>
                    <th class="light-technic" style="width:250px"><b>Article</b></th>
                    <th class="protection">Protection</th>
                    <th class="driver">Control gear</th>
                    <th class="connection">Mounting/Connection</th>
                    <th class="dimensions" style="width:110px">Dimensions (mm)</th>
                    <th class="download" style="width:165px">Action</th>
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
                    <img  style="width:200px" data-redirect-to="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level .'/rooms/'. $room_id . '/project-rooms/' . $project_room_id . '/accessory-products/' . encryptDecrypt($product_id) . '/articles/' . $specification['articlecode']) ?>" src="<?php echo $specification['image']; ?>" title="<?php echo $specification['articlecode']; ?>" class="img-responsive redirectable clickable"></a></td>
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
                                <?php if (in_array($specification['articlecode'], $selected_articles)) { ?>
                                    <button disabled="disabled" type="button" class="inverse-outline-btn">Selected</button>
                                <?php } else {?>
                                    <button title="Select Product" data-selected="<?php echo $this->lang->line('selected') ?>" data-accessory='<?php echo $specification['accessory_data'] ?>'  class="outline-btn select-project-accessory" onclick="">Select</button>
                                <?php } ?>
                                <button title="More info"  class="outline-btn redirectable" data-redirect-to="<?php echo base_url('home/projects/' . $project_id . '/levels/' . $level .'/rooms/'. $room_id . '/project-rooms/' . $project_room_id . '/accessory-products/' . encryptDecrypt($product_id) . '/articles/' . $specification['articlecode']) ?>">Info</button>

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
