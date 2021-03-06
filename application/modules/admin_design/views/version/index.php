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
<input type="hidden" value="<?php echo $csrfToken; ?>" name="csrf" id="csrf">
<div class="inner-right-panel">
    <!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Manage Version</a></li>

    </ol>
</div>
<!--breadcrumb wrap close-->
    <!--Filter Section -->
        <div class="fltr-srch-wrap clearfix white-wrapper">
            <div class="row">

                <div class="col-lg-4 col-sm-4">
                    <form action="">
                        <div class="srch-wrap">
                            <button class="srch search-icon" style="cursor:default"></button>
                            <a href="javascript:void(0)"> <span class="srch-close-icon searchCloseBtn"></span></a>
                            <input type="text" value="<?php echo (isset($searchlike) && !empty($searchlike))? $searchlike:''?>" class="search-box searchlike" placeholder="Search by version name" id="searchuser" name="search">
                        </div>
                    </form>
                </div>
                <?php if($accesspermission['addp']) {?>
                    <div class="col-lg-8 col-sm-8">
                        <div class="top-opt-wrap text-right">
                            <ul>
                                <li>
                                    <a href="admin/version/add" title="Add Content" class="icon_filter"><img src="<?php echo base_url()?>public/adminpanel/images/add.svg"> </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                <?php } ?>
            </div>
        </div>

    <!--Filter Section Close-->
    <div class="row">
        <div class="col-lg-6">Total Content: <?php echo $totalrows ?></div>
    </div>
    <!--Table-->
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
     <div class="clearfix white-wrapper">
    <div class="table-responsive custom-tbl">
        <!--table div-->
        <table id="example" class="list-table table table-striped sortable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="50px">S.No</th>
                    <th>Version Name</th>
                    <th>Title</th>
                    <th>Description</th>
                    <th>Platform</th>
                    <th>Update Type</th>
                    <th>Is Current Version</th>
                    <th>Created Date</th>
                    <?php if($accesspermission['deletep'] || $accesspermission['editp']) { ?>
                            <th width="100px">Action</th>
                    <?php } ?>
                </tr>

            </thead>
            <tbody id="table_tr">
                <?php if(isset($versions['result']) && count($versions['result'])>0) :
                    foreach($versions['result'] as $key =>$value): ?>

                    <td><?php echo ++$key; ?></td>
                    <td><?php echo $value['version_name']; ?></td>
                    <td><?php echo $value['versiob_title'];?></td>
                    <td><?php if (!empty($value['version_desc'])) {echo substr($value['version_desc'], 0, 155); if(strlen($value['version_desc']) > 154) {echo '...';
                   } 
                   } ?></td>
                    <td><?php echo ($value['platform']==ANDROID)?"Andorid":"Iphone"; ?></td>
                    <td><?php echo ($value['update_type']==NORMAL)?"Normal":(($value['update_type']==SKIPPABLE)?"Skippable":"Forcefully"); ?></td>
                    <td><?php echo ($value['is_cur_version']==YES)?"Yes":"No"; ?></td>
                    <td><?php echo date('d m Y H:i a', strtotime($value['create_date']));?></td>
                    <?php if($accesspermission['deletep'] || $accesspermission['editp']) { ?>
                    <td>
                        <?php if($accesspermission['editp']) {?>
                            <a class="table_icon" href="<?php echo base_url()?>admin/version/edit?id=<?php echo encryptDecrypt($value['vid']);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <?php } ?>
                        <?php if($accesspermission['deletep']) {?>    
                            <a href="javascript:void(0);" class="table_icon"><i class="fa fa-trash" aria-hidden="true" onclick="deleteUser('version',<?php echo DELETED;?>,'<?php echo encryptDecrypt($value['vid']); ?>','req/change-user-status','Do you really want to delete this version?');"></i></a>
                        <?php } ?>
                    </td>
                    <?php } ?>
                </tr>
            <?php
                    endforeach;
              else:
                    echo '<tr><td colspan="9">No result found.</td></tr>';
              endif;?>
            </tbody>
        </table>
    </div>
            </div>
    <!-- table 1 close-->
</div>
<!--Table listing-->
