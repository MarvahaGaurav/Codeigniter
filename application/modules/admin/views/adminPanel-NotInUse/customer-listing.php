<!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        
        <li class="breadcrumb-item">Users</li>
    </ol>
</div>
<div class="inner-right-panel">
    <!--Filter Section -->
    
        <div class="fltr-srch-wrap clearfix">
            <div class="row">
                <div class="col-lg-2 col-sm-2">
                    <div class="display">
                        
                        <input type="hidden" value="<?php echo $csrfToken; ?>" name="csrf" id="csrf">
                        
                        <form method="GET" id ="page_count_form">
                        <select class="selectpicker" name="pagecount" onchange="pageCountForm();">
                            <?php for($i=10;$i<=100;$i=$i+30){?>
                            <option  <?php if(isset($limit) && $limit  == $i ) { echo "selected='selected'"; 
                           }?> value="<?php echo $i?>">Display <?php echo $i?></option>
                            <?php } ?>
                        </select>
                        </form>
                    </div>
                </div>
                <div class="col-lg-4 col-sm-4">
                    <div class="srch-wrap">
                        <form method="GET" id="admin_search_form">
                            
                            <!--<span class="search-icon"></span>-->
                            <button class="srch" type="submit">
                                <span class="search-icon"></span>
                                
                            </button>
                            <a href="<?php echo base_url().'admin/users'?>"> <span class="srch-close-icon"></span></a>
                            <input type="text" value="<?php echo (isset($searchdata) && !empty($searchdata))? $searchdata:''?>" class="search-box" placeholder="Search by name,email" id="searchuser" name="search">
                        </form>
                    </div>
                </div>

                <div class="col-lg-6 col-sm-6">
                    <div class="top-opt-wrap text-right">
                        <ul>
                            <li>
                                <a href="javascript:void(0)" title="Filter" id="filter-side-wrapper" class="icon_filter"><img src="<?php echo base_url()?>public/adminpanel/images/filter.svg"></a>
                            </li>
                            <!-- <li>
                                <a href="javascripit:void(0);" title="Add Merchant" class="icon_filter"><img src="images/add.svg"></a>
                            </li> -->
                            <li>
                                <a href="javascript:void(0)" title="File Export" class="icon_filter"><img src="<?php echo base_url()?>public/adminpanel/images/export-file.svg"> </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    <!--Filter Section Close-->
    <!--Filter Wrapper-->
    <div class="filter-wrap ">
        <div class="filter_hd clearfix">
            <div class="pull-left">
                <h2 class="fltr-heading">Filter </h2>
            </div>
            <div class="pull-right">
                <span class="close flt_cl" data-dismiss="modal">X</span>
            </div>
        </div>
        <div class="inner-filter-wrap">
            
            <form action="" method="get" id="fileter_form">
            
            <div class="fltr-field-wrap">
                <label class="admin-label">Status</label>
                <div class="commn-select-wrap">
                    
                    <select class="selectpicker filter" name="status">
                        <option value="">Select</option>
                        <option <?php if(isset($status) && $status==ACTIVE) { echo "selected='selected'";
                       }?> value="<?php echo ACTIVE;?>">Active</option>
                        <option <?php if(isset($status) && $status==INACTIVE && $status!='') { echo "selected='selected'";
                       }?> value="<?php echo INACTIVE;?>">Inactive</option>
                     </select>
                    
                </div>

            </div>
            <div class="fltr-field-wrap">
                <label class="admin-label">Registration Date</label>
                <div class="inputfield-wrap">
                    <input type="text" name="from_date" value="<?php echo isset($from_date)?$from_date:""?>" class="form-date_wrap filtertxt" id="datepicker_1" placeholder="From">
                    <input type="text" name="to_date" value="<?php echo isset($to_date)?$to_date:""?>" class="form-date_wrap filtertxt" id="datepicker_2" placeholder="To">
                </div>

            </div>
            
            <?php
                
                $this->load->helper('state_helper');
                $countries = get_country_list();
               
            ?>
            <div class="fltr-field-wrap">
                <label class="admin-label">Country</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter" name="country" onchange="getStates(this.value,'state');" data-live-search="true">
                    <option value="">Select Country</option>
                    <?php if(!empty($countries)) {
                        foreach($countries as $key=>$val){
                    ?>
                    <option <?php if(isset($country) && $country==$val['id']) { echo "selected='selected'";
                   }?> value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                        
                        <?php }
                    } ?>
           </select>
                </div>

            </div>
            
            <div class="fltr-field-wrap">
                <label class="admin-label">State</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter" id="state" name="state" data-live-search="true" onchange="getCities(this.value,'city');">
                        <option value="">Select State</option>
                        <?php if(isset($states) && !empty($states)) {
                            foreach($states as $key=>$val){
                            ?>
                        
                        <option <?php if(isset($state) && $state==$val['id']) { echo "selected='selected'";
                       }?> value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>

            </div>
            <div class="fltr-field-wrap">
                <label class="admin-label">City</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter" id="city" name="city" data-live-search="true">
                        <option value="">Select City</option>
                        <?php if(isset($cities) && !empty($cities)) {
                            foreach($cities as $key=>$val){
                            ?>
                        
                        <option <?php if(isset($city) && $city==$val['id']) { echo "selected='selected'";
                       }?> value="<?php echo $val['id'];?>"><?php echo $val['name'];?></option>
                            <?php }
                        }
                        ?>
                    </select>
                </div>

            </div>

            <div class="button-wrap text-center">
                <button type="reset" class="commn-btn cancel" id="resetbtn" onclick="window.location.href='<?php echo base_url()?>admin/users'">Reset</button>
                <button type="submit" class="commn-btn save" id="filterbtn"name="filter">Apply</button>
            </div>
            
            </form>
        </div>
    </div>
    <!--Filter Wrapper Close-->

    <!--Table-->
    <label id="error"></label>
    <div class="table-responsive custom-tbl">
        <!--table div-->
        <table id="example" class="list-table table table-striped sortable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="50px">S.No</th>
                    <th>
                        <a href="#" class="sort sorting">Name</a>
                    </th>
                    <th>Email Id</th>
                    <th>Mobile Number</th>
                    <th>Address</th>
                    <th>Registered On</th>
                    <th>Total Order</th>
                    <th>status</th>
                    <th width="100px">Action</th>
                </tr>
            </thead>
            <tbody id="table_tr">
                
            <?php if(isset($userlist['result']) && count($userlist['result'])>0) :
                    
                foreach($userlist['result'] as $key =>$value):
                    
            ?>
                
            <tr id ="remove_<?php echo $value['user_id'];?>" >
                <td><?php echo ++$key; ?></td>
                <td><a href="<?php echo base_url()?>admin/users/detail?id=<?php echo $this->Common_model->mcrypt_data($value['user_id'])?>"><?php echo ucfirst($value['first_name']).' '.ucfirst($value['last_name']); ?></a></td>
                <td><?php echo $value['email'];?></td>
                <td><?php echo $value['phone'];?></td>
                <td><?php echo $value['address'];?></td>
                <td><?php echo date("d M Y H:i A", strtotime($value['registered_date']));?></td>
                <td>12</td>
                <td id ="status_<?php echo $value['user_id'];?>"><?php echo ($value['status']==ACTIVE)?"Active":(($value['status']==INACTIVE)?"Inactive":"Blocked");?></td>
                <td> 
                    <?php if($value['status']==BLOCKED) {?>
                        <a href="javascript:void(0);" id ="unblock_<?php echo $value['user_id'];?>" class="table_icon"><i class="fa fa-unlock" aria-hidden="true" onclick="blockUser('user',<?php echo ACTIVE;?>,'<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($value['user_id']), true);?>','req/change-user-status','Do you really want to unblock this user?','Unblock');"></i></a>
                        <a href="javascript:void(0);"  id ="block_<?php echo $value['user_id'];?>" style="display:none;" class="table_icon"><i class="fa fa-ban" aria-hidden="true" onclick="blockUser('user',<?php echo BLOCKED;?>,'<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($value['user_id']), true);?>','req/change-user-status','Do you really want to block this user?','Block');"></i></a>
                    <?php }else{?>
                        <a href="javascript:void(0);" id ="block_<?php echo $value['user_id'];?>" class="table_icon"><i class="fa fa-ban" aria-hidden="true" onclick="blockUser('user',<?php echo BLOCKED;?>,'<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($value['user_id']), true);?>','req/change-user-status','Do you really want to block this user?','Block');"></i></a>
                        <a href="javascript:void(0);" id ="unblock_<?php echo $value['user_id'];?>" style="display:none;" class="table_icon"><i class="fa fa-unlock" aria-hidden="true" onclick="blockUser('user',<?php echo ACTIVE;?>,'<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($value['user_id']), true);?>','req/change-user-status','Do you really want to unblock this user?','Unblock');"></i></a>
                    <?php }?>
                        
                        
                    <a href="javascript:void(0);" class="table_icon"><i class="fa fa-trash" aria-hidden="true" onclick="deleteUser('user',<?php echo DELETED;?>,'<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($value['user_id']), true);?>','req/change-user-status','Do you really want to delete this user?');"></i></a>
                       
                </td>
            </tr>
                
            <?php 
                endforeach;
                else:
                    echo '<tr><td colspan="9">No result found.</td></tr>';
                endif;?>
                

            </tbody>
        </table>
    </div>
     <div class="pagination_wrap clearfix">
                        <?php echo $link;?>
                    </div>
