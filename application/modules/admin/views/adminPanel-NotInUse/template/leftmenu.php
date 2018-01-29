 <!--data Wrap-->
      <div class="in-data-wrap">

            <!--aside-->
<aside>
    <!--left panel-->
    <div class="left-panel">
        <div class="inner-left-pannel">
             <div class="user-short-detail">
                           <figure>   
                          <img src="<?php echo (isset($admininfo['profile_picture']) && !empty($admininfo['profile_picture']))?$admininfo['profile_picture']:'public/adminpanel/images/login.png' ?>" id="lefft-logo">
                           </figure>
                 <span class="user-name"><?php echo (isset($admininfo['name']) && !empty($admininfo['name']))?$admininfo['name']:'' ?></span> 
               </div>   
            <div class="left-menu">
                <ul>
                    <li>
                        <a href="admin/Dashboard" class="active" >
                         <span class="dashboard"></span><label class="nav-txt">Vendor management</label>
                         </a>
                     </li>
                     <li>
                        <a href="javascripit:void(0);" class="" >
                         <span class="dashboard"></span><label class="nav-txt">Dashboard</label>
                         </a>
                     </li>
                     <li>
                        <a href="javascripit:void(0);" class="" >
                         <span class="dashboard"></span><label class="nav-txt">Dashboard</label>
                         </a>
                     </li>
                     <li>
                        <a href="javascripit:void(0);" class="" >
                         <span class="dashboard"></span><label class="nav-txt">Dashboard</label>
                         </a>
                     </li>
                     <li>
                        <a href="javascripit:void(0);" class="" >
                         <span class="dashboard"></span><label class="nav-txt">Dashboard</label>
                         </a>
                     </li>
                     <li>
                        <a href="javascripit:void(0);" class="" >
                         <span class="dashboard"></span><label class="nav-txt">Dashboard</label>
                         </a>
                     </li>
                </ul>
            </div>
        </div>
    </div>
    <!--left panel-->
</aside>