<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li class="active">Projects</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Projects</h1>
            <p class="prj-description">SG Lighting has vast experience of and expertise in a wide range of different types of projects, such as schools, hospitals, offices, industry, retail and outdoor lighting. Under each type of project in the overview below, there are references to the various areas, as well as product recommendations.</p>
        </div>

        <!-- project list search -->
        <div class="project-list-wrapper clearfix">
            <h2 class="project-listtxt">Project List</h2>
            <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <form id="search-form" method="GET" action="">
                    <input type="text" name="search" class="search-box" value="<?php echo html_escape($search) ?>" data-redirect="<?php echo base_url("home/projects") ?>" id="search-box"  placeholder="Search Projects">
                    <input type="submit" value="Search" class="search-btn" />
                </form>
            </div>
        </div>
        <!-- //project list search -->
        <!-- Project list table -->
        <div class="table-responsive table-wrapper" id="scrollbar-inner">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>Project Name</th>
                        <th>Project Number</th>
                        <th class="text-center">Project Level</th>
                        <th class="text-center">Project Version</th>
<!--                <th class="text-center">Received Quotes</th>-->
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    foreach ($projects as $key => $project) {
                        ?>
                        <tr>
                            <td class="op-semibold"><?php echo $project['name']; ?></td>
                            <td class="op-semibold"><?php echo $project['number']; ?></td>
                            <td class="text-center"><?php echo $project['levels']; ?></td>
                            <td class="text-center"><?php echo sprintf('%.2f', $project['version']); ?></td>
<!--                            <td class="text-center">v1.1</td>
                            <td class="text-center">20</td>-->
                            <td class="op-semibold">
                                <a href="<?php echo base_url("home/projects/" . encryptDecrypt($project['project_id'])) ?>" class="project-action" title="<?php echo $this->lang->line('view') ?>">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                                <?php if (empty($project['requests'])) { ?>
                                <a href="<?php echo base_url('home/projects/' . encryptDecrypt($project['project_id']) . '/edit') ?>" class="project-action" title="<?php echo $this->lang->line("edit") ?>">
                                    <i class="fa fa-pencil" aria-hidden="true"></i>
                                </a>
                                <?php }?>
                                <a href="javascript:void(0)" class="confirmation-action-xhttp project-action" title="<?php echo $this->lang->line('clone') ?>" data-url="<?php echo base_url("xhttp/projects/clone") ?>" data-target="#project-<?php echo $key ?>" data-json='<?php echo $project['clone_data'] ?>' data-action="clone" data-redirect="<?php echo base_url(uri_string()) ?>" data-title="<?php echo $this->lang->line('clone_project_tilte') ?>" data-message="<?php echo $this->lang->line('clone_project_confirmation') ?>">
                                    <i class="fa fa-clone"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    <?php if (empty($projects)) { ?>
                        <tr>
                            <td colspan="10"><?php echo $this->lang->line("no_projects_found") ?></td>
                        </tr>
                    <?php }?>
                </tbody>
            </table>
        </div>
        <div class="pagination-wrap">
            <?php echo $links ?>
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

