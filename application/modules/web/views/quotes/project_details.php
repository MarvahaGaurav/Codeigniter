<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/quotes/awaiting') ?>">Awaiting Quotes</a></li>
            <li class="active">Project Details</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Projects Details</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficent and environmental friendly, in combination with a creation of the  ambiance that you need, always keeping in mind that luminaires have.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title2">
            <h3><?php echo $project['name']; ?></h3>
        </div>
        <!-- Caption before section -->

        <!-- Project list table -->
        <div class="table-responsive table-wrapper table-mb" id="scrollbar-inner1">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th class="th-first">Project Number</th>
                        <th>Name</th>
                        <th class="text-center">Project Level</th>
                        <th class="">Location</th>
                        <?php if (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && $isRequested) { ?>
                        <th class="text-center">Quotes Count</th>
                        <?php } ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="op-semibold"><?php echo $project['number']; ?></td>
                        <td class="op-semibold"><?php echo $project['name']; ?></td>
                        <td class="op-semibold text-center"><?php echo $project['levels']; ?></td>
                        <td class=""><?php echo $project['address'] ?></td>
                        <?php if (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && $isRequested) { ?>
                        <td class="op-semibold text-center"><?php echo $quoteCount ?> Quote(s)</td>
                        <?php } ?>
                        <td class="op-semibold">
                            <a href="<?php echo base_url('home/quotes/projects/' . encryptDecrypt($project['project_id']) .'/'.encryptDecrypt($request_id). '/edit') ?>" class="project-action"><i class="fa fa-pencil"></i></a>
                            <?php if (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && $isRequested) { ?>
                            <a href="<?php echo base_url('home/projects/' . $projectId . '/quotations') ?>" class="project-action"><i class="fa fa-quote-right"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- //Project list table -->

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3 class="pull-left">Level List</h3>
            
        </div>
        <!-- Caption before section -->
        <!-- <div class="clearfix"></div> -->
        <!-- Project list table -->
       
        <!-- <div class="table-responsive table-wrapper" id="scrollbar-inner2">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Room Dimension</th>
                        <th class="text-center">No. of Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rooms as $room) {
                        ?>
                        <tr>
                            <td class="td-thumb text-nowrap">
                                <div class="d-flex">
                                    <img src="http://smartguide-staging.applaurels.com/public/images/placeholder/no-found-ico-2.svg" />
                                <span class="td-room-type op-semibold"><?php echo $room['name']; ?></span>
                                </div>
                            </td>
                            <td><?php echo $room['length'] . " M x " . $room['width'] . " M x " . $room['height'] . "M"; ?></td>
                            <td class="text-center"><?php echo count($room['products']); ?></td>
                            <td class="op-semibold">
                                <a href="<?php echo base_url("home/projects/view-result/" . encryptdecrypt($room['project_room_id'])); ?>" class="tb-view-list" title="View <?php echo $room['name']; ?> Result">View Result</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div> -->

        <div class="clearfix"></div>
         <div class="table-wrapper table-responsive sticky-table">
            <table class="table table-striped table-custom">
               <thead>
                  <tr class="sticky-header">
                     <th class="">Levels</th>
                     <th class="text-center">Room Count</th>
                     <th class="text-center">Actions</th>
                  </tr>
               </thead>
               <tbody>
                  <?php foreach ($projectLevels as $key => $level) : ?>
                  <tr>
                     <td align="left" class="">Level <?php echo $level['level'] ?></td>
                     <td align="center" class="text-center"><?php echo (int)$level['room_count'] ?></td>
                     <td class="text-center action">
                        <?php if ((bool)$level['active']) { ?>
                        <a href="<?php echo base_url("/home/quotes/projects/{$projectId}/{$request_id}/levels/{$level['level']}/rooms") ?>" title="<?php echo $this->lang->line('view') ?>"><i class="fa fa-eye"></i></a>  
                        <?php } ?>
                        
                        <?php if (((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                           (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasFinalQuotePriceAdded) ||
                           in_array((int)$userInfo['user_type'], [WHOLESALER, ELECTRICAL_PLANNER], true)) &&
                           ((bool)$level['active'] && count($active_levels) > 1 && (int)$level['room_count'] > 0)
                           ) { ?>
                        <a href="javascript:void(0)" class="level-clone-btn" data-source-levels="<?php echo $level['level'] ?>" data-destination-levels="<?php echo $level['cloneable_destinations'] ?>"><i class="fa fa fa-clone"></i></a>
                        <?php } ?>
                     </td>
                  </tr>
                  <?php endforeach ?>
               </tbody>
            </table>
         </div>
        

            
            
            <?php if (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !empty($projectLevels) && (bool)$all_levels_done && (bool)$hasAddedAllPrice) { ?>
            <div class="request-quotation-btn-wrapper">
                <?php if (!(bool)$hasFinalQuotePriceAdded) { ?>
                <button class="col-md-2 custom-btn save" id="add-price-installer-button" type="button" data-toggle="modal" data-target="#project-final-price-modal"><?php echo $this->lang->line('add_final_quote_price_button_txt') ?></button>
                <?php } else if ((bool)$hasFinalQuotePriceAdded) {?>
                <button class="col-md-2 custom-btn save" id="add-price-installer-button" type="button" data-toggle="modal" data-target="#project-final-price-modal"><?php echo $this->lang->line('view_final_quote_price_button_txt') ?></button>
                <?php }?>

                <?php if((bool)$hasFinalQuotePriceAdded){ ?>
                    <button class="col-md-2 custom-btn save" id="send-email-button" type="button" data-toggle="modal" data-target="#send-email-to-customer"><?php echo $this->lang->line('send_email_to_customer_txt') ?></button>

                <?php } ?>


            </div>
            <?php }?>


        <!-- //Project list table -->

        <?php if (empty($rooms)) { ?>
        <!-- <div class="no-record text-center">
            <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg"); ?>" alt="Note Paper">
            <p><?php echo $this->lang->line('no_rooms_in_this_project') ?></p>
            <p>Tap on <a href="<?php echo base_url("home/projects/" . encryptDecrypt($project['project_id']) . "/levels"); ?>" class="page-link">Levels</a> button to view levels and add rooms.</p>
            <!--<p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>-->
        </div> -->
        <?php } ?>

        

    </div>
</div>


<div id="level-clone-modal" class="modal fade" role="dialog">
  <div class="modal-dialog modal-custom">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <div class="text-center">
            <h4 class="modal-title"><?php echo $this->lang->line('clone') ?></h4>
        </div>
      </div>
      <div class="modal-body">
        <p class="text-center"><?php echo $this->lang->line('clone_level_confirmation') ?></p>
        <div class="form-group">
            <label class="labelTxt"><?php echo $this->lang->line('source') ?></label>
            <div class="form-group-field">
                <select name="" id="clone-source"></select>
            </div>
        </div>
        <div class="clearfix"></div>
        <div class="form-group">
            <label class="labelTxt"><?php echo $this->lang->line('destination') ?></label>
            <div class="form-group-field">
                <select name="" id="clone-destination"></select>
                <span class="customArrow"></span>
            </div>
        </div>
      </div>
      <div class="modal-footer">
        <div class="text-center button-wrapper">
            <button type="button" class="custom-btn btn-margin btn-width save" data-csrf='<?php echo $csrf ?>' data-text="<?php echo $this->lang->line('select') ?>" id="level-clone-submit" data-clone=""><?php echo $this->lang->line('clone') ?></button>
        </div>
      </div>
    </div>

  </div>
</div>

<!------add final price model---->
<?php if (in_array((int)$userInfo['user_type'], [INSTALLER], true)) { ?>
<div id="project-final-price-modal" class="modal fade" role="dialog">
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="text-center">
                    <h4 class="modal-title"><?php echo (bool)$hasFinalQuotePriceAdded?$this->lang->line('final_quote_price_header'):$this->lang->line('add_final_quote_price_button_txt') ?></h4>
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
                        <input type="text" <?php echo (bool)$hasFinalQuotePriceAdded && ($request_status==QUOTATION_STATUS_APPROVED ||$request_status==QUOTATION_STATUS_REJECTED)?'disabled="disabled"':'name="additional_product_charges"' ?> class="modal-price-fields restrict-characters number-only-field" data-restrict-to="15" id="additional_product_charges" value="<?php echo isset($projectRoomPrice['additional_product_charges'])?$projectRoomPrice['additional_product_charges']:'' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount" class="priceTxt">Discount&nbsp;(%)</label>
                    <div class="inputField">
                        <input type="text" <?php echo (bool)$hasFinalQuotePriceAdded && ($request_status==QUOTATION_STATUS_APPROVED ||$request_status==QUOTATION_STATUS_REJECTED)?'disabled="disabled"':'name="discount"' ?> class="modal-price-fields restrict-characters number-only-field" id="discount" data-restrict-to="15" value="<?php echo isset($projectRoomPrice['discount'])?$projectRoomPrice['discount']:'' ?>">
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
                <input type="hidden" name="request_id" value=<?php echo $request_id; ?>>              
                </div>
                <?php echo form_close() ?>
            </div>
            <?php if (!(bool)$hasFinalQuotePriceAdded) { ?>
            <div class="modal-footer">
                <div class="text-center button-wrapper">
                    <button type="button" class="custom-btn btn-margin btn-width save" data-csrf='<?php echo $csrf ?>' data-text="<?php echo $this->lang->line('select') ?>" id="final-quote-price-submit" data-clone=""><?php echo $this->lang->line('send-quote-later') ?></button>
                </div>

                <div class="text-center button-wrapper">
                    <button type="button" class="custom-btn btn-margin btn-width save" data-csrf='<?php echo $csrf ?>' data-text="<?php echo $this->lang->line('select') ?>" id="final-quote-price-submit" data-clone=""><?php echo $this->lang->line('send-email-now') ?></button>
                </div>
            </div>
            <?php }?>
        </div>

    </div>
</div>
<?php } ?>

<!------send email to customer modal--->

<div id="send-email-to-customer" class="modal fade" role="dialog" >
    <div class="modal-dialog modal-custom">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <div class="text-center">
                    <h4 class="modal-title"><?php echo (bool)$hasFinalQuotePriceAdded?$this->lang->line('send_email_to_customer_txt'):$this->lang->line('add_final_price_button_txt') ?></h4>
                </div>
            </div>
            <div class="modal-body">
                <?php echo form_open('', ['id' => 'installer-send-email', 'class' => 'form-horizontal', 'role' => 'form']) ?>
                <div class="projectPric">
                <div class="form-group">
                        	
                    <label for="contact-email" class="col-sm-6 control-label">E-mail:</label>
                            <div class="col-sm-10">
                            	<input type="email" name="email" class="form-control" id="contact-email" placeholder="you@example.com">
                            </div>
                        </div>            
                </div>
                <?php echo form_close() ?>

            </div>

            <div class="text-center button-wrapper">
                    <button type="button" class="custom-btn btn-margin btn-width save" data-csrf='<?php echo $csrf ?>' data-text="<?php echo $this->lang->line('select') ?>" id="send-email-to-customer" data-clone=""><?php echo $this->lang->line('send_email') ?></button>
            </div>
        </div>

    </div>
</div>
