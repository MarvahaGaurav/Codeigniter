<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">
        <title>Smart Guide</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="<?php echo base_url("public/images/favicon.png") ?>" type="image/png" rel="icon">
        <?php if (isset($css) && is_array($css) && ! empty($css)) { ?>
            <?php foreach ($css as $value) { ?>
                <link rel="stylesheet" href="<?php echo $value ?>">
            <?php } ?>
            <?php
        }
        else {
            ?>
            <link rel="stylesheet" href="<?php echo base_url("public/css/bootstrap.min.css") ?>">
            <link rel="stylesheet" href="<?php echo base_url("public/css/jquery.scrollbar.css") ?>">
            <link rel="stylesheet" href="<?php echo base_url("public/css/web/common.css") ?>">
            <link rel="stylesheet" href="<?php echo base_url("public/css/web/style.css") ?>">
            <link rel="stylesheet" href="<?php echo base_url("public/css/web/sgmedia.css") ?>">
            <link rel="stylesheet" href="<?php echo base_url("public/css/plugin/font-awesome.min.css") ?>">
        <?php } ?>

        <!-- Css Plugins -->


        <!-- Js Plugins -->
    </head>

    <body>
        <?php $activePage = isset($activePage)?$activePage:'' ?>
        <!-- header -->
        <header>
            <span id="something-went-wrong" data-message="<?php echo $this->lang->line('something_went_wrong') ?>"></span>
            <nav class="navbar navbar-default navbar-inverse" role="navigation">
                <div class="container">

                    <div class="navbar-top-link">

                        <!-- Brand and toggle get grouped for better mobile display -->
                        <div class="navbar-header">
                            <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#bs-example-navbar-collapse-1">
                                <span class="sr-only">Toggle navigation</span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                                <span class="icon-bar"></span>
                            </button>
                            <a class="navbar-brand" href="<?php echo base_url(); ?>">
                                <img src="<?php echo base_url("public/images/logo.png") ?>" alt="logo" />
                            </a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <div class="navbar-topheader-menu clearfix">
                            <!--                                
                                <ul class="nav navbar-nav">
                                    <li><a href="javascript:void(0)">PROFESSIONAL LIGHTING</a></li>
                                    <li><a href="javascript:void(0)">RESIDENTIAL LIGHTING</a></li>
                                </ul>
                            -->
                            <div class="sl-nav navbar-right">
                                <ul>
                                    <li><a href="javascript:void(0)" class="lang"><b><?php echo $this->lang->line('language');?></b><i class="fa fa-angle-down" aria-hidden="true"></i></a>
                                        <ul class="locale">
                                        <li data-locale='en'><a href="#!"><i class="sl-flag flag-en"></i> <span>English</span></a></li>
                                        <li data-locale='da'><a href="#!"><i class="sl-flag flag-da"></i> <span>Danish</span></a></li>
                                        <li data-locale='nb'><a href="#!"><i class="sl-flag flag-nb"></i> <span>Norwegian</span></a></li>
                                        <li data-locale='sv'><a href="#!"><i class="sl-flag flag-sv"></i> <span>Swedish</span></a></li>
                                        <li data-locale='fi'><a href="#!"><i class="sl-flag flag-fi"></i> <span>Finnish</span></a></li>
                                        <li data-locale='fr'><a href="#!"><i class="sl-flag flag-fr"></i> <span>French</span></a></li>
                                        <li data-locale='nl'><a href="#!"><i class="sl-flag flag-nl"></i> <span>Dutch</span></a></li>
                                        <li data-locale='de'><a href="#!"><i class="sl-flag flag-de"></i> <span>German</span></a></li>
                                        </ul>
                                    </li>
                                </ul>
                            </div>
                                <ul class="nav navbar-nav navbar-right">

                                    <?php if ( ! empty($userInfo['user_id'])) { ?>
                                        <li class="dropdown dropdown-toggle">
                                            <a href="javascript:void(0)" class="guest"><cite>Hi!</cite> &nbsp;
                                                <span class="user-name"><?php echo ucfirst($userInfo['first_name']); ?></span>
                                                <span class="fa fa-caret-down"></span>
                                            </a>
                                            <ul class="dropdown-menu dropdown-profile">
                                                <li><a href="<?php echo base_url("home/profile/" . encryptDecrypt($userInfo['user_id'])) ?>">My Profile</a></li>
                                                <li><a href="<?php echo base_url("home/settings/" . encryptDecrypt($userInfo['user_id'])) ?>">Settings</a></li>
                                                <li><a href="javascript:void(0)" id="user-logout">Logout</a></li>
                                            </ul>
                                        </li>
                                        <?php
                                    }
                                    else {
                                        ?>
                                        <li>
                                            <a href="javascript:void(0)" class="guest"><cite>Hi!</cite> &nbsp;
                                                <span class="user-name">Guest</span>
                                            </a>
                                        </li>
                                        <li><a href="<?php echo base_url(); ?>login">Login</a></li>
                                        <li><a href="<?php echo base_url(); ?>register">Signup</a></li>
                                    <?php } ?>
                                </ul>
                            </div>

                            <ul class="nav navbar-nav">
                                <li class="dropdown <?php echo $activePage === "quickcalc" ? "active" : "" ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Quick Calculations</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url('home/applications') ?>">Application</a></li>
                                        <li><a href="<?php echo base_url('home/fast-calc/lux') ?>">Lux Values</a></li>
                                        <li><a href="<?php echo base_url('home/fast-calc/luminary') ?>">Number Luminaires</a></li>
                                    </ul>
                                </li>
                                <?php if (
                                    !isset($userInfo, $userInfo) ||
                                    (
                                        isset($userInfo, $userInfo['user_id']) &&
                                        ((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) ) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $userInfo['is_owner']==ROLE_OWNER )) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $permission['project_view']==1 && $userInfo['is_owner']=ROLE_EMPLOYEE)))
                                    )
                                ) {?>
                                <li class="dropdown <?php echo $activePage === "projects" ? "active" : "" ?>"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Project</a>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="<?php echo base_url("home/projects") ?>">Project list</a></li>
                                        <?php if (isset($userInfo, $userInfo['user_id']) && ((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) ) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $userInfo['is_owner']==ROLE_OWNER )) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $permission['project_add']==1 && $userInfo['is_owner']=ROLE_EMPLOYEE)))) {?>
                                        <li><a href="<?php echo site_url('home/projects/create'); ?>">Create New Project</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>
                                <li class="dropdown <?php echo $activePage === "companies" ? "active" : "" ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Companies</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url('home/companies') ?>">All Companies</a></li>
                                        <?php if (isset($userInfo['user_id'])) { ?>
                                            <li><a href="<?php echo base_url('home/companies/favorites') ?>">Favourite Companies</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php if (isset($userInfo, $userInfo['user_id']) && ((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true) ) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $userInfo['is_owner']==ROLE_OWNER )) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $permission['quote_view']==1 && $userInfo['is_owner']=ROLE_EMPLOYEE)))) {?>
                                <li class="dropdown <?php echo $activePage === "quotes" ? "active" : "" ?>"><a href="" class="dropdown-toggle" data-toggle="dropdown">Quotes</a>
                                    <ul class="dropdown-menu">
                                        <?php if (in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER], true)) { ?>
                                        <li><a href="<?php echo base_url("home/quotes") ?>">Quotes List</a></li>
                                        <?php } else {?>
                                        <li><a href="<?php echo base_url('home/quotes/awaiting') ?>">Awaiting</a></li>
                                        <li><a href="<?php echo base_url('home/quotes/submitted') ?>">Submitted</a></li>
                                        <li><a href="<?php echo base_url('home/quotes/approved') ?>">Approved</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php if (isset($userInfo['user_id']) && ((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER, ARCHITECT], true) ) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $userInfo['is_owner']==ROLE_OWNER )) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $permission['insp_view']==1 && $userInfo['is_owner']=ROLE_EMPLOYEE)))) { ?>
                                <li class="dropdown <?php echo $activePage === "inspirations" ? "active" : "" ?>"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Inspirations</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url("home/inspirations") ?>">Inspiration List</a></li>
                                        <?php if (((in_array((int)$userInfo['user_type'], [PRIVATE_USER, BUSINESS_USER, ARCHITECT], true) ) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $userInfo['is_owner']==ROLE_OWNER )) || ((in_array((int)$userInfo['user_type'], [INSTALLER, ELECTRICAL_PLANNER,WHOLESALER], true) && $permission['insp_add']==1 && $userInfo['is_owner']=ROLE_EMPLOYEE)))) { ?>
                                        <li><a href="<?php echo base_url("home/inspirations/add") ?>">Add Inspiration</a></li>
                                        <?php }?>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php
                                if (isset($userInfo['user_type']) && in_array($userInfo['user_type'], [INSTALLER, WHOLESALER, ELECTRICAL_PLANNER]) && ROLE_OWNER === (int) $userInfo['is_owner']) {
                                    ?>
                                    <li class="dropdown <?php echo $activePage === "technicians" ? "active" : "" ?>"><a href="" class="dropdown-toggle" data-toggle="dropdown">Manage Technician</a>
                                        <ul class="dropdown-menu">
                                            <li><a href="<?php echo base_url("home/technicians") ?>">Technician List</a></li>
                                            <li><a href="<?php echo base_url("home/technicians/requests") ?>">Request List</a></li>
                                        </ul>
                                    </li>
                                <?php } ?>
                            </ul>                             
                        </div>

                        <ul class="nav navbar-nav navbar-right navbar-search-link">
                            <li>
                                <span id="searchico-for-mob"><i class="fa fa-search"></i></span>
                                <form role="search" method="GET" action="/home/search" class="app-search">
                                    
                                    <input type="text" placeholder="Search..." class="form-control" id="search-input-field" name="search" value="<?php echo $this->input->get('search_text')?>" autocomplete="off">

                                    <span class="fa fa-search search-ico-default" id="search-default"></span>
                                    <span class="fa fa-times" id="search-ico-close"></span>
                                </form>
                            </li>
                            <li>
                                <button data-toggle="modal" data-target="#basketModal" class="btn-basket basket">
                                    <i class="fa fa-lg fa-file-text-o" style="zoom: 100%;">
                                        <span class="badge badge-icon">0</span>
                                    </i>
                                </button>
                            </li>
                            <?php if (isset($siteNotifications)) { ?>
                            <li class="dropdown notification">
                                <a href="javascript:void(0)" class="btn-basket basket">
                                    <i class="fa fa-bell" style="zoom: 100%;">
                                        <span class="badge badge-icon"><?php echo $notificationCount ?></span>
                                    </i>
                                </a>
                            <?php } ?>
                                <?php if (isset($siteNotifications)) { ?>
                                <div class="dropdown-menu dropdown-menu-xl">
                                <!-- Dropdown header -->
                                <div class="padd10">
                                <h6 class="text-sm">You have <strong class="text-primary"><?php echo $notificationCount ?></strong> notifications.</h6>
                                </div>
                                <!-- List group -->
                            
                                <div class="list-group list-group-flush">
                                    <?php foreach($siteNotifications as $notification) { ?>
                                    <a href="<?php echo $notification['redirection']  ?>" class="list-group-item list-group-item-action">
                                        <div class="d_flex">
                                        <div class="col-auto">
                                            <!-- Avatar -->
                                            <img alt="Image placeholder" src="<?php echo empty($notification['sender']['image'])?base_url('public/images/user-placeholder.png'):$notification['sender']['image'] ?>" class="avatar">
                                        </div>
                                        <div class="col ml10">
                                            <div class="d__flex">
                                            <div>
                                                <h4 class=""><?php echo $notification['name'] ?></h4>
                                            </div>
                                            <div class="text-right text-muted">
                                                <small><?php echo convert_date_time_format("Y-m-d H:i:s", $notification['created_at'], 'g:i A, M d Y') ?></small>
                                            </div>
                                            </div>
                                            <p><?php echo $notification['message'] ?></p>
                                        </div>
                                        </div>
                                    </a>
                                    <?php } ?>
                                </div>
                            
                                <!-- View all -->
                                <a href="<?php echo base_url("home/notifications") ?>" class="view_all">View all</a>
                                </div>
                                <?php } ?>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>
        </header>
        <!-- //header -->
        <div class="main-container">