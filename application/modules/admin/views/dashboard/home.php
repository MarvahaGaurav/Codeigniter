<link href="<?php echo base_url()?>public/css/datepicker.css">

<!-- alert -->
<?php if ( null !== $this->session->flashdata("greetings")) { ?>
<div class="alert alert-success" role="alert">
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
    <h4 class="alert-heading"><?php echo $this->session->flashdata("greetings") ?></h4>
    <p><?php echo $this->session->flashdata("message") ?></p>
</div>
<?php } ?>
        <!-- //alert -->

<div class="inner-right-panel">
    <div class="totalwrapper-section">
    <div class="row">
            
            <div class="col-lg-12">
                <div class="section-wrapper">
                    <form method="GET" action="" id="dashboard-form">
                        <div class="clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="label-txt" for="email">Start Date:</label>
                                    <div class="form-blk">
                                        <!-- calendar -->
                                        <input type="text" placeholder="From" id="dpd3" name="start_date" value="<?php echo $start_date ?>" class="form-field">
                                        <label class="ficon ficon-right" for="dpd3"><i class="fa fa-calendar"></i></label>
                                        <!-- //calendar -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="label-txt" for="email">End Date:</label>
                                    <div class="form-blk">
                                        <!-- calendar -->
                                        <input type="text" placeholder="To" id="dpd4" name="end_date" value="<?php echo $end_date ?>" class="form-field">
                                        <label class="ficon ficon-right" for="dpd4"><i class="fa fa-calendar"></i></label>
                                        <!-- //calendar -->
                                     </div>
                                </div>
                            </div>
                            <div class="col-lg-2 col-md-2 col-sm-4">
                                <div class="button-wrap text-center">
                                    <input type="submit" class="commn-btn save" id="filter-button" name="" value="Apply">
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>  
    </div>
    <!-- totalwrapper-section-->
    <div class="totalwrapper-section">
        <div class="row">
          
            <!--<div class="col-lg-12">
                <h3 class="section-heading ts-numberof-heading">Total Number Of</h3>
            </div>-->
            <div class="col-lg-4 col-md-4">
                <a href="<?php echo base_url().'admin/users'?>">
                    <div class="ts-wrapper purple clearfix">
                        <div class="ts-content">
                            <div class="ts-numbers"><?php echo $userCount ?>
                                <span class="ts-userstxt">Users</span>
                                <!-- <span class="total-userstxt"><?php //echo $userCount ?></span> -->
                            </div>
                            <!--<p class="ts-description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </p>-->
                        </div>
                        <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                    </div>
                </a>
            </div>

            <div class="col-lg-4 col-md-4">
                <a href="<?php echo base_url().'admin/technician?user_type=technician'?>">
                    <div class="ts-wrapper yellow clearfix">
                        <div class="ts-content">
                            <div class="ts-numbers"><?php echo $technicianCount ?>
                                <span class="ts-userstxt">Technician</span>
                            </div>
                            <!--<p class="ts-description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </p>-->
                        </div>
                        <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                    </div>
                </a>
            </div>
            
            <div class="col-lg-4 col-md-4">
                <a href="javascript:void(0)">
                    <div class="ts-wrapper red clearfix">
                        <div class="ts-content">
                            <div class="ts-numbers"><?php echo $projectCount ?>
                                <span class="ts-userstxt">Projects</span>
                            </div>
                            <!--<p class="ts-description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </p>-->
                        </div>
                        <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                    </div>
                </a>
            </div>
        
        </div>
    </div>
    <!-- //totalwrapper-section-->

    <!-- totalwrapper-section-->
    <div class="totalwrapper-section">
        
            <!--<div class="col-lg-12">
                <h3 class="section-heading ts-numberof-heading">Total Number Of</h3>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper purple clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers"><?php echo $userCount ?>
                            <span class="ts-userstxt">Users</span>
                        </div> -->
                        <!--<p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>-->
                    <!-- </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper yellow clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers"><?php echo $technicianCount ?>
                            <span class="ts-userstxt">Technician</span>
                        </div> -->
                        <!--<p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>-->
                   <!--  </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper red clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers"><?php echo $projectCount ?>
                            <span class="ts-userstxt">Projects</span>
                        </div> -->
                        <!--<p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>-->
                   <!--  </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>
        </div>-->
    </div> 
    <!-- //totalwrapper-section-->

    <!-- Graph Section -->
    <?php /* ?>
    <div class="graph-section">
        <div class="row">

            <div class="col-lg-6">
                <div class="number-ofusers-wrapper">
                    <label class="label-txt">Number of Users</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-blk selector">
                                <!-- calendar -->
                                <input type="text" placeholder="From" id="dpd1" name="dpd1" value="" class="form-control form-field">
                                <label class="ficon ficon-right" for="dpd1"><i class="fa fa-calendar"></i></label>
                                <!-- //calendar -->
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-blk selector">
                                <!-- calendar -->
                                <input type="text" placeholder="To" id="dpd2" name="dpd2" value="" class="form-control form-field">
                                <label class="ficon ficon-right" for="dpd2"><i class="fa fa-calendar"></i></label>
                                <!-- //calendar -->
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-blk selector">
                                <!-- Select Picker -->
                                <select placeholder="Select" class="selectpicker form-control">
                                    <option>Select</option>
                                    <option>Daily</option>
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                    <option>Yearly</option>
                                </select>
                                <!-- //Select Picker -->
                            </div>
                        </div>
                    </div>
                    <div class="graph">
                        <div id="chart1" style="min-width:250px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
            <div class="col-lg-6">
                <div class="number-ofusers-wrapper">
                    <label class="label-txt">Number of Merchant</label>
                    <div class="row">
                        <div class="col-lg-4">
                            <div class="form-blk selector">
                                <!-- calendar -->
                                <input type="text" placeholder="From" id="dpd3" name="dpd3" value="" class="form-control form-field">
                                <label class="ficon ficon-right" for="dpd3"><i class="fa fa-calendar"></i></label>
                                <!-- //calendar -->
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-blk selector">
                                <!-- calendar -->
                                <input type="text" placeholder="To" id="dpd4" name="dpd4" value="" class="form-control form-field">
                                <label class="ficon ficon-right" for="dpd4"><i class="fa fa-calendar"></i></label>
                                <!-- //calendar -->
                            </div>
                        </div>
                        <div class="col-lg-4">
                            <div class="form-blk selector">
                                <!-- Select Picker -->
                                <select placeholder="Select" class="selectpicker form-control">
                                    <option>Select</option>
                                    <option>Daily</option>
                                    <option>Weekly</option>
                                    <option>Monthly</option>
                                    <option>Yearly</option>
                                </select>
                                <!-- //Select Picker -->
                            </div>
                        </div>
                    </div>
                    <div class="graph">
                        <div id="chart2" style="min-width:250px; height: 400px; margin: 0 auto"></div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php */ ?>
    <!-- //Graph Section -->

</div>

<script src="<?php echo base_url()?>public/js/plugin/datepicker.min.js"></script>
<script src="<?php echo base_url()?>public/js/custom-dashboard.js"></script>
<!-- <script src="<?php //echo base_url()?>public/js/datepicker.min.js"></script> -->
<!--<script src="<?php //echo base_url()?>public/js/highcharts.js"></script>
<script src="<?php //echo base_url()?>public/js/custom-dashboard.js"></script>-->