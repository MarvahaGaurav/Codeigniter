<?php 
$filterArr = $this->input->get();
$filterArr = (object) $filterArr;
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
$module = $this->router->fetch_module();
?>
<link href="<?php echo base_url()?>public/css/datepicker.min.css" rel='stylesheet'>
<input type="hidden" id="filterVal" value='<?php echo json_encode($filterArr); ?>'>
<input type="hidden" name="csrf_token" id="csrf" value="<?php echo $csrfToken ?>">
<input type="hidden" id="pageUrl" value='<?php echo base_url().$module.'/'.strtolower($controller).'/'.$method; ?>'>
<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Template Management</li>
        </ol>
    </div>

    <!--Filter Section -->
    <div class="section filter-section clearfix">
        <div class="row">
            <div class="col-lg-2 col-sm-3">
                <div class="display col-sm-space">
                    <select class="selectpicker dispLimit">
                        <option <?php echo ($limit == 10)?'Selected':'' ?> value="10">Display 10</option>
                        <option <?php echo ($limit == 20)?'Selected':'' ?> value="20">Display 20</option>
                        <option <?php echo ($limit == 50)?'Selected':'' ?> value="50">Display 50</option>
                        <option <?php echo ($limit == 100)?'Selected':'' ?> value="100">Display 100</option>
                    </select>
                </div>
            </div>

            <div class="col-lg-6 col-sm-6">
                <div class="srch-wrap fawe-icon-position col-sm-space">
                    <span class="fawe-icon fawe-icon-position-left search-ico"><i class="fa fa-search"></i></span>
                    <?php if(isset($searchlike) && !empty($searchlike)){?>
                    <span class="fawe-icon fawe-icon-position-right show-close-ico" onclick="jQuery('.searchCloseBtn').trigger('click');"><i class="fa fa-times-circle"></i></span>
                    <?php }else{ ?>
                    <span class="fawe-icon fawe-icon-position-right close-ico"><i class="fa fa-times-circle"></i></span>
                    <?php } ?>
                    <input type="text" maxlength="50" value="<?php echo (isset($searchlike) && !empty($searchlike))? $searchlike:''?>" <?php if(isset($searchlike) && !empty($searchlike)){ echo 'readonly';}?> class="search-box <?php if(isset($searchlike) && !empty($searchlike)){ echo 'searchCloseBtn'; }else{  echo 'searchlike'; }?>" placeholder="Search by title" id="searchuser" name="search">
                </div>
            </div>

            <div class="col-lg-4 col-sm-3">
                <div class="circle-btn-wrap col-sm-space">
                    <a href="javascript:void(0)" id="filter-side-wrapper" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                            <?php if(!empty($status) || !empty($user_type) || !empty($startDate) || !empty($endDate) || !empty($country)){ ?>
                            <i class="fa fa-close" aria-hidden="true"></i>
                            <?php } ?>
                        </div>
                        <span class="tooltiptext">Filter</span>
                    </a>
                    <?php /* if($accesspermission['addp']) { */ ?>
                    <a href="admin/templates/add" title="Add" id="filter-side-wrapper" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-plus"></i>
                        </div>
                        <span class="tooltiptext">Create Room Template</span>
                    </a>
                    <?php /* } */ ?>
                    <!-- <a href="javascript:void(0)" title="File Export" class="icon_filter exportCsv">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                        </div>
                        <span class="tooltiptext">Filter</span>
                        <img src="<?php //echo base_url()?>public/adminpanel/images/export-file.svg">
                    </a> -->
                </div>
            </div>
        </div>
    </div>
    <!--Filter Section Close-->

    <!--Filter Wrapper-->
    <div class="filter-wrap ">
        <div class="filter_hd clearfix">
            <div class="pull-left">
                <h2 class="fltr-heading">Filter</h2>
            </div>
            <div class="pull-right">
                <span class="close flt_cl" data-dismiss="modal">X</span>
            </div>
        </div>
        <div class="inner-filter-wrap">
           
            <div class="fltr-field-wrap">
                <label class="admin-label">Status</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option value="">All</option>
                        <option <?php echo ($status == ACTIVE)?'selected':'' ?> value="1">Active</option>
                        <option <?php echo ($status == BLOCKED)?'selected':'' ?> value="2">Inactive</option>
                     </select>
                    
                </div>
            </div>
            <div class="fltr-field-wrap">
                <label class="admin-label">User Type</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter user_type" name="user_type">
                        <option value="">All</option>
                        
                        <option value="individual_user" <?php echo $user_type == "individual_user" ? "selected":"" ?>>Individual User</option>
                        <option value="business_user" <?php echo $user_type == "business_user" ? "selected":"" ?>>Business User</option>
                     </select>
                    
                </div>
            </div>
            <div class="fltr-field-wrap">
                <label class="admin-label">Registration Date</label>
                <div class="inputfield-wrap">
                    <input type="text" name="startDate" data-provide="datepicker" value="<?php echo isset($startDate)?$startDate:""?>" class="startDate" id="startDate" placeholder="From">
                    <label class="ficon ficon-right" for="startDate"><i class="fa fa-calendar"></i></label>
                </div>
                <div class="inputfield-wrap">
                    <input type="text" name="endDate" data-provide="datepicker" value="<?php echo isset($endDate)?$endDate:""?>" class="endDate" id="endDate" placeholder="To">
                    <label class="ficon ficon-right" for="endDate"><i class="fa fa-calendar"></i></label>
                </div>
               
            </div>

            <?php
                $this->load->helper('state_helper');
                $countries = get_country_list();
                
            ?>
            <div class="fltr-field-wrap">
                <label class="admin-label">Country</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter country" name="country" data-live-search="true">
                    <option value="">Select Country</option>
                    <?php if(!empty($countries)){
                        foreach($countries as $key=>$val){
                    ?>
                    <option <?php if(isset($country) && $country == $val['country_code1']){ echo "selected='selected'";}?> value="<?php echo $val['country_code1'];?>"><?php echo $val['name'];?></option>
                       
                    <?php }} ?>
                    </select>
                </div>
            </div>

            <div class="button-wrap text-center">
                <button type="reset" class="commn-btn cancel resetfilter" id="resetbutton">Reset</button>
                <input type="submit" class="commn-btn save applyFilterUser" id="filterbutton"name="filter" value="Apply">
            </div>
            
        </div>
    </div>
    <!--Filter Wrapper Close-->

    <label id="error">
        <?php $alertMsg = $this->session->flashdata('alertMsg'); ?>
        <div class="alert alert-success" <?php echo (!(isset($alertMsg) && !empty($alertMsg)))?"style='display:none'":"" ?> role="alert">
            <button aria-hidden="true" data-dismiss="alert" class="close" type="button">x</button>
            <strong>
                <span class="alertType"><?php echo (isset($alertMsg) && !empty($alertMsg))?$alertMsg['type']:"" ?></span>
            </strong>
            <span class="alertText"><?php echo (isset($alertMsg) && !empty($alertMsg))?$alertMsg['text']:"" ?></span>
        </div>
    </label>

    <div class="section">
        <p class="tt-count">Total Users: <?php echo $totalrows ?></p>
        <!--table-->
        <div class="table-responsive table-wrapper">
            <table cellspacing="0" id="example" class="table-custom sortable">
                <thead>
                    <tr>
                       <th>S.No.</th>
                       <th>Room Type</th>
                       <th>Room Dimensions</th>
                       <th>Workplane Height</th>
                       <th>Room Shape</th>
                       <th>LUX Value</th>
                       <th>Added On</th>
                    <?php if($accesspermission['deletep'] || $accesspermission['blockp']){ ?>
                        <th>Action</th>
                    <?php } ?>
                    </tr>
                </thead>
                <tbody id="table_tr">
                    
                <?php if(isset($templates) && count($templates)){ ?>
                    <?php foreach( $templates as $key => $template) { 

                    ?>
                    <tr>
                        <td><?php echo $sno_start ?></td>
                        <td><?php echo $template['room_type'] ?></td>
                        <td><?php echo $template['dimensions'] ?></td>
                        <td><?php echo $template['workplane_height'].$template['workplane_height_unit'] ?></td>
                        <td><?php echo $template['room_shape'] ?></td>
                        <td><?php echo $template['lux_value'] ?></td>
                        <td><?php echo $template['created_at'] ?></td>
                        <?php if($accesspermission['viewp'] || $accesspermission['deletep'] || $accesspermission['blockp'] || $accesspermission['editp']){ ?>
                        <td class="text-nowrap table-action">
                            <?php if($accesspermission['editp']){ ?>
                                <a class="f-delete" href="<?php echo base_url("admin/templates/{$template['template_id']}") ?>"><i class="fa fa-eye" title="Edit Detail" aria-hidden="true"></i></a>    
                            <?php }?>
                            <?php if($accesspermission['editp']){ ?>
                            <a class="f-pencil" href="<?php echo base_url("admin/templates/{$template['template_id']}/edit") ?>"><i class="fa fa-pencil" title="Edit Detail" aria-hidden="true"></i></a>
                            <?php }?>
                            <?php if($accesspermission['blockp']){ ?>
                                <?php if($value['status'] == BLOCKED){?>
                                    <a class="f-unblock" href="javascript:void(0);" id ="unblock_<?php echo $value['user_id'];?>"><i class="fa fa-unlock" title="unblock" aria-hidden="true" onclick="blockUser('user',<?php echo ACTIVE;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to unblock this user?','Unblock');"></i></a>
                                    <a class="f-block" href="javascript:void(0);"  id ="block_<?php echo $value['user_id'];?>" style="display:none;"><i class="fa fa-ban" title="block" aria-hidden="true" onclick="blockUser('user',<?php echo BLOCKED;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to block this user?','Block');"></i></a>
                                <?php }else{?>
                                    <a class="f-block" href="javascript:void(0);" id ="block_<?php echo $value['user_id'];?>"><i class="fa fa-ban" title="block" aria-hidden="true" onclick="blockUser('user',<?php echo BLOCKED;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to block this user?','Block');"></i></a>
                                    <a class="f-unblock" href="javascript:void(0);" id ="unblock_<?php echo $value['user_id'];?>" style="display:none;"><i class="fa fa-unlock" title="unblock" aria-hidden="true" onclick="blockUser('user',<?php echo ACTIVE;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to unblock this user?','Unblock');"></i></a>
                                <?php }?>
                            <?php }?>
                            <?php if($accesspermission['deletep']){ ?>
                                <a class="f-delete" href="javascript:void(0);"><i class="fa fa-trash" title="Delete" aria-hidden="true" onclick="deleteUser('user',<?php echo DELETED;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to delete this user?');"></i></a>
                            <?php } ?>
                        </td>
                        <?php } ?>
                    </tr>
                <?php 
                    $sno_start++;
                } } else { ?>
                    <tr><td colspan="9">No result found.</td></tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination_wrap clearfix">
            <?php echo $link;?>
        </div>
        
    </div>
</div>
<link rel="stylesheet" href="<?php echo base_url()?>public/css/bootstrap-datetimepicker.css">
<script src="<?php echo base_url()?>public/js/moment-with-locales.js"></script>
<script src="<?php echo base_url()?>public/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url()?>public/js/custom-dashboard.js"></script>
<script>
$(function () {
   $('#startDate,#endDate').datetimepicker({
       viewMode: 'days',
       //format: 'DD-MM-YYYY',
       format: 'DD/MM/YYYY',
       useCurrent: true,
       maxDate: moment()
   });
   $('#startDate').datetimepicker().on('dp.change', function (e) {
       var incrementDay = moment(new Date(e.date));
       incrementDay.add(1, 'days');
       $('#endDate').data('DateTimePicker').minDate(incrementDay);
       $(this).data("DateTimePicker").hide();
   });

   $('#endDate').datetimepicker().on('dp.change', function (e) {
       var decrementDay = moment(new Date(e.date));
       decrementDay.subtract(1, 'days');
       $('#startDate').data('DateTimePicker').maxDate(decrementDay);
       $(this).data("DateTimePicker").hide();
   });
   
});
</script>