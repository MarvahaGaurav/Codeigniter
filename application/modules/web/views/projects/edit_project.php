<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="javascript:void(0)">Home</a></li>
            <li><a href="<?php echo base_url('home/projects') ?>">Projects</a></li>
            <li><a href="<?php echo base_url('home/projects/' . $projectId) ?>">Details</a></li>
            <li class="active">Edit Project</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Edit Project</h1>
            <p class="prj-description">We are continously designing, prototyping and testing new products to enable us to deliver products that are energy efficient and environmental friendly, in combination
                with a creation of the ambiance that you need, always keeping in mind that luminaires have a great impact on the environment, appearance and impression of the overall
                surroundings.</p>
        </div>

        <!-- Caption before section -->
        <div class="section-title clearfix">
            <h3>About Project</h3>
        </div>
        <!-- Caption before section -->

        <!-- form wrapper -->

        <?php echo form_open_multipart(base_url('home/projects/' . $projectId . '/edit'), array ('id' => 'add_project', 'name' => "add_project")) ?>
        <div class="form-wrapper">
            <div class="row form-inline-wrapper m-b-0">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Project Number <span></span></label>
                        <div class="form-group-field">
                            <input value="<?php echo $projectData['number'] ?>" name="project_number" id="project_number" type="text" placeholder="242388">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Project Name</label>
                        <div class="form-group-field">
                            <input type="text" value="<?php echo $projectData['name'] ?>" name="project_name" id="project_name" placeholder="Johnson & Sons">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12 block-div">
                    <div class="form-group">
                        <label class="labelTxt">Project Level</label>
                        <!-- <div class="form-group-field">
                            <input type="text"  value="<?php echo $projectData['levels'] ?>" >
                        </div> -->
                        <div class="form-group-field">
                            <select name="levels" id="levels">
                                <?php foreach (range(1, 10) as $num) : ?>
                                <option value="<?php echo $num ?>" <?php if ($projectData['levels'] == $num) {echo ' selected="selected"' ;} ?>><?php echo $num ?></option>
                                <?php endforeach ?>
                                <option value="others" <?php if ($projectData['levels'] >10) {echo ' selected="selected"' ;} ?>>Other</option>
                                <input type ="hidden" id="project_level_count" value="<?php echo $projectData['levels'] ?>" >
                            </select>
                            <span class="customArrow"></span>
                        </div>
                    </div>
                </div>

                <div class="col-lg-2 col-md-2 col-sm-2 col-xs-6 block-div concealable" id="other-level-count-div">
                    <div class="form-group">
                        <label class="labelTxt" for="">Other Level Count</label>
                        <div class="form-group-field input-group" id="other-levels-wrapper">
                            <span class="input-group-addon clickable" id="decrement-others"><i class="fa fa-minus"></i></span>
                            <input type="text" name="" id="other-project-count" value="11" class="number-only-field text-center restrict-characters" data-restrict-to="3">
                            <span class="input-group-addon clickable" id="increment-others"><i class="fa fa-plus"></i></span>
                        </div>
                    </div>
                </div>

                
                <div class="clearfix"></div>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group" id="address-box-wrapper">
                        <label class="labelTxt">Address</label>
                        <div class="form-group-field">
                            <textarea name="address" readonly id="address" data-toggle="modal" data-target="#maps-modal" placeholder="Click map marker icon to pick location"><?php echo $projectData['address'] ?></textarea>
                        </div>
                        <div id="address-map-error"></div>
                        <input type="hidden" name="address_lat" value="<?php echo $projectData['lat'] ?>" id="address-lat">
                        <input type="hidden" name="address_lng" value="<?php echo $projectData['lng'] ?>" id="address-lng">
                    </div>
                </div>
                <span id="is-installer-owner" data-status="<?php echo (int)$userInfo['user_type'] === INSTALLER && (int)$userInfo['is_owner'] === ROLE_OWNER?"true":"false" ?>"></span>
                <?php if ((int)$userInfo['user_type'] === INSTALLER && (int)$userInfo['is_owner'] === ROLE_OWNER) { ?>
                <div class="col-lg-6 col-md-6 col-sm-6 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Installers</label>
                        <div class="form-group-field">
                            <select name="installers" id="isntallers">
                                <option value="others">Select</option>
                                <?php foreach ($employees as $employee) : ?>
                                <option value="<?php echo $employee['user_id'] ?>" <?php echo $employee['user_id'] === $projectData['installer_id']?"selected":"" ?>><?php echo $employee['first_name'] ?></option>
                                <?php endforeach ?>
                            </select>
                            <span class="customArrow"></span>
                        </div>
                    </div>
                </div>
                <?php }?>
            </div>
        </div>
        <!-- button-wrapper section -->
        <div class="button-wrapper clearfix m-t-10">
            <input type="submit" value="Submit" class="custom-btn btn-margin btn-width save">
            <button type="button" class="custom-btn btn-margin btn-width cancel">Cancel</button>
        </div>
        <!-- button-wrapper section -->
        <?php echo form_close(); ?>
        <!-- //form wrapper -->



        <!-- no record found -->
        <!-- <div class="no-record text-center">
            <img src="../../images/no-found-note.png" alt="Note Paper">
            <p>You have no project.</p>
            <p>You have to <a href="login.html" class="login">Login</a> first to add a project!</p>
        </div> -->
        <!-- no record found -->

    </div>
</div>