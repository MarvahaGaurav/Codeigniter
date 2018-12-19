<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Project</a></li>
            <li class="active">Details</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Projects Details</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficent and environmental friendly, in combination with a creation of the  ambiance that you need, always keeping in mind that luminaires have.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title2">
            <h3><?php echo $project['name']; ?></h3>
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
                        <th class="">Location</th>
                        <?php if (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && $isRequested) { ?>
                        <th class="text-center">Quotes Count</th>
                        <?php } ?>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td class="op-semibold"><?php echo $project['number']; ?></td>
                        <td class="op-semibold"><?php echo $project['name']; ?></td>
                        <td class="op-semibold text-center"><?php echo $project['levels']; ?></td>
                        <td class=""><?php echo $project['address'] ?></td>
                        
                        <?php if (empty($project['requests']) && ((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) ) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $userInfo['is_owner']==ROLE_OWNER )) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $permission['project_edit']==1 && $userInfo['is_owner']=ROLE_EMPLOYEE))) && $isRequested) { ?>
                        <td class="op-semibold text-center"><?php echo $quoteCount ?> Quote(s)</td>
                        <?php } ?>
                        <td class="op-semibold">
                           <?php if(1) { ?>
                            <a href="<?php echo base_url('home/projects/' . encryptDecrypt($project['project_id']) . '/edit') ?>" class="project-action"><i class="fa fa-pencil"></i></a>
                           <?php } ?>
                            
                            <?php if (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) && $isRequested) { ?>
                            <a href="<?php echo base_url('home/projects/' . $projectId . '/quotations') ?>" class="project-action"><i class="fa fa-quote-right"></i></a>
                            <?php } ?>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <!-- //Project list table -->

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3 class="pull-left">Room List</h3>
            <div class="button-wrapper-two pull-right">
                <a href="<?php echo base_url("home/projects/" . encryptDecrypt($project['project_id']) . "/levels"); ?>" class="custom-btn btn-width save">Levels</a>
            </div>
        </div>
        <!-- Caption before section -->
        <!-- <div class="clearfix"></div> -->
        <!-- Project list table -->
        <?php if (!empty($rooms)) { ?>
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
                                <div class="d-flex">
                                    <img src="http://smartguide-staging.applaurels.com/public/images/placeholder/no-found-ico-2.svg" />
                                <span class="td-room-type op-semibold"><?php echo $room['name']; ?></span>
                                </div>
                            </td>
                            <td><?php echo $room['length'] . " M x " . $room['width'] . " M x " . $room['height'] . "M"; ?></td>
                            <td class="text-center"><?php echo count($room['products']); ?></td>
                            <td class="op-semibold">
                                <a href="<?php echo base_url("home/projects/view-result/" . encryptdecrypt($room['project_room_id'])); ?>" class="tb-view-list" title="View <?php echo $room['name']; ?> Result">View Result</a>
                            </td>
                        </tr>
                        <?php
                    }
                    ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
        <!-- //Project list table -->

        <?php if (empty($rooms)) { ?>
        <div class="no-record text-center">
            <img src="<?php echo base_url("public/images/placeholder/no-found-ico-2.svg"); ?>" alt="Note Paper">
            <p><?php echo $this->lang->line('no_rooms_in_this_project') ?></p>
            <p>Tap on <a href="<?php echo base_url("home/projects/" . encryptDecrypt($project['project_id']) . "/levels"); ?>" class="page-link">Levels</a> button to view levels and add rooms.</p>
            <!--<p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>-->
        </div>
        <?php } ?>

    </div>
</div>
