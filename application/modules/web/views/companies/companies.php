        <div class="inner-container">
            <div class="container">
                <!-- breadcrumb -->
                <ul class="breadcrumb">
                    <li><a href="javascript:void(0)">Home</a></li>
                    <li class="active">Company</li>
                </ul>
                <!-- //breadcrumb -->

                <div class="page-heading">
                    <h1 class="page-title">Companies</h1>
                    <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficient and environmental friendly, in combination
                            with a creation of the ambiance that you need, always keeping in mind that luminaires have a great impact on the environment.</p>
                </div>

                <!-- technician list search -->
                <div class="project-list-wrapper technician-list clearfix">
                    <h2 class="project-listtxt"></h2>
                    <div class="search-wrapper search-wrapper-width-2 search-wrp-992 fawe-icon-position">
                        <span class="fawe-icon fawe-icon-position-right close-ico">
                            <i class="fa fa-times"></i>
                        </span>
                        <form id="search-form" method="GET" action="">
                            <input type="text" name="search" class="search-box" value="<?php echo html_escape($search) ?>" data-redirect="<?php echo base_url("home/companies") ?>" id="search-box"  placeholder="Search Companies">
                            <input type="submit" value="Search" class="search-btn" />
                        </form>
                    </div>
                </div>
                <!-- //technician list search -->

                <!-- Technician list table -->
                <div class="table-responsive table-wrapper" id="scrollbar-inner">
                    <table cellspacing="0" class="table-custom">
                        <thead>
                            <tr>
                                <th>Company</th>
                                <th class="text-center">City</th>
                                <th class="text-center">Country</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($companies as $company) { ?>
                            <tr>
                                <td class="td-thumb-round text-nowrap">
                                    <div class="thumb-like-dislike">
                                        <div class="thumb-view-wrapper thumb-view-contain thumb-view-p4 img-viewbdr-radius">
                                            <?php if (!empty($company->company_image)) {?>
                                            <img class="thumb-absolute" src="<?php echo $company->company_image ?>" alt="<?php echo $company->company_name ?>" />
                                            <?php } else { ?>
                                            <img class="thumb-absolute" src="<?php echo base_url('public/images/logo.png') ?>" alt="<?php echo $company->company_name ?>" />
                                            <?php } ?>
                                        </div>
                                        <?php if (isset($userInfo, $userInfo['user_id']) && (int)$userInfo['company_id'] !== (int)$company->company_id) { ?>
                                        <div class="favorite-wrapper">
                                            <span data-favorite='<?php echo $company->favorite_data ?>' class="fa fa-heart clickable <?php echo (int)$company->is_favorite === 1?"faa-like":"faa-dislike" ?> heart-position1" aria-hidden="true"></span>
                                        </div>
                                        <?php } ?>
                                    </div>
                                    <a href="<?php echo base_url('home/companies/' . encryptDecrypt($company->company_id)) ?>"><span class="td-technician op-semibold"><?php echo $company->company_name ?></span></a>
                                </td>
                                <td class="text-center text-nowrap"><?php echo $company->city ?></td>
                                <td class="text-center"><?php echo $company->country ?></td>
                                <td  class="text-nowrap action-user">
                                    <a href="<?php echo base_url('home/companies/' . encryptDecrypt($company->company_id)) ?>" class="tb-view-list" title="View">View</a>
                                </td>
                            </tr>
                            <?php } ?>
                            <?php if (empty($companies)) { ?>
                                <tr>
                                    <td colspan="10">No result found</td>
                                </tr>
                            <?php  } ?>
                        </tbody>
                    </table>
                </div>      
                <div class="pagination-wrap">
                    <?php echo $links ?>
                </div>
                <!-- //Technician list table -->

                <!-- no record found -->
                <!-- <div class="no-record text-center">
                    <img src="../../images/no-found-note.png" alt="Note Paper">
                    <p>You have no project.</p>
                    <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
                </div> -->
                <!-- no record found end -->

            </div>
        </div>