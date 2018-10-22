<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url("home"); ?>">Home</a></li>
            <li><a href="<?php echo base_url("home/projects"); ?>">Projects</a></li>
            <li class="active">Create New Project</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="page-heading">
            <h1 class="page-title">Create New Project</h1>
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

        <?php echo form_open_multipart(base_url("home/projects/create"), array ('id' => 'add_project', 'name' => "add_project")) ?>
        <div class="form-wrapper">
            <div class="row form-inline-wrapper">
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Project Number <span>(Optional)</span></label>
                        <div class="form-group-field">
                            <input name="project_number" id="project_number" type="text" placeholder="242388">
                        </div>
                    </div>
                </div>
                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Project Name</label>
                        <div class="form-group-field">
                            <input type="text" name="project_name" id="project_name" placeholder="Johnson & Sons">
                        </div>
                    </div>
                </div>

                <div class="col-lg-4 col-md-4 col-sm-4 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Project Levels</label>
                        <div class="form-group-field">
                            <select name="levels" id="levels">
                                <?php
                                for ($i = 1; $i <= 10; $i ++) {
                                    echo '<option value="' . $i . '">' . $i . '</option>';
                                }
                                ?>
                            </select>
                            <span class="customArrow"></span>
                        </div>
                    </div>
                </div>
                <div class="clearfix"></div>
                <div class="col-lg-12 col-md-12 col-sm-12 col-xs-12">
                    <div class="form-group">
                        <label class="labelTxt">Address</label>
                        <div class="form-group-field">
                            <textarea name="address" id="address" placeholder="B-25, Sector 58, Noida, UP-201301"></textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- button-wrapper section -->
        <div class="button-wrapper clearfix">
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