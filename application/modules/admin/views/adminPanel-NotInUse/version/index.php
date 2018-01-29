
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
                    <div class="srch-wrap">
                      <form method="GET" id="admin_search_form">

                            <!--<span class="search-icon"></span>-->
                            <button class="srch" type="submit">
                                <span class="search-icon"></span>

                            </button>
                            <a href="<?php echo base_url().'admin/version'?>"> <span class="srch-close-icon"></span></a>
                            <input type="text" value="<?php echo (isset($searchdata) && !empty($searchdata))? $searchdata:''?>" class="search-box" placeholder="Search by name,email" id="searchuser" name="search">
                        </form>
                    </div>
                </div>

                <div class="col-lg-8 col-sm-8">
                    <div class="top-opt-wrap text-right">
                        <ul>
                            <li>
                                <a href="<?php echo base_url()?>admin/version/add" title="Add Content" class="icon_filter"><img src="<?php echo base_url()?>public/adminpanel/images/add.svg"> </a>
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
                    <th width="100px">Actions</th>
                </tr>

            </thead>
            <tbody id="table_tr">
              <?php if(isset($versions['result']) && count($versions['result'])>0):

                    foreach($versions['result'] as $key =>$value):

            ?>

                  <tr id ="remove_<?php echo $value['vid'];?>">
                    <td><?php echo ++$key; ?></td>
                    <td><?php echo $value['version_name']; ?></td>
                    <td><?php echo $value['versiob_title'];?></td>
                    <td><?php if (!empty($value['version_desc'])) {echo substr($value['version_desc'],0,155); if(strlen($value['version_desc']) > 154){echo '...';} } ?></td>
                    <td><?php echo ($value['platform']==ANDROID)?"Andorid":"Iphone"; ?></td>
                    <td><?php echo ($value['update_type']==NORMAL)?"Normal":(($value['update_type']==SKIPPABLE)?"Skippable":"Forcefully"); ?></td>
                    <td><?php echo ($value['is_cur_version']==YES)?"Yes":"No"; ?></td>
                    <td><?php echo date('d m Y H:i a', strtotime($value['create_date']));?></td>

                    <td>
                        <a class="table_icon" href="<?php echo base_url()?>admin/version/edit?id=<?php echo $this->Common_model->mcrypt_data($value['vid']);?>"><i class="fa fa-pencil-square-o" aria-hidden="true"></i></a>
                        <a href="javascript:void(0);" class="table_icon"><i class="fa fa-trash" aria-hidden="true" onclick="deleteUser('version',<?php echo DELETED;?>,'<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($value['vid']),true);?>','req/change-user-status','Do you really want to delete this version?');"></i></a>
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
            </div>
    <!-- table 1 close-->
</div>
<!--Table listing-->
