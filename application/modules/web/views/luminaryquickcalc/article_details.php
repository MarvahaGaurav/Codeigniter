<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
        <li><a href="javascript:void(0)">Home</a></li>
        <li><a href="<?php echo base_url('home/fast-calc/luminary') ?>">No of Luminaries</a></li>
        <li><a href="<?php echo base_url('home/fast-calc/luminary/applications/' . $applicationId . '/rooms/' . $roomId . '/products') ?>">Products</a></li>
        <li><a href="<?php echo base_url('home/fast-calc/luminary/applications/' . $applicationId . '/rooms/' . $roomId . '/products/' . $productId) ?>">Details</a></li>
            <li class="active">Article Details</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <input type="hidden" value="<?php echo $productData['title']; ?>" id="product_name">
            <input type="hidden" value="<?php echo $roomId; ?>" id="room_id">
            <input type="hidden" value="<?php echo $applicationId; ?>" id="application_id">
            <h1 class="page-title"><?php echo $productData['title'] ?></h1>
        </div>

        <!-- thumb view -->
        <div class="product-description-wrapper">
            <div class="row">
                <div class="col-sm-4 col-xs-12">
                    <div class="thumb-box">
                        <div class="thumb-view-wrapper thumb-view-contain thumb-view-contain-pd thumb-view-fullp img-viewbdr-radius4">
                            <div class="thumb-view thumb-viewfullheight-3" style="background:url('<?php echo $articleData['image'] ?>')"></div>
                        </div>
                    </div>
                </div>
                <div class="col-sm-8 col-xs-12">
                    <!-- light type details -->
                    <div class="lighttypedetails">
                        <div class="light-detail-heading">
                            
                            <h3><?php echo $articleData['title'] ?></h3>
                        </div>
                        <div class="light-detail-info-box light-detail-info-box-1">
                            <div class="light-detail-info clearfix">
                                <h4>Article Code:</h4>
                                <p><?php echo $articleData['articlecode'] ?></p>
                            </div>
                            <div class="light-detail-info clearfix">
                                <h4>EAN:</h4>
                                <p><?php echo $articleData['ean'] ?></p>
                            </div>
                        </div>
                        <p><?php echo $productData['body'] ?></p>
                        <!-- button wrapper section -->
                        <div class="section-title clearfix">
                            <div class="button-wrapper">
                                <a href="<?php echo $articleData['pdf'] ?>" target="_blank" class="custom-btn btn-width save">
                                    Download
                                </a>
                                <button type="button" class="custom-btn btn-width save" onclick="select_product('<?php echo $articleData['articlecode'];?>', '<?php echo $articleData['product_id'];?>','<?php echo $mounting ?>')">Select</button>
                            </div>
                        </div>
                        <!-- button wrapper section -->
                    </div>
                    <!-- //light type details -->
                </div>
            </div>
        </div>
        <!-- thumb view end -->

        <div class="section-title clearfix">
            <h3>Technical Details</h3>
        </div>

        <div class="thumb-view-footer">
            <h4>Energy Class: <?php echo $articleData['energy_class'] ?></h4>
            <!-- light type description -->
            <div class="row thumb-footer-detail">

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <!-- light type details -->
                    <div class="lighttypedetails">
                        <h3 class="thumb-footer-title">Light Technic</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="rd-property">Type of light source:</p>
                                <p class="rd-value"><?php echo $articleData['type_of_light_source'] ?></p>
                            </div>
                            
                            <div class="col-xs-12">
                                <p class="rd-property">Wattage:</p>
                                <p class="rd-value"><?php echo $articleData['wattage'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">System Wattage:</p>
                                <p class="rd-value"><?php echo $articleData['system_wattage'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Efficiency:</p>
                                <p class="rd-value"><?php echo $articleData['efficacy'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Voltage:</p>
                                <p class="rd-value"><?php echo $articleData['voltage'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Colour Temperature:</p>
                                <p class="rd-value"><?php echo $articleData['colour_temperature'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Colour rendering (CRI):</p>
                                <p class="rd-value"><?php echo $articleData['colour_rendering'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">MacAdams Factor:</p>
                                <p class="rd-value"><?php echo $articleData['mac_adams_factor'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Lifetime:</p>
                                <p class="rd-value"><?php echo $articleData['lifetime'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Beam angle:</p>
                                <p class="rd-value"><?php echo $articleData['beam_angle'] ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- light type details end -->

                    <!-- light type details -->
                    <div class="lighttypedetails">
                        <h3 class="thumb-footer-title">Material and Finish</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="rd-property">Housing:</p>
                                <p class="rd-value"><?php echo '' ?></p>
                            </div>
                            
                            <div class="col-xs-12">
                                <p class="rd-property">Colour:</p>
                                <p class="rd-value"><?php echo $articleData['colour'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Optics:</p>
                                <p class="rd-value"><?php echo $articleData['optics'] ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- light type details end -->

                    <!-- light type details -->
                    <div class="lighttypedetails">
                        <h3 class="thumb-footer-title">Packaging</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="rd-property">Packaging Dimensions:</p>
                                <p class="rd-value">610 x 190 x 80</p>
                            </div>
                        </div>
                    </div>
                    <!-- light type details end -->
                </div>

                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <!-- light type details -->
                    <div class="lighttypedetails">
                        <h3 class="thumb-footer-title">Protection</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="rd-property">Luminaire Class:</p>
                                <p class="rd-value">Class I</p>
                            </div>
                            
                            <div class="col-xs-12">
                                <p class="rd-property">Ingress Protection Rating:</p>
                                <p class="rd-value"><?php echo $articleData['ingress_protection_rating'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Ta Nominel:</p>
                                <p class="rd-value"><?php echo $articleData['ta_nominel'] ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- light type details end -->

                    <!-- light type details -->
                    <div class="lighttypedetails">
                        <h3 class="thumb-footer-title">Energy and Approvals</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="rd-property">Energy Class:</p>
                                <p class="rd-value"><?php echo $articleData['energy_class'] ?></p>
                            </div>
                            
                            <div class="col-xs-12">
                                <p class="rd-property">Approval:</p>
                                <p class="rd-value"><?php echo $articleData['approval'] ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- light type details end -->

                    <!-- light type details -->
                    <div class="lighttypedetails">
                        <h3 class="thumb-footer-title">Mounting / Connection</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="rd-property">Mounting:</p>
                                <p class="rd-value"><?php echo $articleData['mounting'] ?></p>
                            </div>
                            
                            <div class="col-xs-12">
                                <p class="rd-property">Plug:</p>
                                <p class="rd-value"><?php echo $articleData['plug'] ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- light type details end -->

                    <!-- light type details -->
                    <div class="lighttypedetails">
                        <h3 class="thumb-footer-title">Dimensions(mm)</h3>
                        <div class="row">
                            <div class="col-xs-12">
                                <p class="rd-property">Length (L):</p>
                                <p class="rd-value"><?php echo $articleData['length'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Width (W):</p>
                                <p class="rd-value"><?php echo $articleData['width'] ?></p>
                            </div>
                            
                            <div class="col-xs-12">
                                <p class="rd-property">Height (H):</p>
                                <p class="rd-value"><?php echo $articleData['height'] ?></p>
                            </div>

                            <div class="col-xs-12">
                                <p class="rd-property">Weight (brutto / netto):</p>
                                <p class="rd-value"><?php echo $articleData['brutto_weight'] ?>/<?php echo $articleData['netto_weight'] ?></p>
                            </div>
                        </div>
                    </div>
                    <!-- light type details end -->

                </div>
                
            </div>
            <!-- light type description end -->
        </div>

    </div>
</div>