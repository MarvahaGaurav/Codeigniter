<div class="inner-container">
    <div class="white-wrapper">
        <div class="container">
            <!-- breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="javascript:void(0)">Home</a></li>
                <li><a href="<?php echo base_url('home/projects') ?>">Quotes</a></li>
                <li><a href="<?php echo base_url('home/quotes/projects'.$projectId.'/'.$request_id) ?>">Project Details</a></li>
                <li><a href="<?php echo base_url('home/projects/' . $projectId.'/'.$request_id.'/quotations') ?>">Received Quotes</a></li>
                <li class="active">Levels</li>
            </ul>

            <div class="page-heading">
                <h1 class="page-title">Levels</h1>
                <p class="prj-description"></p>
            </div>
            <!-- //breadcrumb -->
            <!-- <div class="request-quotation-btn-wrapper">
                <button class="custom-btn btn-width save" type="button">Request Quotation</button>
            </div> -->
            <div class="row clearfix">
                <ul class="list-inline pull-right list-info">
                    <li><a href="javascript:void(0)"><i class="fa fa-eye"></i>&nbsp;View</a></li>
                    <li><a href="javascript:void(0)"><i class="fa fa-check"></i>&nbsp;Level Complete (pending)</a></li>
                    <li><a href="javascript:void(0)"><i class="fa fa-check-circle"></i>&nbsp;Level Complete (done)</a></li>
                    <li><a href="javascript:void(0)"><i class="fa fa-clone"></i>&nbsp;Clone</a></li>
                </ul>
            </div>
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
                                <a href="<?php echo base_url("/home/projects/{$projectId}/levels/{$level['level']}/rooms") ?>" title="<?php echo $this->lang->line('view') ?>"><i class="fa fa-eye"></i></a>  
                                <?php } ?>
                                <?php if ((int)$level['status'] === 0 && (int)$level['room_count'] > 0) { ?>
                                <a href="javascript:void(0)" class="confirmation-action-xhttp" data-json='<?php echo $level['data'] ?>' data-redirect="<?php echo base_url(uri_string()) ?>" data-url="<?php echo base_url('xhttp/projects/mark-as-done') ?>" data-action="clone" data-target="#level-<?php echo $key ?>" data-title="<?php echo $this->lang->line('mark_as_done') ?>" data-message="<?php echo sprintf($this->lang->line('level_mark_as_done_confirmation'), $level['level']) ?>" title="<?php echo $this->lang->line("click_to_mark_as_done") ?>"><i class="fa fa-check"></i></a>  
                                <?php } else if ((int)$level['status'] === 1) {?>
                                <a href="javascript:void(0)" title="<?php echo $this->lang->line('level_marked_done') ?>"><i class="fa fa-check-circle"></i></a>
                                <?php } ?>
                                <?php if (((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                                    (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasAddedFinalPrice) ||
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
            <!-- table-wrapper End -->
            
            <div class="clearfix"></div>
            <?php if (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) { ?>
            <?php if (!empty($projectLevels) && $all_levels_done) { ?>
            <div class="request-quotation-btn-wrapper">
                <button class="col-md-2 custom-btn save redirectable" data-redirect-to="<?php echo base_url('/home/projects/' . $projectId . '/quotation/installers') ?>" id="view-installer-button" type="button" <?php echo (bool)$all_levels_done?"":"disabled" ?>>View Installers</button>
            </div>
            <?php } ?>
            <?php } ?>
            <?php if ((((in_array((int)$userInfo['user_type'], [INSTALLER], true)) && $userInfo['is_owner']==ROLE_OWNER ) || (in_array((int)$userInfo['user_type'], [INSTALLER], true)  && $userInfo['is_owner']==ROLE_EMPLOYEE && isset($permission['quote_add']) && $permission['quote_add']==1 )) && !empty($projectLevels) && (bool)$all_levels_done && (bool)$hasAddedAllPrice ) { ?>
            <div class="request-quotation-btn-wrapper">
                <?php if (!(bool)$hasAddedFinalPrice) { ?>
                <button class="col-md-2 custom-btn save" id="add-price-installer-button" type="button" data-toggle="modal" data-target="#project-final-price-modal"><?php echo $this->lang->line('add_final_price_button_txt') ?></button>
                <?php } else if ((bool)$hasAddedFinalPrice) {?>
                <button class="col-md-2 custom-btn save" id="add-price-installer-button" type="button" data-toggle="modal" data-target="#project-final-price-modal"><?php echo $this->lang->line('view_final_price_button_txt') ?></button>
                <?php }?>
            </div>
            <?php }?>
        </div>
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
            <?php if (!(bool)$hasAddedFinalPrice && ((in_array((int)$userInfo['user_type'], [INSTALLER], true) && $userInfo['is_owner']=ROLE_EMPLOYEE)|| ((in_array((int)$userInfo['user_type'], [INSTALLER], true)) && $userInfo['is_owner']=ROLE_OWNER && isset($permission['quote_add']) && $permission['quote_add']==1 )) ) { ?>
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

