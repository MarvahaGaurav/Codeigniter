<!--aside-->
<div class="right-panel">
    <!--Header-->
    <header>
        <!--toggle button-->
        <div class="toggle-btn-wrap">
            <span class="line-bar"></span>
            <span class="line-bar shot-line-br"></span>
            <span class="line-bar"></span>
        </div>
        <!--toggle button close-->  
        <!--nav brand wrap-->
        <div class="nav-brand">
            <a href="javascripit:void(0)" class="brand" >
                <img  src="<?php echo base_url() ?>public/adminpanel/images/logo.png" title="Admin Logo">  
            </a>
        </div>
        <!--nav brand wrap close-->
        <!--User Setting-->   
        <div class="user-setting-wrap">
            <div class="user-pic-wrap">
                <ul>
                    <!--                        <li> 
                                               <a href="admin/Admin_Profile/admin_profile"><img src="public/adminpanel/images/logoo.jpg" title="Admin Profile"></a>   
                                            </li>-->
                    <li> 
                        <a href="admin/Dashboard"><img src="public/adminpanel/images/ring.svg" title="Notification"></a> 
                        <span class="noti-digit">2</span>  
                    </li>
                    <li> 
                        <a href="<?php echo base_url() ?>admin/profile"><img src="public/adminpanel/images/setting.svg" title="Setting"></a>   
                    </li>
                    <li> 
                        <a href="javascript:void(1)" onclick="logoutUser();"><img src="public/adminpanel/images/logout.svg" title="Logout"></a>   
                    </li>
                </ul>
            </div>
        </div>
        <!--User Setting wrap close-->   
    </header>