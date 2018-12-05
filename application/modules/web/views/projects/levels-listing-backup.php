<div class="inner-container">
    <div class="white-wrapper">
        <div class="container">
            <!-- breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="javascript:void(0)">Home</a></li>
                <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
                <li><a href="<?php echo base_url('home/projects/' . $projectId) ?>">Details</a></li>
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
            <div class="clearfix"></div>
            <?php foreach ($projectLevels as $level) : ?>
            <div class="white-card-wrapper levels-listing-wrapper clickable <?php echo !(bool)$level['active']?"disabled-level":"" ?>" data-redirect-to="<?php echo base_url("/home/projects/{$projectId}/levels/{$level['level']}/rooms") ?>">
                <div class="col-left">
                    <span class="level-heading">Level <?php echo $level['level'] ?></span>
                    <!-- <span class="level-count">Level <?php echo $level['level'] ?></span> -->
                </div>
                <div class="col-right text-right">
                    <div class="action-btn-wrapper">
                        <ul>
                            <?php if ((int)$level['status'] === 0 && (int)$level['room_count'] > 0) { ?>
                            <li> <button class="level-btn mark-as-done" type="button" <?php echo (int)$level['room_count'] < 1?"disabled":"" ?> data-level-data='<?php echo $level['data'] ?>' title="<?php echo (int)$level['room_count'] < 1?$this->lang->line("add_rooms_to_mark_as_done"):"" ?>"> Mark as Done </button> </li>
                            <?php } else if ((int)$level['status'] === 1) {?>
                            <li class="level-done-li" title="<?php echo $this->lang->line('level_marked_done') ?>"><i class="fa fa-check-circle level-done-check"></i></li>
                            <?php } else if ((bool)$level['active']) { ?>
                            <li class="level-done-li" title="<?php echo $this->lang->line('add_rooms') ?>"><i class="fa fa-plus-circle level-done-check"></i></li>
                            <?php } ?>
                            <?php if (((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                                (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasAddedFinalPrice) ||
                                in_array((int)$userInfo['user_type'], [WHOLESALER, ELECTRICAL_PLANNER], true)) &&
                                ((bool)$level['active'] && count($active_levels) > 1 && (int)$level['room_count'] > 0)
                            ) { ?>
                            <li><button type="button" data-source-levels="<?php echo $level['level'] ?>" data-destination-levels="<?php echo $level['cloneable_destinations'] ?>" class="level-btn level-clone-btn"><?php echo $this->lang->line('clone') ?></button></li>
                            <?php } ?>
                            <!-- <li> <button class="level-btn"> + Add </button></li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
            <div class="clearfix"></div>
            <?php if (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) { ?>
            <?php if (!empty($projectLevels) && $all_levels_done) { ?>
            <div class="request-quotation-btn-wrapper">
                <button class="col-md-2 custom-btn save redirectable" data-redirect-to="<?php echo base_url('/home/projects/' . $projectId . '/quotation/installers') ?>" id="view-installer-button" type="button" <?php echo (bool)$all_levels_done?"":"disabled" ?>>View Installers</button>
            </div>
            <?php } ?>
            <?php } ?>
            <?php if (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !empty($projectLevels) && (bool)$all_levels_done && (bool)$hasAddedAllPrice) { ?>
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
<style>
    .level-done-check {
        font-size: 30px;
        color: #e03014;
    }

    .white-card-wrapper {
        display: flex;
        background: #fff;
        box-shadow: 0 0 6px #ccc;
        padding: 23px 28px;
        border-radius: 5px;
        margin: 0 0 20px 0;
    }

    .col-left {
        width: 50%;
        padding: 0 10px;
    }

    .col-right {
        width: 50%;
        padding: 0 10px;
    }

    .level-heading {
        font-size: 22px;
        font-weight: 600;
        margin: 0 11px 0 0;
        display: inline-block;
        max-width: 250px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        vertical-align: middle;
        color:black;
    }

    .level-count {
        font-size: 14px;
        color: #e4001b;
        font-weight: 600;
    }

    .action-btn-wrapper ul li {
        display: inline-block;
        margin: 0 10px;
    }
    .action-btn-wrapper ul li.level-done-li {
        display: inline-block;
        margin: 0 10px;
        vertical-align: -6px;
    }


    .action-btn-wrapper .level-btn {
        width: 124px;
        border: 1px solid #e4001b;
        border-radius: 4px;
        padding: 5px 0;
        background: #e4001b;
        color: #fff;
        text-transform: uppercase;
    }

    .action-btn-wrapper .level-btn:hover {
        background: #b7061b;
        box-shadow: 0px 3px 6px #0000001f;
    }

.white-card-wrapper {
    display: flex;
    background: #fff;
    box-shadow: 0 0 6px #ccc;
    padding: 23px 28px;
    border-radius: 5px;
    margin: 0 0 20px 0;
}

.col-left {
    width: 50%;
    padding: 0 10px;
}

.col-right {
    width: 50%;
    padding: 0 10px;
}

.level-heading {
    font-size: 22px;
    font-weight: 600;
    margin: 0 11px 0 0;
    display: inline-block;
    max-width: 250px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    vertical-align: middle;
}

.level-count {
    font-size: 14px;
    color: #e4001b;
    font-weight: 600;
}

.action-btn-wrapper ul li {
    display: inline-block;
    margin: 0 10px;
}


.action-btn-wrapper .level-btn {
    width: 124px;
    border: 1px solid #e4001b;
    border-radius: 4px;
    padding: 5px 0;
    background: #e4001b;
    color: #fff;
    text-transform: uppercase;
}

.action-btn-wrapper .level-btn:hover {
    background: #b7061b;
    box-shadow: 0px 3px 6px #0000001f;
}
</style>
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
                <div class="form-group">
                    <label for="total_price_per_luminary" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">Total Luminary Price</label>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">
                        <input type="text" disabled="disabled" class="modal-price-fields" id="total_price_per_luminary" value="<?php echo isset($projectRoomPrice['price_per_luminaries'])?$projectRoomPrice['price_per_luminaries']:'' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="total_installation_charges" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">Total Installation Charges</label>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">
                        <input type="text" disabled="disabled" class="modal-price-fields" id="total_installation_charges" value="<?php echo isset($projectRoomPrice['installation_charges'])?$projectRoomPrice['installation_charges']:'' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="additional_product_charges" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">Additional Product Charges</label>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">
                        <input type="text" <?php echo (bool)$hasAddedFinalPrice?'disabled="disabled"':'name="additional_product_charges"' ?> class="modal-price-fields restrict-characters number-only-field" data-restrict-to="15" id="additional_product_charges" value="<?php echo isset($projectRoomPrice['additional_product_charges'])?$projectRoomPrice['additional_product_charges']:'' ?>">
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">Discount&nbsp;(%)</label>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">
                        <input type="text" <?php echo (bool)$hasAddedFinalPrice?'disabled="disabled"':'name="discount"' ?> class="modal-price-fields restrict-characters number-only-field" id="discount" data-restrict-to="15" value="<?php echo isset($projectRoomPrice['discount'])?$projectRoomPrice['discount']:'' ?>">
                    </div>
                </div>
                <hr>
                <div class="form-group">
                    <label for="discount" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">Subtotal</label>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">
                        <?php echo isset($projectRoomPrice['subtotal'])?$projectRoomPrice['subtotal']:0.00 ?>
                    </div>
                </div>
                <div class="form-group">
                    <label for="discount" class="col-lg-4 col-md-4 col-sm-4 col-xs-12">Total</label>
                    <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4 pull-right">
                        <?php echo isset($projectRoomPrice['total'])?$projectRoomPrice['total']:0.00 ?>
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

