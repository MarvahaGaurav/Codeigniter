<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/quotes/awaiting') ?>">Awaiting Quotes</a></li>
            <li><a href="<?php echo base_url('home/quotes/projects/'.$projectId.'/'.$request_id) ?>">Project Details</a></li>
            <li><a href="<?php echo base_url('home/quotes/projects/' . $projectId . '/levels') ?>">Levels</a></li>
            <li class="active">Rooms</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Rooms</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us
                to deliver products that are energy efficent and environmental friendly, in combination with a creation
                of the ambiance that you need, always keeping in mind that luminaires have.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3 class="pull-left">Room List</h3>
            <?php if (
                (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasAddedFinalPrice) ||
                (in_array((int)$userInfo['user_type'], [WHOLESALER, ELECTRICAL_PLANNER], true))
            ) { ?>
            
            <?php }?>
        </div>
        <!-- Caption before section -->

        <!-- Project list table -->
        <?php if (!empty($rooms)) { ?>
        <div class="table-responsive table-wrapper" id="scrollbar-inner">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th class="">Room Dimension</th>
                        <th class="text-center">No. of Rooms</th>
                        <th class="text-center">No. of Products</th>
                        <th class="text-center">Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($rooms as $key => $room) : ?>
                    <tr>
                        <td class="td-thumb text-nowrap">
                            <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg")?>"  />
                            <span class="td-room-type op-semibold"><?php echo strlen($room['reference_name'])>0?$room['reference_name']:$room['name'] ?></span>
                        </td>
                        <td><?php echo "{$room['length']}M x {$room['width']}M x {$room['height']}M" ?></td>
                        <td class="text-nowrap text-center">
                            <!-- Change the `data-field` of buttons and `name` of input field's for multiple plus minus buttons-->
                            <div class="input-group plus-minus-input">
                                <div class="input-group-button btn-circle">
                                    <?php if (
                                        (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                                        (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasAddedFinalPrice) ||
                                        (in_array((int)$userInfo['user_type'], [WHOLESALER, ELECTRICAL_PLANNER], true))
                                    ) { ?>
                                    <button type="button" id="decrement-room-count-<?php echo $key ?>" data-action="decrement" data-id="<?php echo $key ?>" data-url="<?php echo base_url('xhttp/projects/rooms/decrement-count') ?>" data-json='<?php echo $room['room_count_data'] ?>' class="button change-room-count hollow circle" data-quantity="minus" data-field="quantity">
                                        <i class="fa fa-minus" aria-hidden="true"></i>
                                    </button>
                                    <?php } ?>
                                </div>
                                <input class="input-group-field input-circle-space" id="room-count-<?php echo $key ?>" data-id="<?php echo $key ?>" type="number" name="quantity" value="<?php echo $room['count'] ?>" disabled>
                                <div class="input-group-button btn-circle">
                                    <?php if (
                                        (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                                        (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasAddedFinalPrice) ||
                                        (in_array((int)$userInfo['user_type'], [WHOLESALER, ELECTRICAL_PLANNER], true))
                                    ) { ?>
                                    <button type="button" id="increment-room-count-<?php echo $key ?>" data-action="increment" data-id="<?php echo $key ?>" data-url="<?php echo base_url('xhttp/projects/rooms/increment-count') ?>" data-json='<?php echo $room['room_count_data'] ?>' class="button change-room-count hollow circle" data-quantity="plus" data-field="quantity">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                    <?php } ?>
                                </div>
                            </div>
                        </td>
                        <td class="text-nowrap text-center">
                            <!-- Change the `data-field` of buttons and `name` of input field's for multiple plus minus buttons-->
                            <div class="input-group plus-minus-input">
                                <input class="input-group-field input-square-space" type="number" disabled="disabled" name="quantity" value="<?php echo count($room['products']) ?>">
                                <?php if (
                                    (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                                    (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasAddedFinalPrice) ||
                                    (in_array((int)$userInfo['user_type'], [WHOLESALER, ELECTRICAL_PLANNER], true))
                                ) { ?>
                                <div class="input-group-button btn-square">
                                    <button type="button" class="button hollow square redirectable" data-redirect-to="<?php echo base_url('home/quotes/projects/' . $projectId . '/levels/' . $level . '/rooms/' . encryptDecrypt($room['room_id']) . '/project-rooms/' . encryptDecrypt($room['project_room_id']) . '/accessory-products') ?>" data-quantity="oplus" data-field="quantity">
                                        <i class="fa fa-plus" aria-hidden="true"></i>
                                    </button>
                                </div>
                                <?php } ?>
                            </div>
                        </td>
                        <td class="op-semibold text-center">
                            <a href="<?php echo base_url('home/quotes/projects/' . $projectId . '/levels/' . $level . '/rooms/' . encryptDecrypt($room['room_id']) . '/project-rooms/' . encryptDecrypt($room['project_room_id']) . '/selected-products') ?>" class="tb-view-list" title="View List">View List</a>
                        </td>
                        <td class="op-semibold text-center">
                        <?php if (
                            (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && empty($quotationRequest)) ||
                            (in_array((int)$userInfo['user_type'], [INSTALLER], true) && !(bool)$hasAddedFinalPrice) ||
                            (in_array((int)$userInfo['user_type'], [WHOLESALER, ELECTRICAL_PLANNER], true))
                        ) { ?>
                            <a href="<?php echo base_url("/home/projects/{$projectId}/levels/{$level}/rooms/" . encryptDecrypt($room['project_room_id']) . "/edit") ?>" class="project-action" title="Edit"><i class="fa fa-pencil"></i></a>
                        <?php } else { ?>
                            <div class="text-center">--</div>
                        <?php } ?>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>

            
        </div>
        <?php } ?>
       
        <?php if (empty($rooms)) : ?>
        <div class="no-record text-center">
            <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg"); ?>" alt="Note Paper">
            <?php if ( ((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && (bool)$userInfo['is_owner'] )) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $permission['project_edit']==1)))) { ?>
                <p>You have no room.</p>
            <p>Tap on <a href="<?php echo base_url("home/projects/" . $projectId . "/levels/{$level}/rooms/applications"); ?>" class="page-link">Add Room</a> button to add a room.</p>
            <?php } ?>
        </div>
        <?php endif ?>
        <div class="pagination-wrap">
            <?php echo $links ?>
        </div>
        <!-- //Project list table -->

        <!-- Caption before section -->
        <?php if (!empty($rooms)) { ?>
            <div class="section-title clearfix">
                <div class="button-wrapper">
                    <?php if (!empty($rooms)) {?>
                        <button type="button" title="<?php echo !empty($rooms)?$this->lang->line("Evaluate_btn_txt"):"" ?>" class="custom-btn btn-margin btn-width save redirectable" id="evaluate" data-redirect-to="<?php echo base_url('home/projects/' . $projectId . '/levels/'. $level .'/rooms/results') ?>" >
                        <?php echo $this->lang->line("Evaluate_btn_txt") ?>
                    </button>
                <?php } ?>
                <?php if (is_numeric($levelCheck['status']) && (int)$levelCheck['status'] === 0) { ?>
                <button type="button" <?php echo empty($rooms)?"disabled":"" ?> title="<?php echo empty($rooms)?$this->lang->line("add_rooms_to_mark_as_done"):"" ?>" data-level-data='<?php echo $levelData ?>' data-redirect-to="<?php echo base_url('/home/projects/' . $projectId . '/levels') ?>" class="custom-btn btn-margin btn-width save" id="mark-as-done-btn">
                    Mark As Done
                </button>
                <?php } ?>
            </div>
        </div>
        <?php } ?>
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
                    <h4 class="modal-title"><?php echo $this->lang->line('add_price') ?></h4>
                </div>
            </div>
            <div class="modal-body">
                <?php echo form_open(base_url(uri_string()), ['id' => 'add-price-form']) ?>
                <div class="row form-inline-wrapper">
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Main Product Price</div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">0.00</div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Accessory Product Price</div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8">0.00</div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt"><?php echo $this->lang->line('price_per_luminaries') ?></label>
                            <div class="form-group-field">
                                <input name="price_per_luminaries" id="price-per-luminaries" type="number" placeholder="10.00" maxlength="12">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt"><?php echo $this->lang->line('installation_charges') ?></label>
                            <div class="form-group-field">
                                <input type="number" name="installation_charges" id="installation-charges" placeholder="10.00" maxlength="12">
                            </div>
                        </div>
                    </div>
                    <div class="clearfix"></div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <label class="labelTxt"><?php echo $this->lang->line('discount_price') ?></label>
                            <div class="form-group-field">
                                <input type="number" name="discount_price" id="discount-price" placeholder="10.00" maxlength="12">
                            </div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Subtotal</div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="subtotal"></div>
                        </div>
                    </div>
                    <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                        <div class="form-group">
                            <div class="col-lg-4 col-md-4 col-sm-4 col-xs-4">Total</div>
                            <div class="col-lg-8 col-md-8 col-sm-8 col-xs-8" id="total"></div>
                        </div>
                    </div>
                    <input type="hidden" name="project_room_id" id="project-room-id" value="">;
                    <span id="target-handler" data-target=""></span>
                </div>
                <?php echo form_close() ?>
            </div>
            <div class="modal-footer">
                <div class="text-center button-wrapper">
                    <button type="button" class="custom-btn btn-margin btn-width save" data-csrf='<?php echo $csrf ?>' data-text="<?php echo $this->lang->line('add_price') ?>" id="add-price-submit"><?php echo $this->lang->line('add_price') ?></button>
                </div>
            </div>
        </div>

    </div>
</div>
