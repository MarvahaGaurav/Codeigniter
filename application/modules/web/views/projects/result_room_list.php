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
                            <div class="d-flex">
                              <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg")?>"  />
                                <span class="td-room-type op-semibold"><?php echo strlen($room['reference_name'])>0?$room['reference_name']:$room['name'] ?></span>
                            </div>
                        </td>
                        <td><?php echo "{$room['length']}M x {$room['width']}M x {$room['height']}M" ?></td>
                        <td class="text-center"><?php echo $room['count'] ?> <?php echo (int)$room['count'] > 1?$this->lang->line('room_count_sets_txt'):$this->lang->line('room_count_set_txt') ?></td>
                        <td class="text-center"><?php echo count($room['products']) - 1 ?></td>
                        <?php if (((in_array((int)$userInfo['user_type'], [INSTALLER], true) && $userInfo['is_owner']==ROLE_OWNER) || (in_array((int)$userInfo['user_type'], [INSTALLER], true) && $userInfo['is_owner']==ROLE_EMPLOYEE && $permission['project_add']==1))) { ?>
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
                            <a href="<?php echo base_url('home/projects/' . $projectId . '/levels/' . $level . '/rooms/' . encryptDecrypt($room['project_room_id']) . '/tco') ?>" class="tb-view-list" title="<?php echo $this->lang->line('tco_txt') ?>"><?php echo $this->lang->line('tco_txt') ?></a>
                        </td>
                        <?php } ?>
                        <td class="op-semibold text-center">
                        <a href="<?php echo base_url("/home/projects/view-result/" . encryptDecrypt($room['project_room_id'])) ?>" class="tb-view-list" title="View Results">View Results</a>
                        </td>
                    </tr>
                    <?php endforeach ?>
                </tbody>
                <?php if (((in_array((int)$userInfo['user_type'], [INSTALLER], true) && $userInfo['is_owner']==ROLE_OWNER) || (in_array((int)$userInfo['user_type'], [INSTALLER], true) && $userInfo['is_owner']==ROLE_EMPLOYEE && $permission['project_add']==1))) { ?>
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
        <?php if (empty($rooms)) : ?>
        <div class="no-record text-center">
            <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg"); ?>" alt="Note Paper">
            <?php if (((in_array((int)$userInfo['user_type'], [INSTALLER], true) && $userInfo['is_owner']==ROLE_OWNER) || (in_array((int)$userInfo['user_type'], [INSTALLER], true) && $userInfo['is_owner']==ROLE_EMPLOYEE && $permission['project_add']==1))) { ?>
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
                    <button type="button" class="custom-btn btn-margin btn-width save modal-price-text" data-csrf='<?php echo $csrf ?>' data-text="" id="add-price-submit"></button>
                </div>
            </div>
        </div>

    </div>
</div>
