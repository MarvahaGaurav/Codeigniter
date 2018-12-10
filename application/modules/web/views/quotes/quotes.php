<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/' .$projectId) ?>">Details</a></li>
            <li class="active">Quotes</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Quotes</h1>
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
                <input type="text" class="search-box" id="search-box" placeholder="Search Project">
                <input type="submit" value="Search" class="search-btn" />
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
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($quotes as $quote) { ?>
                    <tr>
                        <td><?php echo $quote['company_name'] ?></td>
                        <td class="">$<?php echo $quote['quotation_price']['total'] ?></td>
                        <td  class="text-nowrap action-user">
                            <a href="javascript:void(0)" class="project-action" title="View"><i class="fa fa-eye"></i></a>
                            <a href="javascript:void(0)" data-json<?php echo $quotation['quotation_data'] ?> class="project-action confirmation user-accept" title="Accept"><i class="fa fa-check"></i></a>
                            <a href="javascript:void(0)" data-json<?php echo $quotation['quotation_data'] ?> class="project-action confirmation user-reject" title="Reject"><i class="fa fa-remove"></i></a>
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