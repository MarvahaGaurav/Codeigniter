  <!--Header close-->   

            <div class="inner-right-panel">

                

               <!--breadcrumb wrap-->  

               <div class="breadcrumb-wrap">

                              <ol>

                                

                                  <li><a href="admin/Dashboard">User Managment</a></li>  

                              </ol>

                  </div>

              <!--breadcrumb wrap close-->    

               <!--Filter Section -->

                 <form method="GET">

               <div class="fltr-srch-wrap clearfix white-wrapper">

                  <div class="left-lg-col">

                     <div class="row">

                        <div class="col-lg-8">

                           <div class="srch-wrap">

                              <span class="srch-close-icon"></span>

                              <!--<span class="search-icon"></span>-->

                              <button class="srch" type="submit" action="admin/Dashboard"><span class="search-icon"></span></button>

                              <input type="text" value="<?php echo (isset($searchdata) && !empty($searchdata))? $searchdata:''?>"  class="search-box" placeholder="Search...name ..email" user_id="searchuser" name="searchuser">

                           </div>

                        </div>

                     </div>

                  </div>

                  <div class="right-lg-col">

                     <div class="top-opt-wrap text-right">

                        <ul>

                           <li><a href="javascripit:vouser_id(0)" title="File Export" class="icon_filter"><img src="public/adminpanel/images/export-file.svg"> </a></li>
                           
                           <li><a href="javascripit:vouser_id(0)" title="Filter" class="icon_filter" user_id="filter-suser_ide-wrapper"><img src="public/adminpanel/images/filter.svg"></a></li>
                           <li><a href="admin/Vendor_Management" title="Add" class="icon_filter" user_id="filter-suser_ide-wrapper"><img src="public/adminpanel/images/add.svg"></a></li>                        

                        </ul>

                     </div>

                  </div>

               </div>

                 </form>

               <!--Filter Section Close-->

               <!--Filter Wrapper-->

               <div class="filter-wrap">

                  <h2 class="fltr-heading">Filter </h2>

                  <div class="inner-filter-wrap">

                     <div class="fltr-field-wrap">

                        <label class="admin-label">Status</label>

                        <div class="commn-select-wrap">

                           <select class="selectpicker">

                              <option>as</option>

                              <option>asads</option>

                              <option>dsadasdaas</option>

                           </select>

                        </div>

                        

                     </div>

                     <div class="button-wrap text-center">

                        <input type="Submit" value="Reset" name="" class="commn-btn cancel">

                        <input type="reset" value="Filter" name="" class="commn-btn save">

                     </div>

                  </div>

               </div>

               <!--Filter Wrapper Close-->





               <div class="display-wrap clearfix">

                    <div class="row">
                        <div class="col-lg-9 col-sm-9">
