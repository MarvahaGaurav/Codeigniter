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
            <li class="breadcrumb-item">Template Management</li>
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
                <div class="circle-btn-wrap col-sm-nospace">
                    <a href="javascript:void(0)" id="filter-side-wrapper" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-filter"></i>
                        </div>
                        <span class="tooltiptext">Filter</span>
                    </a>
                    <a href="<?php echo base_url()?>admin/templatemgmt/create" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-plus"></i>
                        </div>
                        <span class="tooltiptext">Create Room Template</span>
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
                <label class="admin-label">Building Type</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option>Apartment block</option>
                        <option>Bungalow</option>
                        <option>Condominium</option>
                     </select>
                    
                </div>
            </div>

            <div class="fltr-field-wrap">
                <label class="admin-label">Total Rooms</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option>4</option>
                        <option>3</option>
                        <option>2</option>
                     </select>
                    
                </div>
            </div>

            <div class="fltr-field-wrap">
                <label class="admin-label">Added On</label>
                <div class="inputfield-wrap">
                    <input type="text" name="startDate" data-provide="datepicker" value="<?php echo isset($startDate)?$startDate:""?>" class="startDate" id="startDate" placeholder="From">
                </div>
                <div class="inputfield-wrap">
                    <input type="text" name="endDate" data-provide="datepicker" value="<?php echo isset($endDate)?$endDate:""?>" class="endDate" id="endDate" placeholder="To">
                </div>
            </div>

            <div class="fltr-field-wrap">
                <label class="admin-label">Room Shape</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option>Rectangle</option>
                        <option>Square</option>
                        <option>Circle</option>
                     </select>
                    
                </div>
            </div>

            <div class="fltr-field-wrap">
                <label class="admin-label">Lux Value</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option>567 Lux</option>
                        <option>568 Lux</option>
                        <option>569 Lux</option>
                     </select>
                    
                </div>
            </div>

            <div class="button-wrap">
                <button type="reset" class="commn-btn cancel" id="resetbutton">Reset</button>
                <input type="submit" class="commn-btn save" id="filterbutton"name="filter" value="Apply">
            </div>
            
        </div>
    </div>
    <!--Filter Wrapper Close-->
    <div class="row">
        <div class="col-lg-6">
            
        </div>
    </div>

    <div class="section">
        <p class="tt-count">Total Room Type: <?php //echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <!--table -->
            <table cellspacing="0" id="example" class="table-custom sortable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Room Type</th>
                        <th>Room Dimention</th>
                        <th>Workplace Height</th>
                        <th>Room Shape</th>
                        <th>Lux Value</th>
                        <th>Added On</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td>01</td>
                        <td>
                            <a href="<?php echo base_url()?>admin/templatemgmt/view" class="thump-type">
                                <span class="td-thumb"><img src="public/images/room.png"></span>
                                <span class="td-thumb-name">Bathroom</span>
                            </a>
                        </td>
                        <td class="text-nowrap">18 M x 22 M x 12 M</td>
                        <td class="text-nowrap">0.75 M</td>
                        <td class="text-nowrap">Rectangle</td>
                        <td class="text-nowrap">550 Lux</td>
                        <td>18-10-2017</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/templatemgmt/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="<?php echo base_url()?>admin/templatemgmt/edit" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>
                            <a href="<?php echo base_url()?>admin/templatemgmt/view" class="thump-type">
                                <span class="td-thumb"><img src="public/images/room.png"></span>
                                <span class="td-thumb-name">Bedroom</span>
                            </a>
                        </td>
                        <td class="text-nowrap">18 M x 22 M x 12 M</td>
                        <td class="text-nowrap">1 M</td>
                        <td class="text-nowrap">Rectangle</td>
                        <td class="text-nowrap">550 Lux</td>
                        <td>18-10-2017</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/templatemgmt/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="javascript:void(0)" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>01</td>
                        <td>
                            <a href="<?php echo base_url()?>admin/templatemgmt/view" class="thump-type">
                                <span class="td-thumb"><img src="public/images/room.png"></span>
                                <span class="td-thumb-name">Bathroom</span>
                            </a>
                        </td>
                        <td class="text-nowrap">18 M x 22 M x 12 M</td>
                        <td class="text-nowrap">0.75 M</td>
                        <td class="text-nowrap">Rectangle</td>
                        <td class="text-nowrap">550 Lux</td>
                        <td>18-10-2017</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/templatemgmt/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="<?php echo base_url()?>admin/templatemgmt/edit" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td>
                            <a href="<?php echo base_url()?>admin/templatemgmt/view" class="thump-type">
                                <span class="td-thumb"><img src="public/images/room.png"></span>
                                <span class="td-thumb-name">Bedroom</span>
                            </a>
                        </td>
                        <td class="text-nowrap">18 M x 22 M x 12 M</td>
                        <td class="text-nowrap">1 M</td>
                        <td class="text-nowrap">Rectangle</td>
                        <td class="text-nowrap">550 Lux</td>
                        <td>18-10-2017</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/templatemgmt/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="javascript:void(0)" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                </tbody>
            </table>
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
