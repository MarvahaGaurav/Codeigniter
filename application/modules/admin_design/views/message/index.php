<link href="<?php echo base_url()?>public/css/plugin/datepicker.css" rel='stylesheet'>

<?php 
$filterArr = $this->input->get();
$filterArr = (object) $filterArr;
$controller = $this->router->fetch_class();
$method = $this->router->fetch_method();
$module = $this->router->fetch_module();
?>
<input type="hidden" id="filterVal" value='<?php echo json_encode($filterArr); ?>'>
<input type="hidden" id="pageUrl" value='<?php echo base_url().$module.'/'.strtolower($controller).'/'.$method; ?>'>


<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item active">Message</li>
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
                    <!-- <button class="srch search-icon" style="cursor:default"></button>
                    <a href="javascript:void(0)"> <span class="srch-close-icon searchCloseBtn"></span></a> -->
                    <span class="fawe-icon fawe-icon-position-left search-ico"><i class="fa fa-search"></i></span>
                    <span class="fawe-icon fawe-icon-position-right close-ico"><i class="fa fa-times-circle"></i></span>
                    <input type="text" value="<?php echo (isset($searchlike) && !empty($searchlike))? $searchlike:''?>" class="search-box searchlike" placeholder="Search by name" id="searchuser" name="search">
                </div>
            </div>

            <div class="col-lg-4 col-sm-3">
                <div class="circle-btn-wrap col-sm-nospace">
                    <a href="javascript:void(0)" id="filter-side-wrapper" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-filter" aria-hidden="true"></i>
                        </div>
                        <span class="tooltiptext">Filter</span>
                    </a>
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
                <label class="admin-label">Received On</label>
                <div class="inputfield-wrap">
                    <input type="text" value="<?php echo !empty($startDate)?date('m/d/Y', strtotime($startDate)):"" ?>" class="form-date_wrap startDate" data-provide="datepicker" id="from_date" placeholder="From">
                </div>
                <div class="inputfield-wrap">
                    <input type="text" value="<?php echo !empty($endDate)?date('m/d/Y', strtotime($endDate)):"" ?>" class="form-date_wrap endDate" data-provide="datepicker" id="to_date" placeholder="To">
                </div>
            </div>
            <div class="button-wrap text-center">
                <button type="Submit" class="commn-btn cancel resetfilter">Reset</button>
                <button type="reset" class="commn-btn save applyfilter">Filter</button>
            </div>
        </div>
    </div>
    <!--Filter Wrapper Close-->

    <!--Table-->
    <div class="section">
        <p class="tt-count">Total Notifications <?php echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <!--table div-->
            <table cellspacing="0" id="example" class="table-custom sortable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Title</th>
                        <th>Message</th>
                        <th><a href="javascript:void(0);" class="sort sorting">Sent By</a></th>
                        <th><a href="javascript:void(0);" class="sort sorting">Received On</a></th>
                        <?php if($accesspermission['deletep'] || $accesspermission['editp']) { ?>
                            <th>Action</th>
                        <?php } ?>
                    </tr>
                </thead>
                <tbody>
                    
                    <?php if (!empty($notiList)) { 
                        if ($page > 1) {
                            $i = (($page * $limit)- $limit) + 1;
                        } else {
                            $i = 1;
                        }
                        ?>
                        <?php foreach ($notiList as $list) { ?>
                            <tr>
                                <td><?php echo $i; ?></td>
                                <td class="text-nowrap">Lorem Ipsum</td>
                                <td>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</td>
                                <td class="text-nowrap"><?php echo $list['total_sents'] ?></td>
                                <td class="text-nowrap"><?php echo date('d-m-Y', strtotime($list['created_at'])) ?></td>
                                <td class="text-nowrap table-action">
                                    <a href=href="<?php echo base_url()?>admin/message/add"  class="f-eye"><i class="fa fa-eye" aria-hidden="true"></i></a>
                                    <a href="javascript:void(0);" class="f-delete"><i class="fa fa-trash"></i></a>
                                </td>
                            </tr>
                        <?php $i++; 
                        } 
                    } else { ?>
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
                <div class="modal-btn">
                    <button type="button" class="commn-btn cancel" data-dismiss="modal">Resend Now</button>
                    <button type="button" class="commn-btn save" >Edit & Resend</button>
                </div>
            </div>

        </div>
    </div>
<!--Edit Modal Close-->

<script src="<?php echo base_url()?>public/js/plugin/datepicker.min.js"></script>
<script>
    $(document).ready(function(){
        
        // Date Picker
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        // =============== Linked Datepicker =============== //
        var add_start = $('#from_date').datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight:'TRUE',
            autoclose: true,
            onRender: function(date) {
                return date.valueOf() > now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            if (ev.date.valueOf() < add_end.date.valueOf()) {
                var newDate = new Date(ev.date)
                newDate.setDate(newDate.getDate());
                add_end.setValue(newDate);
                add_start.hide();
            }
            add_start.hide();
            $('#to_date')[0].focus();
        }).data('datepicker');

        var add_end = $('#to_date').datepicker({
            format: 'dd/mm/yyyy',
            todayHighlight:'TRUE',
            autoclose: true,
            onRender: function(date) {
                return date.valueOf() > now.valueOf() ? 'disabled' : '';
            }
        }).on('changeDate', function(ev) {
            add_end.hide();
        }).data('datepicker');

    });
</script>
