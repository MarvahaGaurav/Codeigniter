<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li>
                <a href="javascript:void(0)">Home</a>
            </li>
            <li class="active">Quotes</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Quotes</h1>
            <p class="prj-description">SG Lighting has vast experience of and expertise in a wide range of different types of projects, such as schools,
                hospitals, offices, industry, retail and outdoor lighting. Under each type of project in the overview below,
                there are references to the various areas, as well as product recommendations.</p>
        </div>

        <!-- tabs section -->
        <div class="tabs multitab">
            <ul>
                <li class="active">
                    <a href="javascript:void(0)">Awaiting Quotes</a>
                </li>
                <li>
                    <a href="<?php echo base_url('home/quotes/submitted') ?>">Submitted Quotes</a>
                </li>
                <li>
                    <a href="<?php echo base_url('home/quotes/approved') ?>">Approved Quotes</a>
                </li>
            </ul>
        </div>
        <div class="clearfix"></div>
        <!-- tabs section end -->

        <!-- project list search -->
        <div class="project-list-wrapper clearfix">
            <h2 class="project-listtxt">Awaiting Quotes</h2>
            <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <form id="search-form" method="GET" action="">
                    <input type="text" name="search" class="search-box" value="<?php echo html_escape($search) ?>" data-redirect="<?php echo base_url("home/quotes/awaiting") ?>" id="search-box"  placeholder="Search Quotes">
                    <input type="submit" value="Search" class="search-btn" />
                </form>
            </div>
        </div>
        <!-- //project list search -->

        <!-- Awaiting Quote table -->
        <?php if (!empty($quotations)) {?>
        <div id="awaitingtab" class="multitable active">
            <div class="table-responsive table-wrapper" id="scrollbar-inner">
                <table cellspacing="0" class="table-custom">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Customer Name</th>
                            <th>Location</th>
                            <th>Received On</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quotations as $quotation) {
                            ?>
                        <tr>
                            <td class="op-semibold"><?php echo $quotation['name']  ?></td>
                            <td><?php echo $quotation['customer_name'] ?></td>
                            <td><?php echo strlen($quotation['address']) > 50?substr($quotation['address'], 0, 50) . '...':$quotation['address'] ?></td>
                            <td><?php echo convert_date_time_format('Y-m-d H:i:s', $quotation['request_created_at'], 'h:i A, M d,Y') ?></td>
                            <td class="op-semibold">
                                <a href="<?php echo base_url("home/quotes/projects/" . encryptDecrypt($quotation['project_id'])) ?>" class="tb-view-list" title="View">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } ?>
        <!-- //Awaiting Quote table -->

        <!-- no record found -->
        <?php if (empty($quotations)) { ?>
        <div class="no-record text-center">
            <img src="<?php echo base_url("public/images/placeholder/no-found-ico-1.svg") ?>" alt="Note Paper">
            <p><?php echo $this->lang->line('no_quotation_found') ?></p>
            <!-- <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p> -->
        </div>
        <?php } ?>
        <!-- no record found end -->
        <div class="pagination-wrap">
            <?php echo $links ?>
        </div>

    </div>
</div>