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
        <!-- <div class="tabs multitab">
            <ul>
                <li class="active">
                    <a href="#awaitingtab" data-toggle="tab">Awaiting Quotes</a>
                </li>
                <li>
                    <a href="#submittedtab" data-toggle="tab">Submitted Quotes</a>
                </li>
                <li>
                    <a href="#approvedtab" data-toggle="tab">Approved Quotes</a>
                </li>
            </ul>
        </div> -->
        <div class="clearfix"></div>
        <!-- tabs section end -->

        <!-- project list search -->
        <div class="project-list-wrapper clearfix">
            <h2 class="project-listtxt">Quotes</h2>
            <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <form id="search-form" method="GET" action="">
                    <input type="text" name="search" class="search-box" value="<?php echo html_escape($search) ?>" data-redirect="<?php echo base_url("home/quotes") ?>" id="search-box"  placeholder="Search Quotes">
                    <input type="submit" value="Search" class="search-btn" />
                </form>
            </div>
        </div>
        <!-- //project list search -->

        <!-- Awaiting Quote table -->
        <?php if (!empty($quotations)) { ?>
        <div id="awaitingtab" class="multitable active">
            <div class="table-responsive table-wrapper" id="scrollbar-inner">
                <table cellspacing="0" class="table-custom">
                    <thead>
                        <tr>
                            <th>Project Name</th>
                            <th>Location</th>
                            <th>Received On</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($quotations as $quotation) {
                            $token= json_decode($csrf,true);
                            $quotation['csrf_token'] = $token['csrf_token'];
                            ?>
                        <tr>
                            <td class="op-semibold"><?php echo $quotation['project_name'] ?></td>
                            <td><?php echo strlen($quotation['project_address']) > 50?substr($quotation['project_address'], 0, 50) . '...':$quotation['project_address'] ?></td>
                            <td><?php echo convert_date_time_format('Y-m-d H:i:s', $quotation['request_created_at'], 'h:i A, M d,Y') ?></td>
                            <td><?php if($quotation['status']==QUOTATION_STATUS_QUOTED) { echo "Pending";} else if($quotation['status']==QUOTATION_STATUS_APPROVED) { echo "Approved";} else if($quotation['status']==QUOTATION_STATUS_REJECTED) { echo "Rejected";}?></td>
                            <td class="op-semibold" >
                                <a href="javascript:void(0)" class="tb-view-list" title="View">
                                    <i class="fa fa-eye" aria-hidden="true"></i>
                                </a>

                            <?php if($quotation['status']==QUOTATION_STATUS_QUOTED) { ?>
                                <a href="javascript:void(0)" data-csrf='<?php echo $csrf ?>' id="approve" class="confirmation-action-xhttp project-action" data-json='<?php echo json_encode($quotation) ?>' title="<?php echo $this->lang->line('approve') ?>" data-url="<?php echo base_url("xhttp/quotes/approve") ?>" data-target="#quotes-<?php echo $quotation['request_id'] ?>"  data-redirect="<?php echo base_url(uri_string()) ?>" data-title="<?php echo $this->lang->line('approve-quote-title') ?>" data-message="<?php echo $this->lang->line('approve_quote_confirmation') ?>">
                                    Approve
                                </a>

                                <a href="javascript:void(0)" data-csrf='<?php echo $csrf ?>' id="reject" class="confirmation-action-xhttp project-action" data-json='<?php echo json_encode($quotation) ?>'  title="<?php echo $this->lang->line('reject') ?>" data-url="<?php echo base_url("xhttp/quotes/reject") ?>" data-target="#quotes-<?php echo $quotation['request_id'] ?>"  data-redirect="<?php echo base_url(uri_string()) ?>" data-title="<?php echo $this->lang->line('reject-quote-title') ?>" data-message="<?php echo $this->lang->line('reject_quote_confirmation') ?>">
                                    Reject
                                </a>
                            <?php } ?>
                                
                                
                                
                            </td>
                        </tr>
                        <?php } ?>
                    </tbody>
                </table>
            </div>
        </div>
        <?php } ?>

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