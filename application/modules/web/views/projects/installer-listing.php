<div class="inner-container">
    <div class="white-wrapper">
        <div class="container">
            <!-- breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="javascript:void(0)">Home</a></li>
                <li><a href="javascript:void">Projects</a></li>
                <li><a href="javascript:void">Levels</a></li>
                <li class="active">Installer Listing</li>
            </ul>
            <!-- //breadcrumb -->
            <!-- <div class="request-quotation-btn-wrapper">
                <button class="custom-btn btn-width save" type="button">Request Quotation</button>
            </div> -->
            <div class="page-heading">
                <h1 class="page-title">Installer List</h1>
                <p class="prj-description"></p>
            </div>
            
            <!-- project list search -->
            <div class="project-list-wrapper clearfix">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 block-div distance-select-div">
                    <div class="form-group">
                        <!-- <label class="labelTxt">Distance</label> -->
                        <div class="form-group-field">
                            <select name="distance" id="distance" data-redirect-to="<?php echo base_url('home/projects/' . $projectId . '/quotation/installers') ?>">
                                <option value="">Select Distance</option>
                                <option value="5" <?php echo (int)$searchRadius == 5?"selected":"" ?>>5kms</option>
                                <option value="10" <?php echo (int)$searchRadius == 10?"selected":"" ?>>10kms</option>
                                <option value="15" <?php echo (int)$searchRadius == 15?"selected":"" ?>>15kms</option>
                                <option value="20" <?php echo (int)$searchRadius == 20?"selected":"" ?>>20kms</option>
                                <option value="25" <?php echo (int)$searchRadius == 25?"selected":"" ?>>25kms</option>
                            </select>
                            <span class="customArrow"></span>
                        </div>
                    </div>
                </div>
                <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                    <span class="fawe-icon fawe-icon-position-right close-ico">
                        <i class="fa fa-times"></i>
                    </span>
                    <form id="search-form" method="GET" action="">
                        <input type="text" name="search" class="search-box" value="<?php echo html_escape(isset($search) ? $search : '') ?>" data-redirect="<?php echo base_url(uri_string()) ?>" id="search-box"  placeholder="Search Companies">
                        <input type="submit" value="Search" class="search-btn" />
                    </form>
                </div>
            </div>
            <div class="clearfix"></div>
            <?php foreach($installers as $installer) { ?>
            <div class="white-card-wrapper installer-listing-wrapper" data-redirect-to="">
                <div class="col-left">
                    <span class="level-heading"><?php echo $installer['company_name'] ?></span>
                </div>
                <div class="col-right text-right">
                    <div class="action-btn-wrapper">
                        <ul>
                            <li class="clickable check-marker" data-company-id="<?php echo $installer['company_id'] ?>" data-title="<?php echo $this->lang->line('installer_selected') ?>" title=""><i class="fa fa-check-circle check-mark not-done-check"></i></li>
                        </ul>
                    </div>
                </div>
            </div>
            <?php } ?>
            <div class="clearfix"></div>
            <div class="request-quotation-btn-wrapper">
                <?php echo form_open(base_url(uri_string()), ['id' => 'request-quotation-form'])?>
                <div id="selected-installers" data-max-count="<?php echo MAXIMUM_REQUEST_COUNTS_PER_PROJECT ?>" data-maximum-message="<?php echo sprintf($this->lang->line('maximum_installer_selection_exceeded'), MAXIMUM_REQUEST_COUNTS_PER_PROJECT) ?>">

                </div>
                <button id="request-quotation-btn" class="col-md-3 custom-btn save" type="submit" data-redirect-to="" data-title="<?php echo $this->lang->line('select_installer_before_quote') ?>" title="<?php echo $this->lang->line('select_installer_before_quote') ?>" disabled>Request Quotation</button>
                <?php echo form_close() ?>
            </div>
        </div>
    </div>
</div>
<style>
    .distance-select-div {
        padding-left: 0px;
    }
    .done-check {
        font-size: 30px;
        color: #e03014;
    }

    .not-done-check {
        font-size: 30px;
        color: rgb(144, 144, 144);
    }

    .white-card-wrapper {
        display: flex;
        background: #fff;
        box-shadow: 0 0 6px #ccc;
        padding: 23px 28px;
        border-radius: 5px;
        margin: 0 0 20px 0;
    }

    .col-left {
        width: 50%;
        padding: 0 10px;
    }

    .col-right {
        width: 50%;
        padding: 0 10px;
    }

    .level-heading {
        font-size: 22px;
        font-weight: 600;
        margin: 0 11px 0 0;
        display: inline-block;
        max-width: 250px;
        text-overflow: ellipsis;
        white-space: nowrap;
        overflow: hidden;
        vertical-align: middle;
    }

    .level-count {
        font-size: 14px;
        color: #e4001b;
        font-weight: 600;
    }

    .action-btn-wrapper ul li {
        display: inline-block;
        margin: 0 10px;
    }


    .action-btn-wrapper .level-btn {
        width: 124px;
        border: 1px solid #e4001b;
        border-radius: 4px;
        padding: 5px 0;
        background: #e4001b;
        color: #fff;
        text-transform: uppercase;
    }

    .action-btn-wrapper .level-btn:hover {
        background: #b7061b;
        box-shadow: 0px 3px 6px #0000001f;
    }
</style>
<style>
.white-card-wrapper {
    display: flex;
    background: #fff;
    box-shadow: 0 0 6px #ccc;
    padding: 23px 28px;
    border-radius: 5px;
    margin: 0 0 20px 0;
}

.col-left {
    width: 50%;
    padding: 0 10px;
}

.col-right {
    width: 50%;
    padding: 0 10px;
}

.level-heading {
    font-size: 22px;
    font-weight: 600;
    margin: 0 11px 0 0;
    display: inline-block;
    max-width: 250px;
    text-overflow: ellipsis;
    white-space: nowrap;
    overflow: hidden;
    vertical-align: middle;
}

.level-count {
    font-size: 14px;
    color: #e4001b;
    font-weight: 600;
}

.action-btn-wrapper ul li {
    display: inline-block;
    margin: 0 10px;
}


.action-btn-wrapper .level-btn {
    width: 124px;
    border: 1px solid #e4001b;
    border-radius: 4px;
    padding: 5px 0;
    background: #e4001b;
    color: #fff;
    text-transform: uppercase;
}

.action-btn-wrapper .level-btn:hover {
    background: #b7061b;
    box-shadow: 0px 3px 6px #0000001f;
}
</style>