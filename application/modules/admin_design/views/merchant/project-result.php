<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/users">Merchant Management</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/detail">Merchant Profile</a></li>
            <li class="breadcrumb-item active">Project Results</li>
        </ol>
    </div>

    <!--Filter Section -->
    <div class="form-item-wrap">

        <!--Filter Wrapper Close-->

        <div class="section">
            <!--table-->
            <div class="table-responsive table-wrapper">
                <table cellspacing="0" id="example" class="table-custom sortable">
                    <thead>
                        <tr>
                            <th>S.No</th>
                            <th>Room Type</th>
                            <th>Room Dimention</th>
                            <th>Project Level</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>01</td>
                            <td class="td-content-left">
                                <a href="javascript:void(0)" class="thumb-type" data-toggle="modal" data-target="#thumb-view-detail">
                                    <span class="td-thumb"><img src="public/images/room.png"></span>
                                    <span class="td-thumb-name">Bathroom</span>
                                </a>
                            </td>
                            <td class="text-nowrap">18 M x 22 M x 12 M</td>
                            <td>9</td>
                            <td class="text-nowrap table-action">
                                <a href="<?php echo base_url()?>admin/project" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                                <a href="javascript:void(0)" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                                <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                                <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                                <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>02</td>
                            <td class="td-content-left">
                                <a href="javascript:void(0)" class="thumb-type" data-toggle="modal" data-target="#thumb-view-detail">
                                    <span class="td-thumb"><img src="public/images/room.png"></span>
                                    <span class="td-thumb-name">Bathroom</span>
                                </a>
                            </td>
                            <td class="text-nowrap">18 M x 22 M x 12 M</td>
                            <td>4</td>
                            <td class="text-nowrap table-action">
                                <a href="<?php echo base_url()?>admin/project" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                                <a href="javascript:void(0)" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                                <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                                <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                                <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>03</td>
                            <td class="td-content-left">
                                <a href="javascript:void(0)" class="thumb-type" data-toggle="modal" data-target="#thumb-view-detail">
                                    <span class="td-thumb"><img src="public/images/room.png"></span>
                                    <span class="td-thumb-name">Bathroom</span>
                                </a>
                            </td>
                            <td class="text-nowrap">18 M x 22 M x 12 M</td>
                            <td>6</td>
                            <td class="text-nowrap table-action">
                                <a href="<?php echo base_url()?>admin/project" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
                                <a href="javascript:void(0)" class="f-pencil"><i class="fa fa-pencil" title="Edit"></i></a>
                                <a href="javascript:void(0)" class="f-unblock"><i class="fa fa-unlock" title="Unblock"></i></a>
                                <a href="javascript:void(0)" class="f-block"><i class="fa fa-ban" title="Block"></i></a>
                                <a href="javascript:void(0)" class="f-delete"><i class="fa fa-trash" title="Delete"></i></a>
                            </td>
                        </tr>
                        <tr>
                            <td>04</td>
                            <td class="td-content-left">
                                <a href="javascript:void(0)" class="thumb-type" data-toggle="modal" data-target="#thumb-view-detail">
                                    <span class="td-thumb"><img src="public/images/room.png"></span>
                                    <span class="td-thumb-name">Bathroom</span>
                                </a>
                            </td>
                            <td class="text-nowrap">18 M x 22 M x 12 M</td>
                            <td>3</td>
                            <td class="text-nowrap table-action">
                                <a href="<?php echo base_url()?>admin/project" class="f-eye"><i class="fa fa-eye" title="View"></i></a>
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
</div>

