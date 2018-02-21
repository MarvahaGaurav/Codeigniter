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

<?php //echo '<pre>'; print_r($accesspermission); echo '</pre>';?>
<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Notification</li>
        </ol>
    </div>
    <!--breadcrumb wrap close-->

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
                    <input type="text" maxlength="50" value="<?php echo (isset($searchlike) && !empty($searchlike))? $searchlike:''?>" <?php if(isset($searchlike) && !empty($searchlike)){ echo 'readonly';}?> class="search-box <?php if(isset($searchlike) && !empty($searchlike)){ echo 'searchCloseBtn'; }else{  echo 'searchlike'; }?>" placeholder="Search by name" id="searchuser" name="search">
                </div>
            </div>

            <div class="col-lg-4 col-sm-3">
                <div class="circle-btn-wrap col-sm-space">
                    <a href="javascript:void(0)" title="Filter" id="filter-side-wrapper" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                            <?php if(!empty($platform) || !empty($startDate) || !empty($endDate)){ ?>
                            <i class="fa fa-close" aria-hidden="true"></i>
                            <?php } ?>
                        </div>
                        <span class="tooltiptext">Filter</span>
                    </a>
                    <?php if($accesspermission['addp']) { ?>
                    <a href="admin/notification/add" title="Add" id="filter-side-wrapper" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-plus"></i>
                        </div>
                        <span class="tooltiptext">Add</span>
                    </a>
                    <?php } ?>
                    <!-- <ul>
                        <li>
                            <a href="javascripit:void(0)" title="File Export" class="icon_filter" id="filter-side-wrapper"><img src="public/adminpanel/images/filter.svg"></a>
                        </li>
                        <?php //if($accesspermission['addp']) { ?>
                        <li>
                            <a href="<?php //echo base_url() . 'admin/notification/add'; ?>" title="Filter" id="filter-side-wrapper" class="icon_filter">
                                <img src="public/adminpanel/images/add.svg">
                            </a>
                        </li>
                        <?//php } ?>
                    </ul> -->
                </div>
            </div>
        </div>
    </div> 
    <!--Filter Section Close-->
    <?php
    if ($this->session->flashdata('message') != '') {
        echo $this->session->flashdata('message');
    }
    ?>
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

    <!--Filter Wrapper-->
    <div class="filter-wrap">
        <div class="filter_hd clearfix">
            <div class="pull-left">
                <h2 class="fltr-heading">Filter </h2>
            </div>
            <div class="pull-right">
                <span class="close flt_cl" data-dismiss="modal">X</span>
            </div>
        </div>
        <div class="inner-filter-wrap">
            <div class="fltr-field-wrap">
                <label class="admin-label">Platform</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker platform">
                        <option value="">All</option>
                        <option <?php echo ($platform == 1)?'Selected':'' ?> value="1">Android</option>
                        <option <?php echo ($platform == 2)?'Selected':'' ?> value="2">iOS</option>
                    </select>
                </div>
            </div>
            <div class="fltr-field-wrap">
                <label class="admin-label">Push Date</label>
                <?php //echo $startDate.' == '.$endDate; ?>
                <div class="inputfield-wrap">
                    <input type="text" value="<?php echo !empty($startDate)?$startDate:"" ?>" class="form-date_wrap startDate" data-provide="datepicker" id="from_date" placeholder="From">
                    <label class="ficon ficon-right" for="from_date"><i class="fa fa-calendar"></i></label>
                </div>
                <div class="inputfield-wrap">
                    <input type="text" value="<?php echo !empty($endDate)?$endDate:"" ?>" class="form-date_wrap endDate" data-provide="datepicker" id="to_date" placeholder="To">
                    <label class="ficon ficon-right" for="to_date"><i class="fa fa-calendar"></i></label>
                </div>

            </div>
            <div class="button-wrap text-center">
                <button type="Submit" class="commn-btn cancel resetfilter">Reset</button>
                <button type="reset" class="commn-btn save applyfilter">Filter</button>
            </div>
        </div>
    </div>
    <!--Filter Wrapper Close-->

    <div class="section">
        <p class="tt-count">Total Notifications <?php echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Push Title</th>
                        <th>Platform</th>
                        <th>User's Sent Count</th>
                        <th>Added On</th>
                        <?php if($accesspermission['deletep'] || $accesspermission['editp']) { ?>
                            <th>Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (!empty($notiList)) { 
                        if ($page > 1){
                            $i = (($page * $limit)- $limit) + 1;
                        } else {
                            $i = 1;
                        }
                        ?>
                        <?php foreach ($notiList as $list) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td>
                                    <?php echo $list['title'] ?>
                                </td>
                                <td><?php echo ($list['platform'] == 1)?'All':(($list['platform'] == 2)?'Android':'iOS'); ?></td>
                                <td><?php echo $list['total_sents'] ?></td>
                                <td><?php echo date('d M Y h:i:s A',strtotime($list['created_at'])) ?></td>
                                <?php if($accesspermission['deletep'] || $accesspermission['editp']) { ?>
                                <td class="text-nowrap table-action">
                                    <?php if($accesspermission['editp']) { ?>
                                        <a class="f-plane" href="javascript:void(0);" aria-hidden="true" data-toggle="modal" data-target="#editModal" onclick="$('#notiToken').val('<?php echo encryptDecrypt($list['id']);?>')"><i class="fa fa-paper-plane" title="Modal" aria-hidden="true"></i></a>
                                    <?php } ?>
                                    <?php if($accesspermission['deletep']) { ?>    
                                        <a class="f-delete" href="javascript:void(0);"><i class="fa fa-trash" title="Delete" aria-hidden="true" onclick="deleteUser('notification',<?php echo DELETED;?>,'<?php echo encryptDecrypt($list['id']);?>','req/change-user-status','Do you really want to delete this notification ?');"></i></a>                      
                                    <?php } ?>
                                </td>
                                <?php } ?>
                            </tr>
                        <?php $i++; } } else { ?>
                        <td colspan="9">No notifications found</td>
                        <?php } ?>
                </tbody>
            </table>
                </div>
            <!-- Pagenation and Display data wrap-->
                <div class="pagination_wrap clearfix">
                    <?php echo $links;?>
                </div>
            <!-- Pagination and Display data wrap-->
        </div>
        <!-- table 1 close-->
    </div>
