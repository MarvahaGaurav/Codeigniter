<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/'.$projectId) ?>">Details</a></li>
            <li class="active">Received Quotes</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Received Quotes</h1>
            <p class="prj-description">SG Lighting has vast experience of and expertise in a wide range of different types of projects, such as schools, hospitals, offices, industry, retail and outdoor lighting. Under
                    each type of project in the overview below, there are references to the various areas, as well as product recommendations.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3 class="pull-left">Received Quotes</h3>
            <div class="search-wrapper search-wrapper-width-1 fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <form id="search-form" method="GET" action="">
                    <input type="text" name="search" class="search-box" value="<?php echo html_escape($search) ?>" data-redirect="<?php echo base_url("home/projects/".$projectId.'/quotations') ?>" id="search-box"  placeholder="Search Quotes">
                    <input type="submit" value="Search" class="search-btn" />
                </form>
            </div>
        </div>
        <!-- Caption before section -->

        <!-- Project list table -->
        <?php if (!empty($quotes)) { ?>
        <div class="table-responsive table-wrapper" id="scrollbar-inner">
            <table cellspacing="0" class="table-custom">
                <thead>
                    <tr>
                        <th>Company Name</th>
                        <th>Quoted Price</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($quotes as $quote) { ?>
                    <tr>
                        <td><?php echo $quote['company_name'] ?></td>
                        <td class="">$<?php echo $quote['quotation_price']['total'] ?></td>
                        <td><?php if($quote['status']==QUOTATION_STATUS_QUOTED) { echo "Pending";} else if($quote['status']==QUOTATION_STATUS_APPROVED) { echo "Approved";} else if($quote['status']==QUOTATION_STATUS_REJECTED) { echo "Rejected";}?></td>
                        <td  class="text-nowrap action-user">
                            <a href="<?php echo base_url("/home/projects/".encryptDecrypt($quote['project_id'], 'encrypt')."/levels") ?>" class="project-action" title="View"><i class="fa fa-eye"></i></a>
                            
                            <?php if($quote['status']==QUOTATION_STATUS_QUOTED) { ?>
                                <a href="javascript:void(0)"  id="approve" data-csrf='<?php echo $csrf ?>' class="confirmation-action-xhttp project-action" data-json='<?php echo $quote['quotation_data'] ?>' title="<?php echo $this->lang->line('approve') ?>" data-url="<?php echo base_url("xhttp/quotes/approve") ?>" data-target="#quotes-<?php echo $quote['quotation_id'] ?>"  data-redirect="<?php echo base_url(uri_string()) ?>" data-title="<?php echo $this->lang->line('approve-quote-title') ?>" data-message="<?php echo $this->lang->line('approve_quote_confirmation') ?>">
                                <i class="fa fa-check"></i>
                                </a>

                                <a href="javascript:void(0)" id="reject" data-csrf='<?php echo $csrf ?>' class="confirmation-action-xhttp project-action" data-json='<?php echo $quote['quotation_data'] ?>' title="<?php echo $this->lang->line('reject') ?>" data-url="<?php echo base_url("xhttp/quotes/reject") ?>" data-target="#quotes-<?php echo $quote['quotation_id'] ?>"  data-redirect="<?php echo base_url(uri_string()) ?>" data-title="<?php echo $this->lang->line('reject-quote-title') ?>" data-message="<?php echo $this->lang->line('reject_quote_confirmation') ?>">
                                <i class="fa fa-remove"></i>
                                </a>

                                
                            <?php } ?>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>   	
        <?php } ?>
        <!-- //Project list table -->

        <?php if (empty($quotes)) { ?>
        <!-- no record found -->
        <div class="no-record text-center">
            <img src="<?php echo base_url('public/images/placeholder/no-found-ico-1.svg') ?>" alt="<?php echo $this->lang->line('no_quotation_found') ?>">
            <p><?php echo $this->lang->line('not_received_any_quotes') ?></p>
        </div>
        <!-- no record found -->
        <?php } ?>

    </div>
</div>