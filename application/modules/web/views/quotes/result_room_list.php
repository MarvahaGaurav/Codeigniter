<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
        <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/quotes/awaiting') ?>">Awaiting Quotes</a></li>
            <li><a href="<?php echo base_url('home/quotes/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/quotes/projects/' . $projectId) ?>">Details</a></li>
            <li class="active">Rooms</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Room Results</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us
                to deliver products that are energy efficent and environmental friendly, in combination with a creation
                of the ambiance that you need, always keeping in mind that luminaires have.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3 class="pull-left">Room Result List</h3>
        </div>
        <!-- Caption before section -->

        <!-- Project list table -->
        <div class="table-responsive table-wrapper" id="scrollbar-inner">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Room Dimension</th>
                        <th class="text-center">No. of Rooms</th>
                        <th class="text-center">Additional Products</th>
                        <?php if (in_array((int)$userInfo['user_type'], [INSTALLER], true)) { ?>
                        <th class="text-center">Installation Price</th>
                        <th class="text-center">Comparison</th>
                        <?php } ?>
                        <th class="text-center">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rooms as $key => $room) : ?>
                    <tr>
                        <td class="td-thumb text-nowrap">
                            <div class="d-flex">
                              <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg")?>"  />
                                <span class="td-room-type op-semibold"><?php echo strlen($room['reference_name'])>0?$room['reference_name']:$room['name'] ?></span>
                            </div>
                        </td>
                        <td><?php echo "{$room['length']}M x {$room['width']}M x {$room['height']}M" ?></td>
                        <td class="text-center"><?php echo $room['count'] ?> <?php echo (int)$room['count'] > 1?$this->lang->line('room_count_sets_txt'):$this->lang->line('room_count_set_txt') ?></td>
                        <td class="text-center"><?php echo count($room['products']) - 1 ?></td>
                        <?php if (in_array((int)$userInfo['user_type'], [INSTALLER], true)) { ?>
                        <td class="text-center">
                        <?php if (empty($room['price'])) { ?>
                            <a href="javascript:void(0)" id="add-price-<?php echo $key ?>" data-modal-text="<?php echo $this->lang->line('add_price_txt') ?>" data-action="add" data-target-value="<?php echo $key ?>" data-room-price='<?php echo $room['price_data'] ?>'' class="tb-view-list project-action installer-add-price" title="<?php echo $this->lang->line('add_price_txt') ?>" data-project-room-id="<?php echo encryptDecrypt($room['project_room_id']) ?>">Add</a>
                            <?php } else {?>
                            <?php echo $room['price']['total'] ?>$
                            <?php if (!(bool)$hasAddedFinalPrice) { ?>
                            <a href="javascript:void(0)" id="add-price-<?php echo $key ?>" data-modal-text="<?php echo $this->lang->line('edit_price_txt') ?>" data-action="edit" data-target-value="<?php echo $key ?>" data-room-price='<?php echo $room['price_data'] ?>'' class="project-action installer-add-price" title="<?php echo $this->lang->line('edit_price_txt') ?>" data-project-room-id="<?php echo encryptDecrypt($room['project_room_id']) ?>"><i class="fa fa-pencil"></i></a>
                            <?php }?>
                        <?php  } ?>
                        </td>
                        <td class="text-center">
                            <a href="javascript:void(0)" onclick="window.alert('Under development')" class="tb-view-list" title="<?php echo $this->lang->line('tco_txt') ?>"><?php echo $this->lang->line('tco_txt') ?></a>
                        </td>
                        <?php } ?>
                        <td class="op-semibold text-center">
                        <a href="<?php echo base_url("/home/quotes/projects/view-result/" . encryptDecrypt($room['project_room_id'])) ?>" class="tb-view-list" title="View Results">View Results</a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                <?php if (in_array((int)$userInfo['user_type'], [INSTALLER], true)) { ?>
                <tfoot>
                    <tr>
                    <td colspan="7">
                                <div class="priceContainer">
                    <div class="price-wrapper">
                        <label for="" class="price-label"><?php echo $this->lang->line('price_per_luminaries') ?></label>
                            <input type="text" class="price-fields"value="<?php echo isset($projectRoomPrice['price_per_luminaries'])?$projectRoomPrice['price_per_luminaries']:'' ?>" disabled>
                    </div>
                    <div class="plus-min-icon">&#43;</div>
                    <div class="price-wrapper">
                    <label for="" class="price-label"><?php echo $this->lang->line('installation_charges') ?></label>
                            <input type="text" class="price-fields"value="<?php echo isset($projectRoomPrice['installation_charges'])?$projectRoomPrice['installation_charges']:'' ?>" disabled>
                    </div>
                    <div class="plus-min-icon">&#45;</div>
                    <div class="price-wrapper">
                        <label for="" class="price-label"><?php echo $this->lang->line('discount_price') ?>(%)</label>
                        <input type="text" class="price-fields"value="<?php echo isset($projectRoomPrice['discount_price'])?$projectRoomPrice['discount_price']:'' ?>" disabled>
                    </div>
                    <div class="plus-min-icon">&#61;</div>
                    <div class="priceWrapper text-center">
                        <label for="" class="price-label">Total</label>
                            <div class="totalPrice"><?php  echo isset($projectRoomPrice['total'])?$projectRoomPrice['total']:'' ?></div>
                    </div>
                </div>
                        </td>
                    </tr>
                </tfoot>
                <?php } ?>
            </table>
            
        </div>

        <div class="section-title clearfix">
            
            <div class="button-wrapper clearfix">
            
            <?php if (in_array((int)$userInfo['user_type'], [INSTALLER], true)) { ?>
                <button class="col-md-2 btn-margin custom-btn save" id="add-price-installer-button" type="button" data-toggle="modal" data-target="#quotes-send-email"><?php echo $this->lang->line('send_email_to_customer_txt') ?></button>
            <?php } ?>
            
            </div>

        
        </div>

        
        

        
        
        <div class="pagination-wrap">
            <?php echo $links ?>
        </div>
        <!-- //Project list table -->

        <!-- Caption before section -->
        <!-- Caption before section -->

        <!-- no record found -->
        <!-- <div class="no-record text-center">
                <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg") ?> alt="Note Paper">
                <p>You have no room.</p>
                <p>Tap on <a href="login.html" class="page-link">Add Room</a> button to add a room.</p>
            </div> -->
        <!-- no record found -->
    </div>
