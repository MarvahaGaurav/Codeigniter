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
            <li class="breadcrumb-item">Sub-admin</li>
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
                    <a href="admin/subadmin/add" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-plus"></i>
                        </div>
                        <span class="tooltiptext">Create Sub Admin</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--Filter Section Close-->

    
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
    <!-- Content -->
    <input type="hidden" id="filterparams" value='<?php echo json_encode($queryString) ;?>'>

    <div class="col-lg-6 col-sm-6 hide-mobile"></div>
    <div class="clear"></div>
    <!-- Content section -->
    <div class="section">
        <p class="tt-count">Total Sub-admin: <?php echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th style="cursor:pointer">Name<a  href="javascript:void(0);" class="th-icon"><i class=""></i></a></th>
                        <th>Email </th> 
                        <th>Status </th>
                        <th style="cursor:pointer">Added On<a  href="javascript:void(0);" class="th-icon"><i class=""></i></a> </th>

                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $pagecount = 10;
                    $i = (($page * $pagecount) - $pagecount) + 1;
                    ?>
                    <?php if(!empty($data)) {
                        foreach ($data as $key => $data) { ?>
                        <tr>
                            <td><?php echo $i ;?></td>
                            <td align="left">
                                <a href="admin/subadmin/view?id=<?php echo encryptDecrypt($data['admin_id']); ?>">
                                <?php echo $data['admin_name'] ?>
                                </a>
                            </td>
                            <td>
                                <?php echo $data['admin_email']?>
                            </td>
                            <td>
                                <?php echo ($data['status'] == 1)?'Active':'Blocked' ?>
                            </td>
                            <td>
                                <?php echo date('d-M-Y', strtotime($data['create_date'])); ?>
                            </td>
                            <td class="text-nowrap table-action">
                                <a class="f-pencil" href="admin/subadmin/edit?id=<?php echo encryptDecrypt($data['admin_id']);?>"><i class="fa fa-pencil" title="Edit" aria-hidden="true"></i></a>
                                <a class="f-block" href="javascript:void(0);" id="block_<?php echo $data['admin_id'];?>"><i class="fa fa-ban" title="Block" aria-hidden="true" onclick="blockUser('subadmin',2,'<?php echo encryptDecrypt($data['admin_id']);?>','req/change-user-status','Do you really want to block this Sub-admin?','Block');"></i></a>
                                <a class="f-unblock" href="javascript:void(0);" id="unblock_<?php echo $data['admin_id'];?>" style="display:none;"><i class="fa fa-unlock" title="Unblock" aria-hidden="true" onclick="blockUser('subadmin',1,'<?php echo encryptDecrypt($data['admin_id']);?>','req/change-user-status','Do you really want to unblock this Sub-admin?','Unblock');"></i></a>                                       
                                <a class="f-delete" href="javascript:void(0);"><i class="fa fa-trash" title="Delete" aria-hidden="true" onclick="deleteUser('subadmin',3,'<?php echo encryptDecrypt($data['admin_id']);?>','req/change-user-status','Do you really want to delete this Sub-admin?');"></i></a>                       
                            </td>
                        </tr>
                        <?php $i++;}}  else { ?> 
                            <tr>
                                <td colspan="9" class="text-center">No Sub-admin exist</td>
                            </tr>
                        <?php }?>
                </tbody>
            </table>
        </div>   	
        <div class="clear"></div>
        <nav class="pagination-wrapper m-t-md">
            <ul class="pagination">
                <?php
                if (isset($links)) {
                    echo $links;
                }
                ?>
            </ul>
        </nav>     
    </div>
    <!-- Content section End --> 
</div>

<div class="modal fade modelbody bd-example-modal-sm" id="Deletemodal" role="dialog">
    <div class="modal-dialog width" role="document">
        <div class="modal-content">     
            <div class="modal-body">
                Are you sure you want to delete selected Subadmin ?
            </div>
            <div class="modal-footer">
                <div class="col-lg-6 col-sm-6">
                     
                    <button type="button" class="custom-btn cancel" data-dismiss="modal" onclick="window.location.href = '/Subadmin/'">Cancel</button>
                </div>
                <div class="col-lg-6 col-sm-6">
                   
                   <input type="hidden" id="hiddenuser" value="" />
                    <button type="button" class="custom-btn save delete-subadmin">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade modelbody bd-example-modal-sm" id="Blockmodal" role="dialog">
    <div class="modal-dialog width" role="document">
        <div class="modal-content">     
            <div class="modal-body">
                Are you sure you want to block selected Subadmin ?
            </div>
            <div class="modal-footer">
                <div class="col-lg-6 col-sm-6">
                    <button type="button" class="custom-btn cancel" data-dismiss="modal">Cancel</button>
                </div>
                <div class="col-lg-6 col-sm-6">
                      <input type="hidden" id="blockuserid" value="" />
                    <input type="hidden" id="blockuserstatus" value="" />
                    <button type="button" class="custom-btn save block-admin">Yes</button>
                </div>
            </div>
        </div>
    </div>
</div>