</div>
<!--Edit  Modal Close-->
<div id="editModal" class="modal fade" role="dialog">
    <input type="hidden" id="uid" name="uid" value="">
    <input type="hidden" id="ustatus" name="ustatus" value="">
    <div class="modal-dialog modal-sm">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-alt-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modal-heading">Resend</h4>
            </div>
            <div class="modal-body">
                <p class="modal-para">Please select the option ?</p>
            </div>
            <input type="hidden" id="notiToken">
            <div class="modal-footer">
                <div class="button-wrap">
                    <button type="button" class="commn-btn resendPush save" data-dismiss="modal">Resend Now</button>
                    <button type="button" class="commn-btn editPush cancel" >Edit & Resend</button>
                </div>
            </div>

        </div>
    </div>
<!--Edit Modal Close-->
<link rel="stylesheet" href="<?php echo base_url()?>public/css/bootstrap-datetimepicker.css">
<script src="<?php echo base_url()?>public/js/moment-with-locales.js"></script>
<script src="<?php echo base_url()?>public/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url()?>public/js/custom-dashboard.js"></script>
<script>
$(function () {
   $('#from_date,#to_date').datetimepicker({
       viewMode: 'days',
       //format: 'DD-MM-YYYY',
       format: 'DD/MM/YYYY',
       useCurrent: true,
       maxDate: moment()
   });
   $('#to_date').datetimepicker().on('dp.change', function (e) {
       var incrementDay = moment(new Date(e.date));
       incrementDay.add(1, 'days');
       $('#endDate').data('DateTimePicker').minDate(incrementDay);
       $(this).data("DateTimePicker").hide();
   });

   $('#to_date').datetimepicker().on('dp.change', function (e) {
       var decrementDay = moment(new Date(e.date));
       decrementDay.subtract(1, 'days');
       $('#from_date').data('DateTimePicker').maxDate(decrementDay);
       $(this).data("DateTimePicker").hide();
   });
   
});
</script>