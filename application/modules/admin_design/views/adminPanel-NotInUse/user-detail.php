

<div class="inner-right-panel">

<!--breadcrumb wrap-->
<div class="breadcrumb-wrap">
    <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/users">Users</a></li>
        <li class="breadcrumb-item active">User Detail</li>
    </ol>
</div>






    <!--Filter Section -->

        <div class="form-item-wrap">
            <div class="form-item-title clearfix">
                <h3 class="title">User Detail</h3>
            </div>
            <!-- title and form upper action end-->
            <div class="form-ele-wrapper clearfix">


                <div class="row">
                    <!-- <div class="col-lg-4 col-sm-4 col-xs-4">
                        <div class="form-profile-pic-wrapper">
                            <div class="profile-pic" style="background-image:url('http://imkstaging.applaurels.com/dev/public/upload/user_image/man.svg');">
                                <a href="javascript:void(0);" class="upimage-btn">
                                            <img src="images/photo-camera.svg">
                                            <input type="file" id="uploadPic">
                                        </a>
                            </div>
                        </div>
                    </div> -->
                    <!--form ele wrapper-->
                    <div class="user-detail-panel">
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Name</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo ucfirst($profile['first_name']).' '.ucfirst($profile['middle_name']).' '.$profile['last_name'];?></span>
                                </div>
                            </div>
                        </div>
                        <!--<div class="col-lg-6 col-sm-6 col-xs-6">
                                        <div class="form-group">
                                            <label>Registration Date</label>
                                            <div class="input-holder">
                                                <span class="text-detail">12-7-17</span>
                                            </div>
                                        </div>
                                    </div>-->
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Gender</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $profile['gender'];?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Address</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $profile['address'];?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Phone Number</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $profile['phone'];?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Age</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo $profile['age'];?></span>
                                </div>
                            </div>
                        </div>
                        <div class="col-lg-6 col-sm-6 col-xs-6">
                            <div class="form-group">
                                <label class="admin-label">Register Date</label>
                                <div class="input-holder">
                                    <span class="text-detail"><?php echo date("d M Y H:i a",strtotime($profile['registered_date']));?></span>
                                </div>
                            </div>
                        </div>


                    </div>


                </div>
                <!--form ele wrapper end-->
            </div>
            <!--form element wrapper end-->
            <!--<div class="form-ele-action-bottom-wrap btns-center clearfix">
                        <button class="btn btn-cancel">Cancel</button>
                        <button class="btn btn-success">Save</button>
                    </div>-->
        </div>

    <?php /* ?>
        <!--close form view   -->
        <div class="filter_order">
            <div class="row">
                <div class="col-lg-12 col-sm-12">
                    <h1 class="view_hd">Total Order Requests
                        <span></span>
                    </h1>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <label class="admin-label">Search By Order Id</label>
                    <div class="srch-wrap">
                        <span class="srch-close-icon"></span>
                        <!--<span class="search-icon"></span>-->
                        <button class="srch" type="submit" action=""><span class="search-icon"></span></button>
                        <input type="text" value="" class="search-box" placeholder="Search by name,email" id="searchuser" name="searchuser">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <label class="admin-label">Start Date</label>
                    <div class="inputfield-wrap">
                        <input type="text" name="" value="" class="form-date_wrap" id="datepicker_1" placeholder="From">

                    </div>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <label class="admin-label">End Date</label>
                    <div class="inputfield-wrap">

                        <input type="text" name="" value="" class="form-date_wrap" id="datepicker_2" placeholder="To">
                    </div>
                </div>
                <div class="col-lg-3 col-sm-3">
                    <div class="button-wrap text-center btn-pd">
                        <button type="Submit" class="commn-btn cancel">Reset</button>
                        <button type="reset" class="commn-btn save">Filter</button>
                    </div>
                </div>
            </div>

        </div>

    <!--Filter Section Close-->


    <!--Table-->
    <div class="table-responsive custom-tbl">
        <!--table div-->
        <table id="example" class="list-table table table-striped sortable" cellspacing="0" width="100%">
            <thead>
                <tr>
                    <th width="50px">S.No</th>
                    <th>Order Id</th>
                    <th>Order Date</th>
                    <th>Order Time</th>
                    <th>Order Amount</th>

                    <th>Restaurant Name</th>

                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>1</td>
                    <td><a href="javascript:void(0);">ORD123</a></td>
                    <td>24 july'17</td>
                    <td>3:24 pm</td>
                    <td>$832</td>
                    <td>Red chilli Restaurant</td>
                </tr>
                <tr>

                    <td>2</td>
                    <td><a href="javascript:void(0);">ORD123</a></td>
                    <td>24 july'17</td>
                    <td>3:24 pm</td>
                    <td>$832</td>
                    <td>Red chilli Restaurant</td>
                </tr>
                <tr>
                    <td>3</td>
                    <td><a href="javascript:void(0);">ORD123</a></td>
                    <td>24 july'17</td>
                    <td>3:24 pm</td>
                    <td>$832</td>
                    <td>Red chilli Restaurant</td>
                </tr>
                <tr>
                    <td>4</td>
                    <td><a href="javascript:void(0);">ORD123</a></td>
                    <td>24 july'17</td>
                    <td>3:24 pm</td>
                    <td>$832</td>
                    <td>Red chilli Restaurant</td>
                </tr>

            </tbody>
        </table>
    </div> <input type="hidden" name="" id="" value="">
    <!-- table 1 close-->
    <?php */ ?>
</div>
