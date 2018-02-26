<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="javascript:void(0)">Home</a>
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
        <?php echo form_open(base_url("home/inspirations/{$inspiration_id}/edit"), ['id' => 'add-inspiration']) ?>
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
                            <div class="form-group">
                                <label class="p-label">Used Products</label>
                                <div class="form-group-field">
                                    <select id="multiple-checked" class="multiple-checked" name="states[]" multiple="multiple">
                                        <option value="AL">Alabama</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="AL">Alabama</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="WY">Wyoming</option>
                                        <option value="WY">Wyoming</option>
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
                                        <li>
                                            <div class="album-thumnail">
                                                <input type="file" name="thumbnail" data-container="#album" class="album-uploader" multiple="" id="addalbum">
                                                <i class="fa fa-plus"></i>
                                            </div>
                                        </li>
                                    </ul>
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