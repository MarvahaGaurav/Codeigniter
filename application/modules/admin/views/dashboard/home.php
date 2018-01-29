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
    
    <!-- totalwrapper-section-->
    <div class="totalwrapper-section">
        <div class="row">
            <a href="<?php echo base_url().'admin/users'?>">
            <div class="col-lg-4">
                <div class="total-status-wrapper green clearfix">
                    <div class="total-usersicon"><i class="fa fa-users"></i></div>
                    <div class="total-numbers">Users
                        <span class="total-userstxt"><?php echo $userCount ?></span>
                    </div>
                    <!--<div class="total-newusers-status green">
                        <p class="total-newusers">New Users</p>
                        <p class="total-userscount">385</p>
                    </div>-->
                </div>
            </div>
            </a>
            <a href="<?php echo base_url().'admin/technician'?>">
            <div class="col-lg-4">
                <div class="total-status-wrapper green clearfix">
                    <div class="total-usersicon"><i class="fa fa-users"></i></div>
                    <div class="total-numbers">Technicians
                        <span class="total-userstxt"><?php echo $technicianCount ?></span>
                    </div>
                    <!--<div class="total-newusers-status green">
                        <p class="total-newusers">New Merchants</p>
                        <p class="total-userscount">100</p>
                    </div>-->
                </div>
            </div>
            </a>
            <a href="javascript:void(0)">
            <div class="col-lg-4">
                <div class="total-status-wrapper green clearfix">
                    <div class="total-usersicon"><i class="fa fa-users"></i></div>
                    <div class="total-numbers">Projects
                        <span class="total-userstxt"><?php echo $projectCount ?></span>
                    </div>
                    <!--<div class="total-newusers-status green">
                        <p class="total-newusers">New Merchants</p>
                        <p class="total-userscount">100</p>
                    </div>-->
                </div>
            </div>
            </a>
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

</div>

<script src="<?php echo base_url()?>public/js/datepicker.min.js"></script>
<!--<script src="<?php //echo base_url()?>public/js/highcharts.js"></script>
<script src="<?php //echo base_url()?>public/js/custom-dashboard.js"></script>-->