<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">

        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0,minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>S G Admin Panel</title>
        <link rel="icon" type="image/png" sizes="32x32" href="public/images/logo.png">
        <!-- Bootstrap Core CSS -->

        <link href="public/css/plugin/bootstrap.min.css" rel='stylesheet'>
        <link href="public/css/layout.css" rel='stylesheet'>
        <link href="public/css/style.css" rel='stylesheet'>
        <link href="public/css/plugin/jquery.mCustomScrollbar.min.css" rel='stylesheet'>
        <link href="public/css/plugin/bootstrap-select.min.css" rel='stylesheet'>
        <link href="public/css/plugin/font-awesome.min.css" rel='stylesheet'>
        <script src="public/js/jquery.min.js"></script>
        <script>
            var projectglobal = {};
            projectglobal.baseurl = "";
            var csrf_token = <?php echo "'" . $this->security->get_csrf_hash() . "'"; ?>;
            var baseUrl = '';
        </script>

    </head>
    <?php
    $this->load->helper('cookie');
    $controller = strtolower($this->router->fetch_class());
    $method = strtolower($this->router->fetch_method());
    $sidebarState = isset($adminInfo["sidebar_state"]) ? $adminInfo["sidebar_state"] : "";
    $sideBar = get_cookie('sideBar');
    $sideBar = isset($sideBar) ? $sideBar : "";
//        echo $controller;die('test');
    ?>
    <body class="<?php echo ($sideBar == 'minimized') ? 'body-sm' : '' ?>">
        <?php //echo '<pre>'; print_r($admininfo); echo '</pre>';?>
        <div class="in-data-wrap">
            <!--left panel-->
            <aside class="sidebar-wrapper<?php /* echo $sidebarState */ ?>">
                <div class="closeSidebar768">x</div>
                <div class="sidebar-menu side-panel">
                    <div class="user-short-detail">
                        <div class="image-view-wrapper img-view80p img-viewbdr-radius">
                            <div id="lefft-logo" class="image-view img-view80" style="background:url('<?php echo (!empty($admininfo['admin_profile_pic'])) ? IMAGE_PATH . $admininfo['admin_profile_pic'] : DEFAULT_IMAGE ?>')"></div>
                        </div>
                        <span class="user-name"><?php echo (!empty($admininfo['admin_name'])) ? $admininfo['admin_name'] : 'Admin'; ?></span>

                        <!-- <figure>
                            <img src="<?php //echo (!empty($admininfo['admin_profile_pic'])) ? IMAGE_PATH . $admininfo['admin_profile_pic'] : DEFAULT_IMAGE ?>" id="lefft-logo">
                        </figure>
                        <span class="user-name"><?php //echo (!empty($admininfo['admin_name'])) ? $admininfo['admin_name'] : 'Admin'; ?></span> -->
                    </div>
                    <div class="left-menu">
                        <ul>
                            <li>
                                <a href="admin/dashboard" class="<?php echo ($controller == 'dashboard') ? 'active' : ''; ?>"  >
                                    <!-- <span class="dashboard_img comm-img"></span> -->
                                    <img src="public/images/svg/dashboard.svg" alt="dashboard">
                                    <label class="nav-txt">Dashboard</label>
                                </a>
                            </li>
                            <?php if ($admininfo['role_id'] == 1) { ?>
                                <li>
                                    <a href="admin/subadmin" class="<?php echo ($controller == 'subadmin') ? 'active' : ''; ?>">
                                        <img src="public/images/svg/sub-users.svg" alt="Subadmin">
                                        <label class="nav-txt">Subadmin</label>
                                    </a>
                                </li>
                            <?php } ?>
                            <li>
                                <a href="admin/users" class="<?php echo ($controller == 'user') ? 'active' : ''; ?>">
                                    <img src="public/images/svg/user.svg" alt="Users">
                                    <label class="nav-txt">User Management</label>
                                </a>
                            </li>
                            <li>
                                <a href="admin/technician" <?php echo ($controller == "technician") ? "class='active'" : "" ?> >
                                    <img src="public/images/svg/cms.svg" alt="Merchant Management">
                                    <label class="nav-txt">Merchant Management</label>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" <?php echo ($controller == "products") ? "class='active'" : "" ?> >
                                    <img src="public/images/svg/cms.svg" alt="Template Management">
                                    <label class="nav-txt">Product Management</label>
                                </a>
                            </li>
                            <li>
                                <a href="javascript:void(0)" <?php echo ($controller == "roomtemplates") ? "class='active'" : "" ?> >
                                    <img src="public/images/svg/cms.svg" alt="Content Management">
                                    <label class="nav-txt">Template Management</label>
                                </a>
                            </li>
                            <li>
                                <a href="admin/cms" class="<?php echo ($controller == 'cms') ? 'active' : ''; ?>" >
                                    <img src="public/images/svg/cms.svg" alt="Content Management">
                                    <label class="nav-txt">Content Management</label>
                                </a>
                            </li>
                            <li>
                                <a href="admin/version" class="<?php echo ($controller == 'version') ? 'active' : ''; ?>" >
                                    <img src="public/images/svg/notification.svg" alt="Notification">
                                    <label class="nav-txt">App Version Management</label>
                                </a>
                            </li>
                            <li>
                                <a href="admin/notification" class="<?php echo ($controller == 'notification') ? 'active' : '';  ?>">
                                    <img src="public/images/svg/notification.svg" alt="Notification">
                                    <label class="nav-txt">Notification</label>
                                </a>
                            </li>

                            <li>
                                <a href="javascript:void(0)" <?php echo ($controller == "message") ? "class='active'" : "" ?> >
                                    <img src="public/images/svg/message.svg" alt="Message">
                                    <label class="nav-txt">Messages</label>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </aside>
            <!--left panel-->

            <!--Header-->
            <header>
                <!--toggle button-->
                <div class="toggleBtn" data-state="<?php echo empty($sidebarState) ? "expanded" : "minimized"; ?>">
                    <span class="line-bar"></span>
                    <span class="line-bar shot-line-br"></span>
                    <span class="line-bar"></span>
                </div>
                <!--toggle button close-->
                <!--nav brand wrap-->
                <div class="nav-brand">
                    <a href="javascripit:void(0)" title="Logo">
                        <span><img src="public/images/logo.png" alt="logo"></span>
                        <div class="logoTxt">Smart Guide
                            <p>Lighting</p>
                        </div>
                    </a>
                </div>
                <!--nav brand wrap close-->
                <!--User Setting-->
                <div class="user-setting-wrap">
                    <ul>
                        <li class="drp">
                            <a href="javascript:void(0)" class="drpactions dropmenu" data-toggle="dropdown">
                                <i class="fa fa-bell-o"></i>
                            </a>
                            <div class="fncy-drp fncy-dropdown-two">
                                <h3 class="notifyTxt">Notifications</h3>
                                <ul>
                                    <li><a href="javascript:void(0)">Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book.</a><span class="pull-right">08 Feb 2018 05:41 AM</span></li>                                   
                                    <li><a href="javascript:void(0)" class="viewallNotifications">View All Notifications</a></li>
                                </ul>
                            </div>
                        </li>
                        <li>
                            <a href="admin/profile"><img src="public/images/setting.svg" title="Setting"></a>
                        </li>
                        <li>
                            <a href="javascript:void(0)" onclick="logoutUser();"><img src="public/images/logout.svg" title="Logout"></a>
                        </li>
                    </ul>
                </div>
                <!--User Setting wrap close-->
            </header>

            <div class="right-panel">