<!-- thumb-view-detail Modal -->
<div id="thumb-view-detail" class="modal fade" role="dialog">
    <div class="modal-dialog modal-lg">
        <!-- Modal content-->
        <div class="modal-content">
            <div class="modal-header modal-alt-header">
                <button type="button" class="close" data-dismiss="modal">&times;</button>
                <h4 class="modal-title modal-heading">Bathroom Front View </h4>
            </div>
            <div class="modal-body">
                <div class="thumbview-table">
                    <div class="thumbview-tablecell thumbview-tablecell-one">
                        <!-- thumbnail -->
                        <div id="thumb-tab1" class="image-view-wrapper img-viewbdr-radius4p img-view-fullp active">
                            <div class="image-view img-view-full-12" style="background-image:url('public/images/bathroom-light.jpg')"></div>
                        </div>

                        <div id="thumb-tab2" class="image-view-wrapper img-viewbdr-radius4p img-view-fullp">
                            <div class="image-view img-view-full-12" style="background-image:url('public/images/room.png')"></div>
                        </div>

                        <div id="thumb-tab3" class="image-view-wrapper img-viewbdr-radius4p img-view-fullp">
                            <div class="image-view img-view-full-12" style="background-image:url('public/images/bathroom-light.jpg')"></div>
                        </div>
                        <!-- //thumbnail -->
                    </div>
                    <div class="thumbview-tablecell thumbview-tablecell-two">
                        <!-- thumbnail list -->
                        <div class="thumb-view-listing-wrapper thumb-tab">
                            <ul>
                                <li data-thumb="thumb-tab1" class="active">
                                    <div class="thumb-view-list clearfix">
                                        <figure>
                                            <img src="public/images/bathroom-light.jpg" alt="thumbnail">
                                        </figure>
                                        <span class="thumbnail-sideview">Front View</span>
                                    </div>
                                </li>
                                <li data-thumb="thumb-tab2">
                                    <div class="thumb-view-list clearfix">
                                        <figure>
                                            <img src="public/images/room.png" alt="thumbnail">
                                        </figure>
                                        <span class="thumbnail-sideview">Top View</span>
                                    </div>
                                </li>
                                <li data-thumb="thumb-tab3">
                                    <div class="thumb-view-list clearfix">
                                        <figure>
                                            <img src="public/images/bathroom-light.jpg" alt="thumbnail">
                                        </figure>
                                        <span class="thumbnail-sideview">Side View</span>
                                    </div>
                                </li>
                            </ul>
                        </div>
                        <!-- //thumbnail list -->
                    </div>
                </div>

                <div class="thumb-view-footer">
                    <h3 class="thumb-footer-title">Recommended Data</h3>
                    <!-- thumb-footer-detail -->
                    <div class="row thumb-footer-detail">
                        <div class="col-xs-6">
                            <p class="rd-property">Lux</p>
                            <p class="rd-value">520</p>
                        </div>
                        <div class="col-xs-6">
                            <p class="rd-property">Number of Luminaries</p>
                            <p class="rd-value">6</p>
                        </div>
                    </div>
                    <div class="row thumb-footer-detail">
                        <div class="col-xs-6">
                            <p class="rd-property">Article Number:</p>
                            <p class="rd-value">11713</p>
                        </div>
                        <div class="col-xs-6">
                            <p class="rd-property">Product Family:</p>
                            <p class="rd-value">Occuldas</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-6">
                            <p class="rd-property">Kelvin:</p>
                            <p class="rd-value">2700K</p>
                        </div>
                        <div class="col-xs-6">
                            <p class="rd-property">Beam Angle:</p>
                            <p class="rd-value">1100</p>
                        </div>
                        <div class="clearfix"></div>
                        <div class="col-xs-6">
                            <p class="rd-property">Energy Class:</p>
                            <p class="rd-value">A+</p>
                        </div>
                        <div class="col-xs-6">
                            <p class="rd-property">IP Class</p>
                            <p class="rd-value">IP23</p>
                        </div>
                        <div class="col-xs-6">
                            <p class="rd-property">UGR</p>
                            <p class="rd-value">30lK</p>
                        </div>
                    </div>
                    <!-- thumb-footer-detail -->
                </div>
            </div>

        </div>
    </div>
</div>

<script>
    $(document).ready(function(){

        //thumb-tab
        $('.thumb-tab ul li').click(function(){
            var thumbtab = $(this).attr('data-thumb');

            $('.thumb-tab ul li').removeClass('active');
            $('.image-view-wrapper').removeClass('active');

            $(this).addClass('active');
            $('#'+thumbtab).addClass('active');
        });
    
    })
</script>
