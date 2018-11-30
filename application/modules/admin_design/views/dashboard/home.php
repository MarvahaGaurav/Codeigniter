<link href="<?php echo base_url()?>public/css/plugin/datepicker.css">

<!-- alert -->
<?php if (null !== $this->session->flashdata("greetings")) { ?>
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
            <div class="col-lg-12">
                <h3 class="section-heading ts-numberof-heading">Total Number Of</h3>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper purple clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers">509
                            <span class="ts-userstxt">Users</span>
                            <!-- <span class="total-userstxt"><?php //echo $userCount ?></span> -->
                        </div>
                        <p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>
                    </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper yellow clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers">200
                            <span class="ts-userstxt">Technician</span>
                        </div>
                        <p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>
                    </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper red clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers">999+
                            <span class="ts-userstxt">Projects</span>
                        </div>
                        <p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>
                    </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- //totalwrapper-section-->

    <!-- totalwrapper-section-->
    <div class="totalwrapper-section">
        <div class="row">
            <div class="col-lg-12">
                <div class="section-wrapper">
                    <form>
                        <div class="clearfix">
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="label-txt" for="email">Start Date:</label>
                                    <div class="form-blk">
                                        <!-- calendar -->
                                        <input type="text" placeholder="From" id="dpd1" name="dpd1" value="" class="form-field">
                                        <label class="ficon ficon-right" for="dpd1"><i class="fa fa-calendar"></i></label>
                                        <!-- //calendar -->
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-md-4 col-sm-6">
                                <div class="form-group">
                                    <label class="label-txt" for="email">End Date:</label>
                                    <div class="form-blk">
                                        <!-- calendar -->
                                        <input type="text" placeholder="To" id="dpd2" name="dpd2" value="" class="form-field">
                                        <label class="ficon ficon-right" for="dpd2"><i class="fa fa-calendar"></i></label>
                                        <!-- //calendar -->
                                    </div>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            <div class="col-lg-12">
                <h3 class="section-heading ts-numberof-heading">Total Number Of</h3>
            </div>
            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper purple clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers">10
                            <span class="ts-userstxt">Users</span>
                        </div>
                        <p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>
                    </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper yellow clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers">3
                            <span class="ts-userstxt">Technician</span>
                        </div>
                        <p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>
                    </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>

            <div class="col-lg-4 col-md-4">
                <div class="ts-wrapper red clearfix">
                    <div class="ts-content">
                        <div class="ts-numbers">105
                            <span class="ts-userstxt">Projects</span>
                        </div>
                        <p class="ts-description">
                            Lorem Ipsum is simply dummy text of the printing and typesetting industry.
                        </p>
                    </div>
                    <div class="ts-thumbnail" style="background:url('public/images/ts-img1.jpg')"></div>
                </div>
            </div>
        </div>
    </div>
    <!-- //totalwrapper-section-->

</div>

<script src="<?php echo base_url()?>public/js/plugin/datepicker.min.js"></script>
<script src="<?php echo base_url()?>public/js/custom-dashboard.js"></script>