</div>

                     <div class="col-lg-3 col-sm-3">

                           <select class="selectpicker">

                              <option>Display 5</option>

                              <option>Display 10</option>

                              <option>Display 15</option>

                           </select>

                        </div>
                     </div>                
              

           

               <!--Table-->

               <input type="huser_idden" name="<?php echo $csrfName;?>" user_id="<?php echo $csrfName;?>" value="<?php echo $csrfToken;?>"> 



               <div class="white-wrapper">

               <div class="table-responsive clearfix">

                   

                  <!--table div-->

                  <table user_id="example" class="list-table table " cellspacing="0" wuser_idth="100%">

                     <thead>

                         <tr>

                                    <th >S.No</th>

                                    <th>Restaurant Name</th>

                                    <th>Email Id</th>

                                    <th>Mobile Number</th>

                                    <th>Address</th>

                                    <th>Adding Date</th>

                                    <th>User Type</th>

                                    <th>status</th>

                                    <th>Action</th>

                                </tr>

                     </thead>

                     <tbody>

                        <?php $pagecount = PAGE_LIMIT;  $i = (($page * $pagecount) - $pagecount) + 1; ?>

                        <?php

                        if (isset($userlist['result']) && !empty($userlist['result'])) {

                            $i=$offset+1;    foreach ($userlist['result'] as $key => $user_data)  {   

                                ?>

                                <tr>

                                    <td><?php echo $i ?></td>

                                    <td><a href="admin/viewMerchant?user=<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($user_data['user_id']), true);?>"><?php echo $user_data['first_name']?></a></td>

                                    <td><?php echo $user_data['email']?></td>

                                    <td><?php echo $user_data['phone']?></td>

                                    <td><?php echo $user_data['address']?></td>

                                    <td><?php echo $user_data['registered_date']?></td>

                                    <td><?php if($user_data['type']=='1') { echo"Customer";
                                   } else if($user_data['type']=='2') { echo "Merchant";
}?></td>

                                    <td><?php if($user_data['status']=='1') { echo"Active";
                                   } else if($user_data['status']=='2') { echo "Blocked";
} else if($user_data['status']=='3') { echo "Blocked";
} else if($user_data['status']=='4') {echo"Deleted";
}?></td>  

                                    <td>  

                                    <a href="admin/viewMerchant?user=<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($user_data['user_id']), true);?>"><i class="fa fa-eye" aria-huser_idden="true"></i></a>

                                    <a href="javascript:vouser_id(0);" user_id="changestatus" data-block-status="<?php echo ($user_data['status'] == 1)? 2:1; ?>" class="form-hyperlink" onclick="changestatus('<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($user_data['user_id']), true);?>',this);" data-toggle="modal" data-target="#myModal-block"><?php echo ($user_data['status'] == 1)?'Block':'Unblock'; ?></a>

                                    <a href="javascript:vouser_id(0);" user_id="deleteuser" data-delete-status="3" class="form-hyperlink deleteuser" onclick="deleteuser('<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($user_data['user_id']), true);?>',this);"><i class="fa fa-trash" aria-huser_idden="true" data-toggle="modal" data-target="#myModal-trash"></i></a>    

                                    <a  href="admin/editMerchant?user=<?php echo encrypt_with_openssl(new Encryption\OpenSSLEncrypt($user_data['user_id']), true);?>"><i class="fa fa-pencil-square-o" aria-huser_idden="true"></i></a>



                                    </td>

                                </tr>

                                <?php $i++; 
                            } ?>

                        <?php } else { ?><tr><td colspan="12"><?php echo "No result found";
                        } ?></td></tr>

                     </tbody>

                  </table>

                  <!-- Pagenation and Display data wrap-->

                  <div class="bottom-wrap clearfix">

                     <div class="left-column">

                        

                     </div>

                     <div class="right-column text-right">

                        <div class="pagenation-wrap">

                           <!--<ul>-->

                                <?php echo $link; ?>

                           <!--</ul>-->

                        </div>

                     </div>

                  </div>

                  <!-- Pagenation and Display data wrap-->

               </div>
 </div>
               <!--Table listing-->  

            </div>
                         </div>               
         </div>

      </div>

      <!--data  Wrap close-->

      <!--Delete  Modal Close-->

      <!-- Modal -->

      <!-- Modal -->

      <div user_id="myModal-trash" class="modal fade" role="dialog">

          <input type="huser_idden" user_id="uuser_id" name="uuser_id" value="">

          <input type="huser_idden" user_id="ustatus" name="ustatus" value="">

         <div class="modal-dialog modal-sm">

            <!-- Modal content-->

            <div class="modal-content">

               <div class="modal-header modal-alt-header">

                  <button type="button" class="close" data-dismiss="modal">&times;</button>

                  <h4 class="modal-title modal-heading">Delete </h4>

               </div>

               <div class="modal-body">

                  <p class="modal-para">Are you sure want to delete this user?</p>

                  <div class="button-wrap">

                      <ul>

                     <input type="button" value="Cancel" class="commn-btn cancel" name="" onclick="closemodel()">

                     <input type="button" value="Delete" class="commn-btn save" name="" onclick="deletemerchant()">

                     </ul>

                  </div>

               </div>

            </div>

         </div>

      </div>

      <!--delete Modal Close-->

      <!--Block  Modal Close-->

      <!-- Modal -->

      <!-- Modal -->

      <div user_id="myModal-block" class="modal fade" role="dialog">

        <input type="huser_idden" user_id="useruser_id" name="useruser_id" value="">

          <input type="huser_idden" user_id="udstatus" name="udstatus" value="">

         <div class="modal-dialog modal-sm">

            <!-- Modal content-->

            <div class="modal-content">

               <div class="modal-header modal-alt-header">

                  <button type="button" class="close" data-dismiss="modal">&times;</button>

                  <h4 class="modal-title modal-heading">BLOCK</h4>

               </div>

               <div class="modal-body">

                  <p class="modal-para">Are you sure want to block this user?</p>

                  <div class="button-wrap">

                      <ul>

                      <input type="button" value="No" class="commn-btn cancel" name="" onclick="closemodel()">

                     <input type="button" value="Yes" class="commn-btn save" name=""  onclick="changeuserstatus()">

                     </ul>

                  </div>

               </div>

            </div>

         </div>

      </div>

