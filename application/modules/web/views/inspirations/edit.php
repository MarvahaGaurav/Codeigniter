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
            <li class="active">Edit Inspiration</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Edit Inspiration</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are
                energy efficient and environmental friendly, in combination with a creation of the ambiance that you need,
                always keeping in mind that luminaires have a great impact on the environment.</p>
        </div>
        
        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3>Inspiration Details</h3>
        </div>
        <!-- Caption before section -->
        <?php echo form_open_multipart(base_url("home/inspirations/{$inspiration_id}/edit"), ['id' => 'add-inspiration']) ?>
            <div class="inspiration-wrapper">
                <div class="user-detail-block3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="p-label">Inspiration Title</label>
                                <div class="form-group-field">
                                    <input type="text" name="title" maxlength="255" value="<?php echo set_value("title", $inspiration['title']) ?>" placeholder="Tower Name">
                                </div>
                                <div class="error"><?php echo form_error("title") ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-detail-block3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="p-label">Description</label>
                                <div class="form-group-field">
                                    <textarea maxlength="255" name="description" class="textarea-height"><?php echo set_value("description", $inspiration['description']) ?></textarea>
                                </div>
                                <div class="error"><?php echo form_error("description") ?></div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-detail-block3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group" id="address-box-wrapper">
                                <label class="labelTxt">Address <i class="fa fa-map-marker"></i></label>
                                <div class="form-group-field">
                                    <textarea readonly name="address" id="address" data-toggle="modal" data-target="#maps-modal" placeholder="Click map marker icon to pick location"><?php echo $inspiration['address'] ?></textarea>
                                </div>
                                <div id="address-map-error"></div>
                                <input type="hidden" value="<?php echo $inspiration['lat'] ?>" name="address_lat" id="address-lat">
                                <input type="hidden" value="<?php echo $inspiration['lng'] ?>" name="address_lng" id="address-lng">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-detail-block3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="p-label">Used Products</label>
                                <div class="form-group-field">
                                    <select id="multiple-checked" class="multiple-checked" name="products[]" multiple="multiple">
                                        <?php foreach ($products as $product) : ?>
                                        <option value="<?php echo $product['product_id'] ?>" <?php echo in_array($product['product_id'], $selectedProducts)?'selected':'' ?>><?php echo $product['title'] ?></option>
                                        <?php endforeach?>
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-detail-block3 no-margin">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="form-group">
                                <label class="p-label">Add Images/Video</label>
                                <div class="form-group-field">
                                    <!-- upload image and video -->
                                    <ul id="album" class="album-wrapper">
                                <?php foreach($inspirationMedia as $media): ?>
                                    <?php if ((int)$media['media_type'] === CONTENT_TYPE_IMAGE) { ?>
                                        <li class="visible-wrapper-original">
                                            <div class="album-thumnail">
                                                <i class="fa fa-plus"></i>
                                                    <input type="hidden" name="existing_image[]" value="<?php echo $media['id'] ?>" data-container="#album" class="album-uploader valid" id="album-add" aria-invalid="false">
                                                    <div class="albub-item"><img src="<?php echo $media['media'] ?>"></div><span class="remove-item" data-content-id="<?php echo $media['id'] ?>"><i class="fa fa-times"></i>
                                                </span>
                                            </div>
                                        </li>
                                    <?php } elseif ((int)$media['media_type'] === CONTENT_TYPE_VIDEO) {?>
                                        <li class="visible-wrapper-original">
                                            <div class="album-thumnail">
                                                <i class="fa fa-plus"></i>
                                                    <input type="hidden" name="existing_image[]" value="<?php echo $media['id'] ?>" data-container="#album" class="album-uploader valid" id="album-add" aria-invalid="false">
                                                    <div class="albub-item"><video src="<?php echo $media['media'] ?>"></video></div><span class="remove-item" data-content-id="<?php echo $media['id'] ?>"><i class="fa fa-times"></i></span><span class="player"><i class="fa fa-play-circle"></i></span>
                                                </span>
                                            </div>
                                        </li>
                                    <?php }?>
                                <?php endforeach ?>

                                        <li class="visible-wrapper" style="<?php echo is_array($inspirationMedia) && count($inspirationMedia) > 3?"display:none;":"" ?>">
                                            <div class="album-thumnail">
                                                <input type="file" name="<?php echo is_array($inspirationMedia)&&count($inspirationMedia) < 4?"inspiration_image[]":"" ?>" data-container="#album" class="album-uploader" multiple="" id="addalbum">
                                                <i class="fa fa-plus"></i>
                                            </div>
                                        </li>
                                    </ul>
                                    <div id="image-to-delete-wrapper">

                                    </div>
                                    <!-- //upload image and video end -->
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Button wrapper section -->
            <div class="section-title clearfix">
                <div class="button-wrapper">
                    <input type="submit" value="Submit" class="custom-btn btn-width save">
                </div>
            </div>
        </form>
        <!-- Button wrapper section -->

    </div>
</div>
<div id="maps-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title">Pick your Location</h4>
            </div>
            <div class="modal-body">
                <div class="input-group col-md-12 col-lg-12 col-sm-12 col-xs-12">
                    <input type="text" name="" id="maps-places">
                </div>
                <div id="maps-box">
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-default" data-dismiss="modal">Done</button>
            </div>
        </div>

    </div>
</div>
