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
        <li class="breadcrumb-item">Content</li>
    </ol>
</div>
<!--breadcrumb wrap close-->
    <!--Filter Section -->
        <div class="fltr-srch-wrap white-wrapper clearfix">
            <div class="row">
                <div class="col-lg-4 col-sm-4">
                    <div class="srch-wrap">
                        <button class="srch search-icon" style="cursor:default"></button>
                        <a href="javascript:void(0)"> <span class="srch-close-icon searchCloseBtn"></span></a>
                        <input type="text" maxlength="15" value="<?php echo (isset($searchlike) && !empty($searchlike))? $searchlike:''?>" class="search-box searchlike" placeholder="Search by title" id="searchuser" name="search">
                    </div>
                </div>
                <div class="col-lg-8 col-sm-8">
                    <div class="top-opt-wrap text-right">
                        <ul>
                            <li>
                                <a href="<?php echo base_url()?>admin/cms/add" title="Add Content" class="icon_filter"><img src="<?php echo base_url()?>public/adminpanel/images/add.svg"> </a>
                            </li>
                        </ul>
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
    <div class="row">
        <div class="col-lg-6">Total Pages <?php echo $totalrows ?></div>
    </div>
    <div class="white-wrapper">
    <div class="table-responsive custom-tbl">
        <!--table div-->
        <table id="example" class="list-table table table-striped sortable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="50px">S.No</th>
                    <th>Page Title</th>
                    <th>Description</th>
                    <th style="width:13%">Status</th>
                    <th width="100px">Actions</th>
                </tr>

            </thead>
            <tbody id="table_tr">
              <?php if(isset($cmsData) && count($cmsData)>0):
                    foreach($cmsData as $key =>$value):  ?>

                  <tr id ="remove_<?php echo $value['id'];?>">
                    <td><?php echo ++$key; ?></td>
                    <td><?php echo ucfirst($value['name']); ?></td>
                    <td><?php echo substr($value['content'],0,150);?></td>
                    <td><?php echo ($value['status']==ACTIVE)?"Active":"Inactive";?></td>
                    <td>
                        <a class="table_icon" href="<?php echo base_url()?>admin/cms/edit?id=<?php echo encryptDecrypt($value['id']);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:void(0);" class="table_icon"><i class="fa fa-trash" aria-hidden="true" onclick="deleteUser('cms',<?php echo DELETED;?>,'<?php echo encryptDecrypt($value['id']);?>','req/change-user-status','Do you really want to delete this page?');"></i></a>
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