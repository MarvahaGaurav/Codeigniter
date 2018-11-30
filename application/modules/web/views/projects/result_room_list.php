<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId) ?>">Details</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels') ?>">Levels</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId . '/levels/' . $level .'/rooms') ?>">Rooms</a></li>
            <li class="active">Room Results</li>
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
                            <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg")?>"  />
                            <span class="td-room-type op-semibold"><?php echo strlen($room['reference_name'])>0?$room['reference_name']:$room['name'] ?></span>
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
                        <a href="<?php echo base_url("/home/projects/view-result/" . encryptDecrypt($room['project_room_id'])) ?>" class="tb-view-list" title="View Results">View Results</a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
            </table>
            <div class="price-container col-lg-12 col-md-12 col-sm-12 col-xs-12">
                <div class="price-wrapper col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="price-group col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <label for="" class="price-label"><?php echo $this->lang->line('price_per_luminaries') ?></label>
                        <input type="text" class="price-fields"value="<?php echo isset($projectRoomPrice['price_per_luminaries'])?$projectRoomPrice['price_per_luminaries']:'' ?>" disabled>
                    </div>
                    <div class="price-group col-lg-1 col-md-1 col-sm-1 col-xs-6">
                        <label for="" class="price-label"></label>
                        <label for="" class="price-label">+</label>
                    </div>
                    <div class="price-group col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <label for="" class="price-label"><?php echo $this->lang->line('installation_charges') ?></label>
                        <input type="text" class="price-fields"value="<?php echo isset($projectRoomPrice['installation_charges'])?$projectRoomPrice['installation_charges']:'' ?>" disabled>
                    </div>
                    <div class="price-group col-lg-1 col-md-1 col-sm-1 col-xs-6">
                        <label for="" class="price-label"></label>
                        <label for="" class="price-label">-</label>
                    </div>
                    <div class="price-group col-lg-2 col-md-2 col-sm-2 col-xs-6">
                        <label for="" class="price-label"><?php echo $this->lang->line('discount_price') ?>(%)</label>
                        <input type="text" class="price-fields"value="<?php echo isset($projectRoomPrice['discount_price'])?$projectRoomPrice['discount_price']:'' ?>" disabled>
                    </div>
                    <div class="price-group col-lg-2 col-md-2 col-sm-3 col-xs-6 pull-right">
                        <label for="" class="price-label">Total</label>
                        <label for="" class="price-label"><?php  echo isset($projectRoomPrice['total'])?$projectRoomPrice['total']:'' ?></label>
                    </div>
                </div>
            </div>
        </div>
        <?php if (empty($rooms)) : ?>
        <div class="no-record text-center">
            <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg"); ?>" alt="Note Paper">
            <p>You have no room.</p>
            <p>Tap on <a href="<?php echo base_url("home/projects/" . $projectId . "/levels/{$level}/rooms/applications"); ?>" class="page-link">Add Room</a> button to add a room.</p>
        </div>
        <?php endif ?>
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
                    <button type="button" class="custom-btn btn-margin btn-width save modal-price-text" data-csrf='<?php echo $csrf ?>' data-text="" id="add-price-submit"></button>
                </div>
            </div>
        </div>

    </div>
</div>