</div>

<div id="add-price-modal" class="modal fade" role="dialog">
    <div class="modal-dialog">

        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="text-center">
                    <h4 class="modal-title modal-price-text"></h4>
                </div>
            </div>
            <div class="modal-body">
            
                <?php echo form_open(base_url(uri_string()), ['id' => 'add-price-form']) ?>
                <div class="">
                  <div class="row">
                   <div class="col-md-12">
                       <div class="priceDetails">
                           <div class="colLeft">Main Product Price</div>
                           <div class="colRight">0.00</div>
                       </div>
                       <div class="priceDetails">
                           <div class="colLeft">Accessory Product Price</div>
                           <div class="colRight">0.00</div>
                       </div>
                       
                       
                   </div>
                   <!-- col-md-12 close -->
                    </div>
                    <!-- row close -->
                   
              
                    <div class="row">
                       <div class="col-md-12">
                        <div class="form-group">
                            <label class="labelTxt"><?php echo $this->lang->line('price_per_luminaries') ?></label>
                            <div class="form-group-field">
                                <input name="price_per_luminaries" class="room-price-fields number-only-field restrict-characters" data-restrict-to='15' id="price-per-luminaries" type="text" placeholder="10.00" maxlength="15">
                            </div>
                        </div>
                        <!-- form-group -->
                        
                        <div class="form-group">
                            <label class="labelTxt"><?php echo $this->lang->line('installation_charges') ?></label>
                            <div class="form-group-field">
                                <input type="text" class="room-price-fields number-only-field restrict-characters" data-restrict-to='15' name="installation_charges" id="installation-charges" placeholder="10.00" maxlength="15">
                            </div>
                        </div>
                        <!-- form-group -->
                        <div class="form-group">
                            <label class="labelTxt"><?php echo $this->lang->line('discount_price') ?></label>
                            <div class="form-group-field">
                                <input type="text" class="room-price-fields number-only-field restrict-characters" data-restrict-to='15' name="discount_price" id="discount-price" placeholder="10.00" maxlength="15">
                            </div>
                        </div>
                        <!-- form-group -->
                        
                        <div class="priceDetails">
                           <div class="colLeft">Subtotal</div>
                           <div class="colRight" id="subtotal">0</div>
                       </div>
                       
                        <div class="priceDetails">
                           <div class="colLeft">Total</div>
                           <div class="colRight" id="total">0</div>
                       </div>
                      </div>
                      <!-- col-md-12 close -->
                    </div>
                    <!-- row close -->                                    
                    <input type="hidden" name="project_room_id" id="project-room-id" value="">
                    <span id="target-handler" data-target=""></span>
                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <div class="text-center button-wrapper">
                    <button type="button" class="custom-btn btn-margin btn-width save modal-price-text" data-csrf='<?php echo $csrf ?>' data-text="" id="add-quote-price-submit"></button>
                </div>
            </div>
        </div>

    </div>
