<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="project-list.html">Project</a></li>
            <li class="active">Projects Details</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Projects Details</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficent and environmental friendly, in combination with a creation of the  ambiance that you need, always keeping in mind that luminaires have.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title2">
            <h3>Johnson & Sons</h3>
        </div>
        <!-- Caption before section -->

        <!-- Project list table -->
        <div class="table-responsive table-wrapper table-mb" id="scrollbar-inner1">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th class="th-first">Project Number</th>
                        <th>Name</th>
                        <th class="text-center">Project Level</th>
                        <th class="text-center">Quotes Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="op-semibold"><?php echo $project['number']; ?></td>
                        <td class="op-semibold"><?php echo $project['name']; ?></td>
                        <td class="op-semibold text-center"><?php echo $project['levels']; ?></td>
                        <td class="op-semibold text-center">0 Quotes</td>
                        <td class="op-semibold">
                            <!--<a href="javascript:void(0)" class="tb-view-list">View Quotes</a>-->
                            View Quotes
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- //Project list table -->

        <!-- Caption before section -->
        <div class="section-title">
            <h3>Room List</h3>
        </div>
        <!-- Caption before section -->

        <!-- Project list table -->
        <div class="table-responsive table-wrapper" id="scrollbar-inner2">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>Room Type</th>
                        <th>Room Dimension</th>
                        <th class="text-center">No. of Products</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($rooms as $room) {
                        ?>
                        <tr>
                            <td class="td-thumb text-nowrap">
                                <img src="http://smartguide-staging.applaurels.com/public/images/placeholder/no-found-ico-2.svg" />
                                <span class="td-room-type op-semibold"><?php echo $room['name']; ?></span>
                            </td>
                            <td><?php echo $room['length'] . " M x " . $room['width'] . " M x " . $room['height'] . "M"; ?></td>
                            <td class="text-center"><?php echo count($room['products']); ?></td>
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
                            ?>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <!-- //Project list table -->


        <!-- no record found -->
        <!-- <div class="no-record text-center">
            <img src="../../images/no-found-note.png" alt="Note Paper">
            <p>You have no project.</p>
            <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
        </div> -->
        <!-- no record found -->

    </div>
</div>
