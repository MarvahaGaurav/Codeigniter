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
            <li class="breadcrumb-item">Merchant Management</li>
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
                    <input type="text" maxlength="15" value="" class="search-box searchlike" placeholder="Search by name, email" id="searchuser" name="search">
                </div>
            </div>

            <div class="col-lg-4 col-sm-3">
                <div class="circle-btn-wrap col-sm-space">
                    <a href="javascript:void(0)" id="filter-side-wrapper" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-filter"></i>
                        </div>
                        <span class="tooltiptext">Filter</span>
                    </a>
                </div>
            </div>
        </div>
    </div>
    <!--Filter Section Close-->
    
    <!--Filter Wrapper-->
    <div class="filter-wrap ">
        <div class="filter_hd clearfix">
            <div class="pull-left">
                <h2 class="fltr-heading">Filter</h2>
            </div>
            <div class="pull-right">
                <span class="close flt_cl" data-dismiss="modal">X</span>
            </div>
        </div>
        <div class="inner-filter-wrap">
           
            <div class="fltr-field-wrap">
                <label class="admin-label">Registration Date</label>
                <div class="inputfield-wrap">
                    <input type="text" name="startDate" data-provide="datepicker" value="" class="startDate" id="startDate" placeholder="From">
                 </div>
                 <div class="inputfield-wrap">
                    <input type="text" name="endDate" data-provide="datepicker" value="" class="endDate" id="endDate" placeholder="To">
                 </div>
            </div>
            
            <div class="fltr-field-wrap">
                <label class="admin-label">Total Projects</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option value="">All</option>
                        <option>0-10</option>
                        <option>10-20</option>
                     </select>
                    
                </div>
            </div>

            <div class="fltr-field-wrap">
                <label class="admin-label">Country</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter country" name="country" data-live-search="true">
                        <option value="">Select Country</option>
                        <option></option>
                    </select>
                </div>
            </div>

            <div class="button-wrap text-center">
                <button type="reset" class="commn-btn cancel resetfilter" id="resetbutton">Reset</button>
                <input type="submit" class="commn-btn save applyFilterUser" id="filterbutton"name="filter" value="Apply">
            </div>
            
        </div>
    </div>
    <!--Filter Wrapper Close-->

    <div class="section">
        <p class="tt-count">Total Users: <?php //echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <!--table div-->
            <table  cellspacing="0" id="example" class="table-custom sortable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>
                            <a href="" class="sort sorting">Name</a>
                        </th>
                        <th>Email ID</th>
                        <th>Mobile Number</th>
                        <th><a href="javascript:void(0)" class="sort sorting">City</th>
                        <th>Country</th>
                        <th>
                            <a href="" class="sort sorting">Registered On</a>
                        </th>
                        <th>Total Projects</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table_tr">                        
                    <tr>
                        <td>01</td>
                        <td class="text-nowrap">Adam</td>
                        <td class="text-nowrap">adam012@gmail.com</td>
                        <td>9874512685</td>
                        <td>Oslo</td>
                        <td>Norway</td>
                        <td class="text-nowrap">02-01-2018</td>
                        <td>13</td>
                        <td>Active</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/merchant/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="<?php echo base_url()?>admin/merchant/edit" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td class="text-nowrap">Robert</td>
                        <td class="text-nowrap">robert@gmail.com</td>
                        <td>9874512685</td>
                        <td>Oslo</td>
                        <td>Norway</td>
                        <td class="text-nowrap">02-01-2018</td>
                        <td>13</td>
                        <td>Active</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/merchant/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="<?php echo base_url()?>admin/merchant/edit" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>03</td>
                        <td class="text-nowrap">Adam</td>
                        <td class="text-nowrap">adam012@gmail.com</td>
                        <td>9874512685</td>
                        <td>Oslo</td>
                        <td>Norway</td>
                        <td class="text-nowrap">02-01-2018</td>
                        <td>13</td>
                        <td>Active</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/merchant/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="<?php echo base_url()?>admin/merchant/edit" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>04</td>
                        <td class="text-nowrap">Robert</td>
                        <td class="text-nowrap">robert@gmail.com</td>
                        <td>9874512685</td>
                        <td>Oslo</td>
                        <td>Norway</td>
                        <td class="text-nowrap">02-01-2018</td>
                        <td>13</td>
                        <td>Active</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/merchant/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="<?php echo base_url()?>admin/merchant/edit" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div class="pagination_wrap clearfix">
            <?php //echo $link;?>
        </div>
        
    </div>
</div>
<script src="<?php echo base_url()?>public/js/plugin/datepicker.min.js"></script>
<script>

    $(document).ready(function(){
        
        // Date Picker
        var nowTemp = new Date();
        var now = new Date(nowTemp.getFullYear(), nowTemp.getMonth(), nowTemp.getDate(), 0, 0, 0, 0);

        // =============== Linked Datepicker =============== //
        var add_start = $('#startDate').datepicker({
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
            $('#endDate')[0].focus();
        }).data('datepicker');

        var add_end = $('#endDate').datepicker({
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
