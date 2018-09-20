<link href="<?php echo base_url()?>public/css/datepicker.min.css" rel='stylesheet'>

<div class="inner-right-panel">

    <!--breadcrumb wrap-->
    <div class="breadcrumb-wrap">
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/users">Template Management</a></li>
            <li class="breadcrumb-item"><a href="<?php echo base_url()?>admin/users">Appartment</a></li>
            <li class="breadcrumb-item active">Bathroom</li>
        </ol>
    </div>

    <!--  -->
    <div class="clearfix">
        <div class="row">
            <div class="col-sm-12 col-xs-12">
                <!-- thumb wrapper -->
                <div class="image-view-wrapper img-viewbdr-radius4p img-view-fullp">
                    <div class="image-view img-view-full-12" style="background-image:url('public/images/bathroom-light.jpg')"></div>
                </div>
                <!-- thumb wrapper -->
            </div>
            <div class="col-xs-12">
                <div class="section section-alternate">
                    <div class="row">
                        <div class="user-detail-panel">
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Room Type</label>
                                    <div class="input-holder">
                                        <span class="text-detail">Bathroom</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Room Length</label>
                                    <div class="input-holder">
                                        <span class="text-detail">20 Meter</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Room Width</label>
                                    <div class="input-holder">
                                        <span class="text-detail">18 Meter</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Room Height</label>
                                    <div class="input-holder">
                                        <span class="text-detail">12 Meter</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Workplane Height</label>
                                    <div class="input-holder">
                                        <span class="text-detail">0.75 Meter</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Room Shape</label>
                                    <div class="input-holder">
                                        <span class="text-detail">Rectangle</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-lg-4 col-sm-4 col-xs-12">
                                <div class="form-group">
                                    <label class="admin-label">Lux Value</label>
                                    <div class="input-holder">
                                        <span class="text-detail">550 Lux</span>
                                    </div>
                                </div>
                            </div>
                            <div class="col-sm-12 col-xs-12">
                                <div class="button-wrap">
                                    <button type="button" class="commn-btn cancel" onclick="window.location.href = 'http://localhost/smartguide/AdminPanel/admin/subadmin/edit'">Delete Room</button>
                                    <button type="submit" class="commn-btn save"><a href="<?php echo base_url()?>admin/templatemgmt/edit">Edit Room</a></button>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

</div>

<script type="text/javascript" src="//cdn.jsdelivr.net/momentjs/latest/moment.min.js"></script>
<script src="<?php echo base_url()?>public/js/datepicker.min.js"></script>
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
