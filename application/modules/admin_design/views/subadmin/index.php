<?php 
$filterArr = $this->input->get();
$filterArr = (object) $filterArr;
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
$module = $this->router->fetch_module();
?>
<link href="<?php echo base_url()?>public/css/plugin/datepicker.css" rel='stylesheet'>
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
                    <!-- <button class="srch search-icon" style="cursor:default"></button>
                    <a href="javascript:void(0)"> <span class="srch-close-icon searchCloseBtn"></span></a> -->
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

    <label id="error" style="display:none;">
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
    <input type="hidden" id="filterparams" value='<?php echo json_encode($queryString);?>'>    

    <!-- Table Section -->
    <div class="section">
        <p class="tt-count">Total Users: <?php echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th><a href="javascript:void(0);" class="sort sorting">Name</a></th>
                        <th>Email</th> 
                        <th>Phone Number</th>
                        <th><a href="javascript:void(0);" class="sort sorting">Registered On</a></th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>Adam</td>
                        <td>Adam012@gmail.com</td>
                        <td>9814547890</td>
                        <td>02-01-2018</td>
                        <td class="table-action">
                            <a href="admin/subadmin/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="admin/subadmin/edit" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0);" class="f-unblock" id="unblock_271"><i class="fa fa-unlock" title="Unblock" onclick="blockUser('user',1,'0ghTccf2o1WwvTcXUutMugPer3DPer3DPer3A6265617574796c69766b696e67646f6d','req/change-user-status','Do you really want to unblock this user?','Unblock');"></i></a>
                            <a href="javascript:void(0);" class="f-block" id="block_271" style="display:none;"><i class="fa fa-ban" title="Block" onclick="blockUser('user',2,'0ghTccf2o1WwvTcXUutMugPer3DPer3DPer3A6265617574796c69766b696e67646f6d','req/change-user-status','Do you really want to block this user?','Block');"></i></a>
                            <a href="javascript:void(0);" class="f-trash"><i class="fa fa-trash" title="Delete" onclick="deleteUser('user',3,'0ghTccf2o1WwvTcXUutMugPer3DPer3DPer3A6265617574796c69766b696e67646f6d','req/change-user-status','Do you really want to delete this user?');"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>       
        <div class="clear"></div>
        <nav class="pagination-wrapper">
            <ul class="pagination">
                <?php
                if (isset($links)) {
                    echo $links;
                }
                ?>
            </ul>
        </nav>     
    </div>
    <!-- table section End --> 

</div>

<!-- Delete Modal --> 
<div class="modal fade modelbody bd-example-modal-sm" id="Deletemodal" role="dialog">
    <div class="modal-dialog width" role="document">
        <div class="modal-content">     
            <div class="modal-body">
                Are you sure you want to delete selected Subadmin ?
            </div>
            <div class="modal-btn">   
                <button type="button" class="custom-btn cancel" data-dismiss="modal" onclick="window.location.href = '/Subadmin/'">Cancel</button>
                <input type="hidden" id="hiddenuser" value="" />
                <button type="button" class="custom-btn save delete-subadmin">Yes</button>
            </div>
        </div>
    </div>
</div>

<!-- Block Modal --> 
<div class="modal fade modelbody bd-example-modal-sm" id="Blockmodal" role="dialog">
    <div class="modal-dialog width" role="document">
        <div class="modal-content">     
            <div class="modal-body">
                Are you sure you want to block selected Subadmin ?
            </div>
            <div class="modal-btn">
                <button type="button" class="custom-btn cancel" data-dismiss="modal">Cancel</button>
                <input type="hidden" id="blockuserid" value="" />
                <input type="hidden" id="blockuserstatus" value="" />
                <button type="button" class="custom-btn save block-admin">Yes</button>
            </div>
        </div>
    </div>
</div>



