<div class="inner-container">
    <div class="container">

        <!-- breadcrumb -->
        <ul class="breadcrumb">
            <li><a href="<?php echo base_url(); ?>web/home">Home</a></li>
            <li class="active">Quotes</li>
        </ul>
        <!-- //breadcrumb -->

        <div class="project-wrapper">
            <h1 class="page-title">Quotes</h1>
            <p class="prj-description">SG Lighting has vast experience of and expertise in a wide range of different types of projects, such as schools, hospitals, offices, industry, retail and outdoor lighting. Under each type of project in the overview below, there are references to the various areas, as well as product recommendations.</p>
        </div>

        <!-- Quote list search -->
        <div class="project-list-wrapper quote-list clearfix">
            <h2 class="project-listtxt">Received Quotes</h2>
            <div class="search-wrapper fawe-icon-position">
                <span class="fawe-icon fawe-icon-position-right close-ico">
                    <i class="fa fa-times"></i>
                </span>
                <input type="text" class="search-box" id="search-box" placeholder="Search">
                <input type="submit" value="Search" class="search-btn" />
            </div>
        </div>
        <!-- //Quote list search -->

        <!-- no record found -->
        <div class="no-record text-center">
            <img src="public/images/no-found-note.png" alt="Note Paper">
            <p>You have no quotes received.</p>
            <p>You have to <a href="<?php echo base_url(); ?>web/index/index" class="login">Login</a> first to get quotes!</p>
        </div>
        <!-- no record found -->

    </div>
</div>