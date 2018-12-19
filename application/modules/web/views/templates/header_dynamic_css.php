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
        <?php } ?>

        <!-- Css Plugins -->


        <!-- Js Plugins -->
    </head>

    <body>
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
      <li><b>Language</b><i class="fa fa-angle-down" aria-hidden="true"></i>
        <div class="triangle"></div>
        <ul>
          <li><a href="#!"><i class="sl-flag flag-gb"></i> <span>English</span></a></li>
          <li><a href="#!"><i class="sl-flag flag-no"></i> <span>Norsk</span></a></li>
          <li><a href="#!"><i class="sl-flag flag-se"></i> <span>Svenska</span></a></li>
          <li><a href="#!"><i class="sl-flag flag-dk"></i> <span>Dansk</span></a></li>
          <li><a href="#!"><i class="sl-flag flag-de"></i> <span>Deutsch</span></a></li>
          <li><a href="#!"><i class="sl-flag flag-nl"></i> <span>Nederlands</span></a></li>
          <li><a href="#!"><i class="sl-flag flag-fr"></i> <span>Français</span></a></li>
          <li><a href="#!"><i class="sl-flag flag-fi"></i> <span>Suomi</span></a></li>
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
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Quick Calculations</a>
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
                                        in_array((int)$userInfo['user_type'], [BUSINESS_USER, PRIVATE_USER, INSTALLER, WHOLESALER, ELECTRICAL_PLANNER], true)
                                    )
                                ) {?>
                                <li class="dropdown"><a href="javascript:void(0)" class="dropdown-toggle" data-toggle="dropdown">Project</a>
                                    <ul class="dropdown-menu">
                                        <li class="active"><a href="<?php echo base_url("home/projects") ?>">Project list</a></li>
                                        <?php if (isset($userInfo, $userInfo['user_id'])) {?>
                                        <li><a href="<?php echo site_url('home/projects/create'); ?>">Create New Project</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php } ?>
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Companies</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url('home/companies') ?>">All Companies</a></li>
                                        <?php if (isset($userInfo['user_id'])) { ?>
                                            <li><a href="<?php echo base_url('home/companies/favorites') ?>">Favourite Companies</a></li>
                                        <?php } ?>
                                    </ul>
                                </li>
                                <?php if (isset($userInfo, $userInfo['user_id']) && in_array((int)$userInfo['user_type'], [INSTALLER, BUSINESS_USER, PRIVATE_USER], true)) {?>
                                <li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown">Quotes</a>
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
                                <?php if (isset($userInfo['user_id'])) { ?>
                                <li class="dropdown"><a href="#" class="dropdown-toggle" data-toggle="dropdown">Inspirations</a>
                                    <ul class="dropdown-menu">
                                        <li><a href="<?php echo base_url("home/inspirations") ?>">Inspiration List</a></li>
                                        <li><a href="<?php echo base_url("home/inspirations/add") ?>">Add Inspiration</a></li>
                                    </ul>
                                </li>
                                <?php } ?>
                                <?php
                                if (isset($userInfo['user_type']) && in_array($userInfo['user_type'], [INSTALLER, WHOLESALER, ARCHITECT, ELECTRICAL_PLANNER]) && ROLE_OWNER === (int) $userInfo['is_owner']) {
                                    ?>
                                    <li class="dropdown"><a href="" class="dropdown-toggle" data-toggle="dropdown">Manage Technician</a>
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

                            <li class="dropdown notification">
                                <a href="javascript:void(0)" class="btn-basket basket">
                                    <i class="fa fa-bell" style="zoom: 100%;">
                                        <span class="badge badge-icon">0</span>
                                    </i>
                                </a>
                                <div class="dropdown-menu dropdown-menu-xl">
                <!-- Dropdown header -->
                <div class="padd10">
                  <h6 class="text-sm">You have <strong class="text-primary">13</strong> notifications.</h6>
                </div>
                <!-- List group -->
                <div class="list-group list-group-flush">
                  <a href="<?php echo base_url("home/notifications") ?>" class="list-group-item list-group-item-action">
                    <div class="d_flex">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="<?php echo base_url("public/images/team-1.jpg") ?>" class="avatar">
                      </div>
                      <div class="col ml10">
                        <div class="d__flex">
                          <div>
                            <h4 class="">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p>Let's meet at Starbucks at 11:30. Wdyt?</p>
                      </div>
                    </div>
                  </a>
                  <a href="<?php echo base_url("home/notifications") ?>" class="list-group-item list-group-item-action">
                    <div class="d_flex">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="<?php echo base_url("public/images/team-2.jpg") ?>" class="avatar">
                      </div>
                      <div class="col ml10">
                        <div class="d__flex">
                          <div>
                            <h4 class="">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p>A new issue has been reported for Argon.</p>
                      </div>
                    </div>
                  </a>
                  <a href="<?php echo base_url("home/notifications") ?>" class="list-group-item list-group-item-action">
                    <div class="d_flex">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="<?php echo base_url("public/images/team-1.jpg") ?>" class="avatar">
                      </div>
                      <div class="col ml10">
                        <div class="d__flex">
                          <div>
                            <h4 class="">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p>Your posts have been liked a lot.</p>
                      </div>
                    </div>
                  </a>
                  <a href="<?php echo base_url("home/notifications") ?>" class="list-group-item list-group-item-action">
                    <div class="d_flex">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="<?php echo base_url("public/images/team-2.jpg") ?>" class="avatar">
                      </div>
                      <div class="col ml10">
                        <div class="d__flex">
                          <div>
                            <h4 class="">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p>Let's meet at Starbucks at 11:30. Wdyt?</p>
                      </div>
                    </div>
                  </a>
                  <a href="<?php echo base_url("home/notifications") ?>" class="list-group-item list-group-item-action">
                    <div class="d_flex">
                      <div class="col-auto">
                        <!-- Avatar -->
                        <img alt="Image placeholder" src="<?php echo base_url("public/images/team-1.jpg") ?>" class="avatar">
                      </div>
                      <div class="col ml10">
                        <div class="d__flex">
                          <div>
                            <h4 class="">John Snow</h4>
                          </div>
                          <div class="text-right text-muted">
                            <small>2 hrs ago</small>
                          </div>
                        </div>
                        <p>A new issue has been reported for Argon.</p>
                      </div>
                    </div>
                  </a>               
                </div>
                <!-- View all -->
                <a href="<?php echo base_url("home/notifications") ?>" class="view_all">View all</a>
              </div>
                            </li>
                        </ul>
                    </div>

                </div>
            </nav>
        </header>
        <!-- //header -->
        <div class="main-container">