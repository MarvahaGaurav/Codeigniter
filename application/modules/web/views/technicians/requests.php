<div class="inner-container">
        <div class="container">

            <!-- breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="javascript:void(0)">Home</a></li>
                <li class="active">Technician Requests</li>
            </ul>
            <!-- //breadcrumb -->

            <div class="page-heading">
                <h1 class="page-title">Technician Requests</h1>
                <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficient and environmental friendly, in combination
                        with a creation of the ambiance that you need, always keeping in mind that luminaires have a great impact on the environment.</p>
            </div>

            <!-- technician list search -->
            <div class="project-list-wrapper technician-list clearfix">
                <h2 class="project-listtxt">Technician List</h2>
                <div class="button-wrapper-two button-wrapper-right">
                    <!--<button type="button" id="filter-btn" class="custom-btn btn-width save">
                        <i class="fa fa-filter fa-common"></i> Filter
                    </button>-->
                    <!-- filter section -->
                    <div id="filter-section" class="filter-section section-arrow">
                        <!-- form wrapper -->
                        <div class="form-wrapper">

                            <!-- Caption before section -->
                            <div class="section-title clearfix">
                                <h3>Filter</h3>
                            </div>
                            <!-- Caption before section -->

                            <div class="row form-inline-wrapper">
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="labelTxt">Find your Product</label>
                                        <div class="form-group-field">
                                            <select>
                                                <option>Select</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                            </select>
                                            <span class="customArrow"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="labelTxt">Find your Application</label>
                                        <div class="form-group-field">
                                            <select>
                                                <option>Select</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                            </select>
                                            <span class="customArrow"></span>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                                    <div class="form-group">
                                        <label class="labelTxt">Find your Sector</label>
                                        <div class="form-group-field">
                                            <select>
                                                <option>Select</option>
                                                <option>2</option>
                                                <option>3</option>
                                                <option>4</option>
                                            </select>
                                            <span class="customArrow"></span>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            <!-- Caption before section -->
                            <div class="section-title clearfix">
                                <div class="button-wrapper text-right">
                                    <input type="submit" value="Apply" class="custom-btn btn-width save">
                                    <button type="button" id="close-filter" class="custom-btn btn-width cancel">Cancel</button>
                                </div>
                            </div>
                            <!-- Caption before section -->
                        </div>
                        <!-- //form wrapper -->
                    </div>
                </div>
                <div class="search-wrapper search-wrapper-width-2 search-wrp-992 fawe-icon-position">
                    <span class="fawe-icon fawe-icon-position-right close-ico">
                        <i class="fa fa-times"></i>
                    </span>
                    <form id="search-form" method="GET" action="">
                        <input type="text" value="<?php echo $search ?>" data-redirect="<?php echo base_url("home/technicians/requests") ?>" class="search-box" name="search" id="search-box" placeholder="Search Technician">
                        <input type="submit" value="Search" class="search-btn" />
                    </form>
                </div>
            </div>
            <!-- //technician list search -->

            <!-- Technician list table -->
            <div class="table-responsive table-wrapper" id="scrollbar-inner">
                <table cellspacing="0" class="table-custom">
                    <thead>
                        <tr>
                            <th>Technician</th>
                            <th class="text-center">Account Type</th>
                            <th>City</th>
                            <th>Country</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($technicians as $technician) : ?>
                        <tr id="technician-<?php echo $key ?>">
                            <td class="td-thumb-round text-nowrap">
                                <div class="thumb-view-wrapper thumb-view-p4 img-viewbdr-radius">
                                    <div class="thumb-view thumb-viewfullheight-4" style="background:url('<?php echo $technician['image'] ?>') no-repeat;"></div>
                                </div>
                                <span class="td-technician op-semibold"><?php echo $technician['first_name'] ?></span>
                            </td>
                            <td class="text-nowrap text-center"><?php echo $technician['user_type'] ?></td>
                            <td class="text-nowrap"><?php echo $technician['city'] ?></td>
                            <td class="text-nowrap"><?php echo $technician['country'] ?></td>
                            <td  class="text-nowrap action-user">
                                <a href="<?php echo base_url("home/technicians/" . $technician['id']) ?>" class="tb-view-list" title="View">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <a href="javascript:void(0)" data-target="#technician-<?php echo $key ?>" data-json='<?php echo $technician['accept_data'] ?>' data-url="<?php echo base_url("xhttp/employee/action") ?>" data-action="remove" data-redirect="<?php echo base_url("home/technicians") ?>" data-title="Delete" data-message="Are you sure you want to accept <?php echo ucwords($technician['first_name']) ?>'s request?" class="tb-view-list user-accept confirmation-action-xhttp" class="tb-view-list" title="Accept">
                                    <i class="fa fa-check-circle-o" aria-hidden="true"></i>
                                </a>
                                <a href="javascript:void(0)" data-target="#technician-<?php echo $key ?>" data-json='<?php echo $technician['reject_data'] ?>' data-url="<?php echo base_url("xhttp/employee/action") ?>" data-action="remove" data-redirect="<?php echo base_url("home/technicians/requests") ?>" data-title="Delete" data-message="Are you sure you want to reject <?php echo ucwords($technician['first_name']) ?>'s request?" class="tb-view-list user-reject confirmation-action-xhttp" title="Reject">
                                    <i class="fa fa-trash" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach ?>
                        <?php if ( empty($technicians) ) :?>
                            <tr>
                                <td colspan="5">No result found</td>
                            </tr>
                        <?php endif?>
                    </tbody>
                </table>
            </div>   	
            <div class="pagination-wrap">
                <?php echo $links ?>
            </div>
            <!-- //Technician list table -->

            <!-- no record found -->
            <!-- <div class="no-record text-center">
                <img src="../images/no-found-note.png" alt="Note Paper">
                <p>You have no project.</p>
                <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
            </div> -->
            <!-- no record found end -->

        </div>
    </div>