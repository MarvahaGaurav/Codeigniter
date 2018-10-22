<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="javascript:void(0)">Project</a></li>
            <li><a href="javascript:void(0)">Create New Project</a></li>
            <li><a href="javascript:void(0)">Select Application</a></li>
            <li class="active">Rooms</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Rooms</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficent and environmental friendly, in combination with a creation of the  ambiance that you need, always keeping in mind that luminaires have.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3 class="pull-left">Room List</h3>
            <div class="button-wrapper-two pull-right">
                <?php
                if ($is_edit) {
                    ?>
                    <a href="<?php echo base_url("home/projects/" . $application_id . "/select-room-type"); ?>" class="custom-btn btn-width save">
                        <i class="fa fa-plus fa-p-circle"></i>Add Room
                    </a>
                <?php } ?>
            </div>
        </div>
        <!-- Caption before section -->
        <?php
        if (count($rooms)) {
            $first = $rooms[0];
            ?>
            <!-- Project list table -->
            <div class="table-responsive table-wrapper" id="scrollbar-inner">
                <table cellspacing="0" class="table-custom">
                    <thead>
                        <tr>
                            <th>Room Type</th>
                            <th>Room Dimension</th>
                            <?php
                            if ($is_edit) {
                                ?>
                                <th class="text-center">No. of Products</th>
                                <?php
                            }
                            ?>
                            <th class="text-center">Products</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        foreach ($rooms as $room) {
//                        print_r($room);
                            ?>
                            <tr>
                                <td class="td-thumb text-nowrap">
                                    <img src="http://smartguide-staging.applaurels.com/public/images/placeholder/no-found-ico-2.svg" />
                                    <span class="td-room-type op-semibold"><?php echo $room['name']; ?></span>
                                </td>
                                <td><?php echo $room['length'] . " M x " . $room['width'] . " M x " . $room['height'] . "M"; ?></td>

                                <?php
                                if ($is_edit) {
                                    ?>
                                    <td class="text-nowrap text-center">
                                        <!-- Change the `data-field` of buttons and `name` of input field's for multiple plus minus buttons-->
                                        <div class="input-group plus-minus-input">
                                            <input class="input-group-field input-square-space" type="number" name="quantity" value="0">
                                            <div class="input-group-button btn-square">
                                                <button type="button" class="button hollow square" data-quantity="oplus" data-field="quantity">
                                                    <i class="fa fa-plus" aria-hidden="true"></i>
                                                </button>
                                            </div>
                                        </div>
                                    </td>
                                    <td class="op-semibold">
                                        <a href="javascript:void(0)" class="tb-view-list" title="View List">View List</a>
                                    </td>
                                    <td class="op-semibold">
                                        <a href="<?php echo base_url("home/projects/" . $application_id . "/room-edit/" . encryptdecrypt($room['project_room_id'])); ?>" class="tb-view-list" title="Edit">Edit</a>
                                    </td>
                                    <?php
                                }
                                else {
                                    ?>
                                    <td>1</td>
                                    <?php
                                    if ('' == $room['fast_calc_response']) {
                                        ?>
                                        <td class="op-semibold">--</td>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <td class="op-semibold">
                                            <a href="<?php echo base_url("home/projects/view-result/" . encryptdecrypt($room['project_room_id'])); ?>" class="tb-view-list" title="View <?php echo $room['name']; ?> Result">View Result</a>
                                        </td>
                                        <?php
                                    }
                                }
                                ?>

                            </tr>
                            <?php
                        }
                        ?>
                    </tbody>
                </table>
            </div>

            <!-- //Project list table -->

            <!-- Caption before section -->
            <div class="section-title clearfix">
                <div class="button-wrapper">
                    <?php
                    if ($is_edit) {
                        ?>
                        <a data-id="<?php echo encryptDecrypt($project_id); ?>" href="javascript:void(0)" name="evaluate_btn" id="evaluate_btn" class="custom-btn btn-margin btn-width save">
                            Evaluate
                        </a>
                        <?php
                    }
                    else {
                        ?>
                        <a data-spid="<?php echo encryptDecrypt($project_id); ?>" href = "javascript:void(0)" name = "request_btn" id = "request_btn" class = "custom-btn btn-margin btn-width save">
                            Request Quotes
                        </a>
                        <?php
                    }
                    ?>

                </div>
            </div>
            <?php
        }
        ?>
        <input type="hidden" name="<?php echo $csrfName; ?>" id="<?php echo $csrfName; ?>" value="<?php echo $csrfToken; ?>">
        <!-- Caption before section -->
        <?php if ( ! count($rooms)) {
            ?>
            <!-- no record found -->
            <div class="no-record text-center">
                <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg"); ?>" alt="Note Paper">
                <p>You have no room.</p>
                <p>Tap on <a href="<?php echo base_url("home/projects/" . $application_id . "/select-room-type"); ?>" class="page-link">Add Room</a> button to add a room.</p>
            </div>
            <!-- no record found -->
            <?php
        }
        ?>

        <!-- request-send modal -->
        <div id="request-send" class="modal" tabindex="-1" role="dialog">
            <div class="modal-dialog modal-custom" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <p class="succeed2"><img src="<?php echo base_url("public/images/plain-ico.png"); ?>" alt="Enevelop"></p>
                        <h5 class="modal-title text-center">Request Sent!</h5>
                        <p class="modal-action-message">Your request has been sent successfully to the technicians in your vicinity.</p>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-button-wrapper text-center">
                        <input name="close_modal"  id="close_modal" type="button" value="OK" class="custom-btn btn-margin btn-width save">
                    </div>
                </div>
            </div>
        </div>
        <!-- modal end -->
    </div>
</div>