</div>


<?php if (in_array((int)$userInfo['user_type'], [INSTALLER], true)) { ?>
<div id="project-final-price-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="text-center">
                    <h4 class="modal-title"><?php echo (bool)$hasAddedFinalPrice?$this->lang->line('final_project_price_header'):$this->lang->line('add_final_price_button_txt') ?></h4>
                </div>
            </div>
            <div class="modal-body">
                <?php echo form_open('', ['id' => 'installer-submit-price', 'class' => 'form-horizontal', 'role' => 'form']) ?>
                <input type="hidden" name="project_id" value="<?php echo $projectId ?>">
                <div class="projectPric">
                <div class="form-group">
                    <label for="total_price_per_luminary" class="priceTxt">Total Luminary Price</label>
                    <div class="inputField">
                        <input type="text" disabled="disabled" class="modal-price-fields" id="total_price_per_luminary" value="<?php echo isset($projectRoomPrice['price_per_luminaries'])?$projectRoomPrice['price_per_luminaries']:'' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="total_installation_charges" class="priceTxt">Total Installation Charges</label>
                    <div class="inputField">
                        <input type="text" disabled="disabled" class="modal-price-fields" id="total_installation_charges" value="<?php echo isset($projectRoomPrice['installation_charges'])?$projectRoomPrice['installation_charges']:'' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="additional_product_charges" class="priceTxt">Additional Product Charges</label>
                    <div class="inputField">
                        <input type="text" <?php echo (bool)$hasAddedFinalPrice?'disabled="disabled"':'name="additional_product_charges"' ?> class="modal-price-fields restrict-characters number-only-field" data-restrict-to="15" id="additional_product_charges" value="<?php echo isset($projectRoomPrice['additional_product_charges'])?$projectRoomPrice['additional_product_charges']:'' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount" class="priceTxt">Discount&nbsp;(%)</label>
                    <div class="inputField">
                        <input type="text" <?php echo (bool)$hasAddedFinalPrice?'disabled="disabled"':'name="discount"' ?> class="modal-price-fields restrict-characters number-only-field" id="discount" data-restrict-to="15" value="<?php echo isset($projectRoomPrice['discount'])?$projectRoomPrice['discount']:'' ?>">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="discount" class="priceTxt">Subtotal</label>
                    <div class="inputField">
                        <span class="total"><?php echo isset($projectRoomPrice['subtotal'])?$projectRoomPrice['subtotal']:0.00 ?></span>
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="discount" class="priceTxt">Total</label>
                    <div class="inputField">
                        <span class="total"><?php echo isset($projectRoomPrice['total'])?$projectRoomPrice['total']:0.00 ?></span>
                    </div>
                </div>                
                </div>
                <?php echo form_close() ?>
            </div>
            <?php if (!(bool)$hasAddedFinalPrice) { ?>
            <div class="modal-footer">
                <div class="text-center button-wrapper">
                    <button type="button" class="custom-btn btn-margin btn-width save" data-csrf='<?php echo $csrf ?>' data-text="<?php echo $this->lang->line('select') ?>" id="final-price-submit" data-clone=""><?php echo $this->lang->line('add_final_price_button_txt') ?></button>
                </div>
            </div>
            <?php }?>
        </div>

    </div>
</div>
<?php } ?>
