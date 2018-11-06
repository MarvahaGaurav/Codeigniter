<div class="inner-container">
    <div class="white-wrapper">
        <div class="container">
            <!-- breadcrumb -->
            <ul class="breadcrumb">
                <li><a href="javascript:void(0)">Home</a></li>
                <li><a href="javascript:void(0)">Projects</a></li>
                <li><a href="javascript:void(0)">Create New Project</a></li>
                <li class="active">Project Levels</li>
            </ul>
            <!-- //breadcrumb -->
            <!-- <div class="request-quotation-btn-wrapper">
                <button class="custom-btn btn-width save" type="button">Request Quotation</button>
            </div> -->
            <div class="clearfix"></div>
            <?php foreach ($projectLevels as $level) : ?>
            <div class="white-card-wrapper redirectable clickable <?php echo !(bool)$level['active']?"disabled-level":"" ?>" data-redirect-to="<?php echo base_url("/home/projects/{$projectId}/levels/{$level['level']}/rooms") ?>">
                <div class="col-left">
                    <span class="level-heading">L<?php echo $level['level'] ?></span>
                    <span class="level-count">Level <?php echo $level['level'] ?></span>
                </div>
                <div class="col-right text-right">
                    <div class="action-btn-wrapper">
                        <ul>
                            <?php if ((int)$level['status'] === 0) { ?>
                            <li> <button class="level-btn" type="button" <?php echo (int)$level['room_count'] < 1?"disabled":"" ?> title="<?php echo (int)$level['room_count'] < 1?$this->lang->line("add_rooms_to_mark_as_done"):"" ?>"> Mark as Done </button> </li>
                            <?php }?>
                            <!-- <li> <button class="level-btn"> + Add </button></li> -->
                        </ul>
                    </div>
                </div>
            </div>
            <?php endforeach ?>
            <div class="clearfix"></div>
            <div class="request-quotation-btn-wrapper">
                <button class="col-md-3 custom-btn save" type="button" <?php echo (bool)$all_levels_done?"":"disabled" ?>>Request Quotation</button>
            </div>
        </div>
    </div>
</div>
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








</div>
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