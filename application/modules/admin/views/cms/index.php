<?php 
    $filterArr = $this->input->get();
    $filterArr = (object) $filterArr;
    $controller = $this->router->fetch_class();
    $method = $this->router->fetch_method();
    $module = $this->router->fetch_module();
?>
<input type="hidden" id="filterVal" value='<?php echo json_encode($filterArr); ?>'>
<input type="hidden" id="pageUrl" value='<?php echo base_url().$module.'/'.strtolower($controller).'/'.$method; ?>'>

<input type="hidden" value="<?php echo $csrfToken; ?>" name="csrf" id="csrf">
<div class="inner-right-panel">
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Content Management</li>
        </ol>
    </div>
    <!--breadcrumb wrap close-->

    <!--Filter Section -->
    <div class="section filter-section clearfix">
        <div class="row">

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
            <div class="col-lg-6 col-sm-5">
                <div class="circle-btn-wrap col-sm-space">
                    <a href="admin/cms/add" title="Add" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-plus"></i>
                        </div>
                        <span class="tooltiptext">Add</span>
                    </a>
                    <!-- <ul>
                        <li>
                            <a href="<?php //echo base_url()?>admin/cms/add" title="Add Content" class="icon_filter"><img src="<?php echo base_url()?>public/adminpanel/images/add.svg"> </a>
                        </li>
                    </ul> -->
                </div>
            </div>
        </div>
    </div>
    <!--Filter Section Close-->

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

    <div class="section">
        <p class="tt-count">Total Pages <?php echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <table cellspacing="0" class="table-custom">
            <thead>
                <tr>
                    <th>S.No</th>
                    <th>Page Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th>Actions</th>
                </tr>

            </thead>
            <tbody id="table_tr">
              <?php if(isset($cmsData) && count($cmsData)>0):
                    foreach($cmsData as $key =>$value):  ?>

                  <tr id ="remove_<?php echo $value['id'];?>">
                    <td><?php echo ++$key; ?></td>
                    <td><?php echo ucfirst($value['name']); ?></td>
                    <td style="text-align:left;"><?php echo substr($value['content'],0,150);?></td>
                    <td><?php echo ($value['status']==ACTIVE)?"Active":"Inactive";?></td>
                    <td class="text-nowrap table-action">
                        <a class="f-pencil" href="<?php echo base_url()?>admin/cms/edit?id=<?php echo encryptDecrypt($value['id']);?>"><i class="fa fa-pencil" title="Edit" aria-hidden="true"></i></a>
                        <a class="f-delete" href="javascript:void(0);"><i class="fa fa-trash" title="Delete" aria-hidden="true" onclick="deleteUser('cms',<?php echo DELETED;?>,'<?php echo encryptDecrypt($value['id']);?>','req/change-user-status','Do you really want to delete this page?');"></i></a>
                    </td>
                </tr>
          <?php
              endforeach;
              else:
                  echo '<tr><td colspan="5">No result found.</td></tr>';
              endif;?>
            </tbody>
        </table>
    </div>
     <div class="pagination_wrap clearfix">
        <?php echo $link;?>
     </div>
</div>
    <!-- table 1 close-->

<!--Table listing-->
</div>