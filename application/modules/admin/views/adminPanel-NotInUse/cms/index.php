
<input type="hidden" value="<?php echo $csrfToken; ?>" name="csrf" id="csrf">
<div class="inner-right-panel">



    <!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="javascript:void(0);">Content</a></li>
    </ol>
</div>
<!--breadcrumb wrap close-->

    <!--Filter Section -->
    
        <div class="fltr-srch-wrap white-wrapper clearfix">
            <div class="row">

                <div class="col-lg-4 col-sm-4">
                    <form method="GET" id="admin_search_form">
                    <div class="srch-wrap">
                        
                            
                            <!--<span class="search-icon"></span>-->
                            <button class="srch" type="submit">
                                <span class="search-icon"></span>
                                
                            </button>
                            <a href="<?php echo base_url().'admin/cms'?>"> <span class="srch-close-icon"></span></a>
                            <input type="text" value="<?php echo (isset($searchdata) && !empty($searchdata))? $searchdata:''?>" class="search-box" placeholder="Search by name,email" id="searchuser" name="search">
                        
                    </div>
                    </form>
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
    <?php
    if ($this->session->flashdata('message') != '') {

        echo $this->session->flashdata('message');
    }
    ?>
    <div class="white-wrapper">
    <div class="table-responsive custom-tbl">
        <!--table div-->
        <table id="example" class="list-table table table-striped sortable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="50px">S.No</th>
                    <th>Page Title</th>
                    <th>Description</th>
                    <th>Status</th>
                    <th width="100px">Actions</th>
                </tr>

            </thead>
            <tbody id="table_tr">
              <?php if(isset($pages['result']) && count($pages['result'])>0):

                    foreach($pages['result'] as $key =>$value):

            ?>

                  <tr id ="remove_<?php echo $value['id'];?>">
                    <td><?php echo ++$key; ?></td>
                    <td><?php echo ucfirst($value['name']); ?></td>
                    <td><?php echo substr($value['content'],0,150);?></td>
                    <td><?php echo ($value['status']==ACTIVE)?"Active":"Inactive";?></td>
                    <td>
                        <a class="table_icon" href="<?php echo base_url()?>admin/cms/edit?id=<?php echo $this->Common_model->mcrypt_data($value['id']);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:void(0);" class="table_icon"><i class="fa fa-trash" aria-hidden="true" onclick="deleteUser('cms',<?php echo DELETED;?>,'<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($value['id']),true);?>','req/change-user-status','Do you really want to delete this page?');"></i></a>
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
            </div>
    <!-- table 1 close-->

<!--Table listing-->
</div>