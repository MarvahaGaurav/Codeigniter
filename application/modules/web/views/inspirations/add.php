<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="active">Add Inspiration</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Add Inspiration</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are
                energy efficient and environmental friendly, in combination with a creation of the ambiance that you need,
                always keeping in mind that luminaires have a great impact on the environment.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3>Inspiration Details</h3>
        </div>
        <!-- Caption before section -->
        <?php echo form_open_multipart(base_url('home/inspirations/add'), ['id' => 'add-inspiration']) ?>
            <div class="inspiration-wrapper">
                <div class="user-detail-block3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="p-label">Inspiration Title</label>
                                <div class="form-group-field">
                                    <input type="text" name="title" class="alphanumspaces-only-field" maxlength="255" value="<?php echo set_value("title") ?>" placeholder="Tower Name">
                                    <label class="error"><?php echo form_error("title") ?></label>
                                </div>
                            </div>
                            <div class="error"><?php echo form_error("title") ?></div>
                        </div>
                    </div>
                </div>
                <div class="user-detail-block3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="p-label">Description</label>
                                <div class="form-group-field">
                                    <textarea maxlength="255" name="description" class="textarea-height"><?php echo set_value("description") ?></textarea>
                                    <label class="error"><?php echo form_error("description") ?></label>
                                </div>
                            </div>
                            <div class="error"><?php echo form_error("description") ?></div>
                        </div>
                    </div>
                </div>
                <div class="user-detail-block3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group" id="address-box-wrapper">
                                <label class="labelTxt">Address <i class="fa fa-map-marker"></i></label>
                                <div class="form-group-field">
                                    <textarea readonly name="address" id="address" data-toggle="modal" data-target="#maps-modal" placeholder="Click map marker icon to pick location"></textarea>                                    
                                </div>
                                <div id="address-map-error"></div>
                                <input type="hidden" name="address_lat" id="address-lat">
                                <input type="hidden" name="address_lng" id="address-lng">
                            </div>
                        </div>
                    </div>
                </div>
                <div class="user-detail-block3">
                    <div class="row">
                        <div class="col-lg-6 col-sm-6 col-xs-12">
                            <div class="form-group">
                                <label class="p-label">Used Products</label>
                                <div class="form-group-field" id="multicheck-products">
                                    <select id="multiple-checked" class="multiple-checked" name="products[]" multiple="multiple">
                                        <?php foreach ($products as $product) : ?>
                                        <option value="<?php echo $product['product_id'] ?>" <?php ?>><?php echo $product['title'] ?></option>
                                        <?php endforeach; ?>
                                    </select>
                                </div>
                                <label for="" class="error"><?php echo form_error('products[]') ?></label>
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
                                        <li class="visible-wrapper">
                                            <div class="album-thumnail">
                                                <input type="file" name="inspiration_image[]" data-container="#album" class="album-uploader" id="addalbum">
                                                <i class="fa fa-plus"></i>
                                            </div>
                                        </li>
                                    </ul>
                                    <label for="media-error" class="error" id="media-error-label"></label>
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
                <button type="submit" id="inspiration-add-submit" class="custom-btn btn-width save">Submit</button>
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
