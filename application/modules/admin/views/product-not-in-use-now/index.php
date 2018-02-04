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
<div class="inner-right-panel">
    
    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item">Product Management</li>
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
                    <a href="javascript:void(0)" class="tooltip-p">
                        <div class="circle-btn animate-btn">
                            <i class="fa fa-upload"></i>
                            <input type="file" class="file">
                        </div>
                        <span class="tooltiptext">Import New Product</span>
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
                <label class="admin-label">Light Type</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option>LED</option>
                        <option>CFL</option>
                        <option>MHL</option>
                     </select>
                    
                </div>
            </div>

            <div class="fltr-field-wrap">
                <label class="admin-label">Energy Class</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option>A+</option>
                        <option>B+</option>
                        <option>C+</option>
                     </select>
                    
                </div>
            </div>

            <div class="fltr-field-wrap">
                <label class="admin-label">Mounting</label>
                <div class="commn-select-wrap">
                    <select class="selectpicker filter status" name="status">
                        <option>Surface</option>
                        <option>Ceiling</option>
                        <option>Wall Sconces</option>
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


            <div class="button-wrap text-center">
                <button type="reset" class="commn-btn cancel resetfilter" id="resetbutton">Reset</button>
                <input type="submit" class="commn-btn save applyFilterUser" id="filterbutton"name="filter" value="Apply">
            </div>
            
        </div>
    </div>
    <!--Filter Wrapper Close-->

    <!--Table-->
    <div class="section">
        <p class="tt-count">Total Product: <?php //echo $totalrows ?></p>
        <div class="table-responsive table-wrapper">
            <!--table div-->
            <table cellspacing="0" id="example" class="table-custom sortable">
                <thead>
                    <tr>
                        <th>S.No</th>
                        <th>Artical Code</th>
                        <th>Product</th>
                        <th>Wattage</th>
                        <th>Light Type</th>
                        <th>Energy Class</th>
                        <th>Mounting</th>
                        <th>Lux Value</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="table_tr">                        
                    <tr>
                        <td>01</td>
                        <td class="text-nowrap">5046942150</td>
                        <td class="td-content-left">
                            <a href="<?php echo base_url()?>admin/product/view" class="thump-type">
                                <span class="td-thumb"><img src="public/images/room.png"></span>
                                <span class="td-thumb-name">Carmencita</span>
                            </a>
                        </td>
                        <td class="text-nowrap">3 x 2, 4W + 16W</td>
                        <td>LED</td>
                        <td>A+</td>
                        <td>Surface</td>
                        <td>567 Lux</td>
                        <td class="text-nowrap table-action">
                            <a href="<?php echo base_url()?>admin/product/view" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                            <a href="<?php echo base_url()?>admin/merchant/edit" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                            <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                            <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                            <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                        </td>
                    </tr>
                    <tr>
                        <td>02</td>
                        <td class="text-nowrap">5046942151</td>
                        <td class="td-content-left">
                            <a href="<?php echo base_url()?>admin/product/view" class="thump-type">
                                <span class="td-thumb"><img src="public/images/room.png"></span>
                                <span class="td-thumb-name">EcoLine Office</span>
                            </a>
                        </td>
                        <td class="text-nowrap">3 x 2, 4W + 16W</td>
                        <td>LED</td>
                        <td>A+</td>
                        <td>Pendant</td>
                        <td>567 Lux</td>
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
                        <td class="text-nowrap">5046942152</td>
                        <td class="td-content-left">
                            <a href="<?php echo base_url()?>admin/product/view" class="thump-type">
                                <span class="td-thumb"><img src="public/images/room.png"></span>
                                <span class="td-thumb-name">Arena</span>
                            </a>
                        </td>
                        <td class="text-nowrap">3 x 2, 4W + 16W</td>
                        <td>LED</td>
                        <td>A+</td>
                        <td>Surface</td>
                        <td>567 Lux</td>
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
                        <td class="text-nowrap">5046942153</td>
                        <td class="td-content-left">
                            <a href="<?php echo base_url()?>admin/product/view" class="thump-type">
                                <span class="td-thumb"><img src="public/images/room.png"></span>
                                <span class="td-thumb-name">Orion</span>
                            </a>
                        </td>
                        <td class="text-nowrap">3 x 2, 4W + 16W</td>
                        <td>LED</td>
                        <td>A+</td>
                        <td>Top</td>
                        <td>567 Lux</td>
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