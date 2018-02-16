<?php 
$filterArr = $this->input->get();
$filterArr = (object) $filterArr;
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
$module = $this->router->fetch_module();
?>
<link href="<?php echo base_url()?>public/css/datepicker.min.css" rel='stylesheet'>
<input type="hidden" id="filterVal" value='<?php echo json_encode($filterArr); ?>'>
<input type="hidden" id="pageUrl" value='<?php echo base_url().$module.'/'.strtolower($controller).'/'.$method; ?>'>
<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Merchants Management</li>
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
                    <span class="fawe-icon fawe-icon-position-right close-ico"><i class="fa fa-times-circle"></i></span>
                    <input type="text" maxlength="15" value="<?php echo (isset($searchlike) && !empty($searchlike))? $searchlike:''?>" class="search-box searchlike" placeholder="Search by name, email" id="searchuser" name="search">
                </div>
            </div>

            <div class="col-lg-4 col-sm-3">
                <div class="circle-btn-wrap col-sm-space">
                    <a href="javascript:void(0)" id="filter-side-wrapper" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                        </div>
                        <span class="tooltiptext">Filter</span>
                    </a>
                    <!--<a href="javascript:void(0)" title="" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-plus"></i>
                        </div>
                        <span class="tooltiptext">Add</span>
                    </a>-->
                    <!-- <ul>
                        <li>
                            <a href="javascript:void(0)" title="Filter" id="filter-side-wrapper" class="icon_filter"><img src="<?php //echo base_url()?>public/adminpanel/images/filter.svg"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" title="File Export" class="icon_filter exportCsv"><img src="<?php //echo base_url()?>public/adminpanel/images/export-file.svg"> </a>
                        </li>
                    </ul> -->
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
                <label class="admin-label">Merchant Type</label>
                <div class="commn-select-wrap"> 
                    <select class="selectpicker filter user_type" name="user_type">
                        <option value="">All</option>
                        <option value="technician" <?php echo $user_type == "technician" ? "selected":"" ?>>Technician</option>
                        <option value="architect" <?php echo $user_type == "architect" ? "selected":"" ?>>Architect</option>
                        <option value="electrical_planner" <?php echo $user_type == "electrical_planner" ? "selected":"" ?>>Electrical Planner</option>
                        <option value="wholesaler" <?php echo $user_type == "wholesaler" ? "selected":"" ?>>Wholesaler</option>
                     </select>
                    
                </div>
            </div>
            <div class="fltr-field-wrap">
                <label class="admin-label">Registration Date</label>
                <div class="inputfield-wrap">
                    <input type="text" name="startDate" data-provide="datepicker" value="<?php echo isset($startDate)?$startDate:""?>" class="form-control startDate" id="startDate" placeholder="From">
                 </div>
               
            </div>
            <div class="fltr-field-wrap">
            <div class="inputfield-wrap">
                <input type="text" name="endDate" data-provide="datepicker" value="<?php echo isset($endDate)?$endDate:""?>" class="form-control endDate" id="endDate" placeholder="To">
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

    <!-- table section -->
    <div class="section">
        <p class="tt-count">Total Technicians: <?php echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>
                            <a href="<?php base_url() ?>admin/technician?field=name&order=<?php echo $order_by . $get_query ?>" class="sort sorting">Name</a>
                        </th>
                        <th>Email Id</th>
                        <th>Mobile Number</th>
                        <th>Country</th>
                        <th>
                            <a href="<?php base_url() ?>admin/technician?field=registered&order=<?php echo $order_by . $get_query ?>" class="sort sorting">Registered On</a>
                        </th>
                        <th>Merchant Type</th>
                        <th>User Role</th>
                        <th>status</th>
                        <?php if($accesspermission['deletep'] || $accesspermission['blockp']){ ?>
                            <th>Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody id="table_tr">
                    
                <?php if(isset($userlist) && count($userlist)){
                        if ($page > 1){
                            $i = (($page * $limit)- $limit) + 1;
                        } else {
                            $i = 1;
                        }
                        foreach($userlist as $value){ 
                        //echo '<pre>'; print_r($value); echo '</pre>';
                        ?>
                        
                        <tr id ="remove_<?php echo $value['user_id'];?>" >
                        <td><?php echo $i; ?></td>
                        <td>
                            <?php if($accesspermission['viewp']) { ?>
                            <a href="<?php echo base_url()?>admin/technician/detail?id=<?php echo encryptDecrypt($value['user_id']); ?>"><?php echo ucfirst($value['first_name']).' '.ucfirst($value['last_name']); ?></a>
                            <?php }else{ ?>
                            <?php echo ucfirst($value['first_name']).' '.ucfirst($value['last_name']); ?>
                            <?php } ?>
                        </td>
                        <td><?php echo $value['email'];?></td>
                        <td><?php echo !empty($value['phone'])?$value['phone']:"Not Available";?></td>
                        <td><?php echo !empty($value['name'])?$value['name']:"Not Available";?></td>
                        <td><?php echo date("d M Y H:i A",strtotime($value['registered_date']));?></td>
                        <td><?php echo $value['user_type']?></td>
                        <td><?php if($value['user_type_num'] != 1 ){ echo ($value['is_owner'] == '2')?"Owner":"Employee";} ?></td>
                        <td id ="status_<?php echo $value['user_id'];?>"><?php echo ($value['status'] == ACTIVE)?"Active":"Blocked";?></td>
                        <?php if($accesspermission['deletep'] || $accesspermission['blockp']){ ?>
                        <td class="text-nowrap table-action">
                            <a class="f-delete" href="<?php echo base_url()?>admin/technician/detail?id=<?php echo encryptDecrypt($value['user_id']); ?>"><i class="fa fa-eye" title="View Detail" aria-hidden="true"></i></a>
                            
                            <?php if($accesspermission['blockp']){ ?>
                                <?php if($value['status'] == BLOCKED){?>
                                    <a class="f-unblock" href="javascript:void(0);" id ="unblock_<?php echo $value['user_id'];?>"><i class="fa fa-unlock" title="unblock" aria-hidden="true" onclick="blockUser('user',<?php echo ACTIVE;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to unblock this user?','Unblock');"></i></a>
                                    <a class="f-block" href="javascript:void(0);"  id ="block_<?php echo $value['user_id'];?>" style="display:none;"><i class="fa fa-ban" title="block" aria-hidden="true" onclick="blockUser('user',<?php echo BLOCKED;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to block this user?','Block');"></i></a>
                                <?php }else{?>
                                    <a class="f-block" href="javascript:void(0);" id ="block_<?php echo $value['user_id'];?>"><i class="fa fa-ban" title="block" aria-hidden="true" onclick="blockUser('user',<?php echo BLOCKED;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to block this user?','Block');"></i></a>
                                    <a class="f-ublock" href="javascript:void(0);" id ="unblock_<?php echo $value['user_id'];?>" style="display:none;"><i class="fa fa-unlock" title="unblock" aria-hidden="true" onclick="blockUser('user',<?php echo ACTIVE;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to unblock this user?','Unblock');"></i></a>
                                <?php }?>
                            <?php }?>
                            <?php if($accesspermission['deletep']){ ?>
                                <a class="f-delete" href="javascript:void(0);"><i class="fa fa-trash" title="Delete" aria-hidden="true" onclick="deleteUser('user',<?php echo DELETED;?>,'<?php echo encryptDecrypt($value['user_id']);?>','req/change-user-status','Do you really want to delete this user?');"></i></a>
                            <?php } ?>
                        </td>
                        <?php } ?>
                    </tr>
                    <?php 
                    $i++; 
                    } } else { ?>
                    <tr><td colspan="9">No result found.</td></tr
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <div class="pagination_wrap clearfix">
            <?php echo $link;?>
        </div>
    </div>

</div>
<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="<?php echo base_url()?>public/js/datepicker.min.js"></script>
<script>

    $(document).ready(function(){
        
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        var checkin = $('#startDate').datepicker({
            onRender: function(date) {
                return date.valueOf() > now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() < checkout.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate());
                checkout.setValue(newDate);
            }
            checkin.hide();
            $('#endDate')[0].focus();
        }).data('datepicker');
        var checkout = $('#endDate').datepicker({
            onRender: function(date) {
                return date.valueOf() < checkin.date.valueOf() || date.valueOf() > now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            checkout.hide();
        }).data('datepicker');


        //on datepicker 2 focus
        $('#datepicker_2').focus(function() {
            if ($('#datepicker_1').val() == '') {
                checkout.hide();
            }
        });
        //prevent typing datepicker's input
        $('#datepicker_2, #datepicker_1').keydown(function(e) {
            e.preventDefault();
            return false;
        });

    });
</script>