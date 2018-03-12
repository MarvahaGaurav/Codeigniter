<link href="<?php echo base_url()?>public/css/datepicker.min.css">

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
    <div class="totalwrapper-section gutter10">
    <div class="row">
            
            <div class="section-wrapper">
                <form method="GET" action="/admin/dashboard" id="dashboard-form">
                    <div class="clearfix">
                        <div class="col-pd-5 col-lg-4 col-md-4 col-sm-6">
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
                        <div class="col-pd-5 col-lg-4 col-md-4 col-sm-6">
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
                        <div class="col-pd-5 col-lg-4 col-md-4 col-sm-4">
                            <div class="form-group">
                                <label class="label-txt" for=""><?php echo "&nbsp;"?></label>
                                <div class="form-blk text-center">
                                    <button  onclick="window.location.href='/admin/dashboard';" type="button" class="commn-btn cancel" id="resetbutton">Reset</button>
                                    <input onclick="$('#dashboard-form').submit();" type="button" class="commn-btn save" id="filter-button" name="" value="Apply">
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>

        </div>  
    </div>
    <!-- totalwrapper-section-->
    <div class="totalwrapper-section gutter10">
        <div class="row">
          
            <div class="col-pd-5 col-lg-12">
                <h3 class="section-heading ts-numberof-heading">Total Number Of</h3>
            </div>
            <div class="col-pd-5 col-lg-4 col-md-4 col-sm-6">
                <a href="<?php echo base_url().'admin/users?startDate='.$start_date.'&endDate='.$end_date?>">
                    <div class="ts-wrapper bgcolor1 clearfix">
                        <div class="ts-content">
                            <div class="ts-numbers"><?php echo $userCount ?>
                                <span class="ts-userstxt">Users</span>
                                <!-- <span class="total-userstxt"><?php //echo $userCount ?></span> -->
                            </div>
                            <!--<p class="ts-description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </p>-->
                        </div>
                        <div class="ts-thumbnail">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </a>
            </div>

            <div class="col-pd-5 col-lg-4 col-md-4 col-sm-6">
                <a href="<?php echo base_url().'admin/technician?user_type=technician&startDate='.$start_date.'&endDate='.$end_date?>">
                    <div class="ts-wrapper bgcolor2 clearfix">
                        <div class="ts-content">
                            <div class="ts-numbers"><?php echo $technicianCount ?>
                                <span class="ts-userstxt">Technician</span>
                            </div>
                            <!--<p class="ts-description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </p>-->
                        </div>
                        <div class="ts-thumbnail">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </a>
            </div>
            
            <div class="col-pd-5 col-lg-4 col-md-4 col-sm-6">
                <a href="<?php echo base_url().'admin/technician?user_type=architect&startDate='.$start_date.'&endDate='.$end_date?>">
                    <div class="ts-wrapper bgcolor3 clearfix">
                        <div class="ts-content">
                            <div class="ts-numbers"><?php echo $architectCount; ?>
                                <span class="ts-userstxt">Architect</span>
                            </div>
                            <!--<p class="ts-description">
                                Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                            </p>-->
                        </div>
                        <div class="ts-thumbnail">
                            <i class="fa fa-users"></i>
                        </div>
                    </div>
                </a>
            </div>
        
            <div class="col-pd-5 col-lg-4 col-md-4 col-sm-6">
                <a href="<?php echo base_url().'admin/technician?user_type=wholesaler&startDate='.$start_date.'&endDate='.$end_date?>">
                <div class="ts-wrapper bgcolor4 clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers"><?php echo $wholesellerCount; ?>
                            <span class="ts-userstxt">Whole seller </span>
                        </div>
                        <!--<p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>-->
                    </div>
                    <div class="ts-thumbnail">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
                </a>
            </div>

            <div class="col-pd-5 col-lg-4 col-md-4 col-sm-6">
                <a href="<?php echo base_url().'admin/technician?user_type=electrical_planner&startDate='.$start_date.'&endDate='.$end_date?>">
                <div class="ts-wrapper bgcolor5 clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers"><?php echo $electricplannerCount; ?>
                            <span class="ts-userstxt">Electric Planner</span>
                        </div>
                        <!--<p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>-->
                    </div>
                    <div class="ts-thumbnail">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
                </a>
            </div>

            <div class="col-pd-5 col-lg-4 col-md-4 col-sm-6">
                <div class="ts-wrapper bgcolor6 clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers"><?php echo $projectCount ?>
                            <span class="ts-userstxt">Projects</span>
                        </div>
                        <!--<p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>-->
                    </div>
                    <div class="ts-thumbnail">
                        <i class="fa fa-users"></i>
                    </div>
                </div>
            </div>
            </div>
        </div>
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

<link rel="stylesheet" href="<?php echo base_url()?>public/css/bootstrap-datetimepicker.css">
<script src="<?php echo base_url()?>public/js/moment-with-locales.js"></script>
<script src="<?php echo base_url()?>public/js/bootstrap-datetimepicker.js"></script>
<script src="<?php echo base_url()?>public/js/custom-dashboard.js"></script>
<!--<script src="<?php //echo base_url()?>public/js/highcharts.js"></script>
<script src="<?php //echo base_url()?>public/js/custom-dashboard.js"></script>-->