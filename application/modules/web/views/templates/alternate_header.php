<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">
        <title>Smart Guide</title>
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="description" content="Smart Guide">
        <meta name="author" content="Smart Guide">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <link href="public/images/favicon.png" type="image/png" rel="icon">

        <!-- Css Plugins -->
        <link rel="stylesheet" href="public/css/web/plugins/bootstrap.min.css">
        <?php if ( isset($owl) ) { ?>

        <link rel="stylesheet" href="public/css/jquery.scrollbar.css">
        <link rel="stylesheet" href="public/css/owl.carousel.min.css" >
        <?php } ?>

        <!-- Custom Css Plugin -->
        <link rel="stylesheet" href="public/css/web/common.css">        
        <link rel="stylesheet" href="public/css/web/style.css">
        <link rel="stylesheet" href="public/css/web/sgmedia.css">
        
        <script>
            var smartguide = {};
            smartguide.baseUrl = "<?php echo base_url() ?>";
            if (location.hostname == "localhost")
                var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/';
            else
                var domain = location.protocol + '//' + location.hostname + (location.port ? ':' + location.port : '') + '/';
        </script>
    </head>

    <body>

        <!-- header -->
        <header>
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
                            <a class="navbar-brand" href="<?php echo base_url(); ?>web/home">
                                <img src="public/images/logo.png" alt="logo" />
                            </a>
                        </div>

                        <!-- Collect the nav links, forms, and other content for toggling -->
                        <div class="collapse navbar-collapse" id="bs-example-navbar-collapse-1">
                            <div class="navbar-topheader-menu clearfix">
                                <ul class="nav navbar-nav">
                                    <li><a href="javascript:void(0)">PROFESSIONAL LIGHTING</a></li>
                                    <li><a href="javascript:void(0)">RESIDENTIAL LIGHTING</a></li>
                                </ul>
                                <ul class="nav navbar-nav navbar-right">
                                    
                                    <?php if(!empty($userInfo['user_id'])){ ?>
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
                                    <?php }else{ ?>
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
                                <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Project</a>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="<?php echo base_url("home/projects") ?>">Project list</a></li>
                                        <li><a href="javascript:void(0)">Create New Project</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Quick Calculations</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">Application</a></li>
                                        <li><a href="#">Lux Values</a></li>
                                        <li><a href="#">Number Luminaires</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Companies</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="#">All Companies</a></li>
                                        <li><a href="#">Favourite Companies</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown">Quotes</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url("home/quotes") ?>">Quotes List</a></li>
                                        <li><a href="#">Awiating</a></li>
                                        <li><a href="#">Submitted</a></li>
                                        <li><a href="#">Approved</a></li>
                                    </ul>
                                </li>
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Inspirations</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url("home/inspirations") ?>">Inspiration List</a></li>
                                        <?php if (isset($userInfo['user_type']) &&
                                        in_array($userInfo['user_type'], [INSTALLER, ARCHITECT, ELECTRICAL_PLANNER]) &&
                                        (ROLE_OWNER === (int)$userInfo['is_owner'] || (isset($employee_permission['insp_add']) && (int)$employee_permission['insp_add'] == 1))) {
                                          ?>
                                        <li><a href="<?php echo base_url("home/inspirations/add") ?>">Add Inspiration</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php if ( isset($userInfo['user_type']) && in_array($userInfo['user_type'], [INSTALLER, WHOLESALER, ARCHITECT, ELECTRICAL_PLANNER]) && ROLE_OWNER === (int)$userInfo['is_owner'] ) { ?>
                                <li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown">Manage Technician</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url("home/technicians") ?>">Technician List</a></li>
                                        <li><a href="<?php echo base_url("home/technicians/requests") ?>">Request List</a></li>
                                    </ul>
                                </li>
                                <?php }?>
                            </ul>
                        </div>

                        <ul class="nav navbar-nav navbar-right navbar-search-link">
                            <li>
                                <span id="searchico-for-mob"><i class="fa fa-search"></i></span>
                                <form role="search" class="app-search">
                                    <input type="text" placeholder="Search..." class="form-control" id="search-input-field"> 
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
                        </ul>
                    </div>

                </div>
            </nav>
        </header>
        <!-- //header -->
    <div class="main-container">