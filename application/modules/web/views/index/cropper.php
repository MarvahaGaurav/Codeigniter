<style>
    .myloader{
        width: 5%;
    }
    .modal-header {
        padding: 15px;
        border-bottom: 1px solid #e4001c;
    }
    .avatar-form .modal-header {
        border-top-left-radius: 6px;
        border-top-right-radius: 6px;
        background: #2e2e2e;
    }
    .avatar-chooseimg-wrapper {
        position: relative;
        padding: 11px 15px 10px;
        background: #e4001c;
        border-radius:3px;
        font-size: 12px;
        color: #fff;
        display: inline-block;
        overflow: hidden;
    }
    .avatar-chooseimg-wrapper label {
        margin-bottom: 0;
    }
    .avatar-chooseimg-wrapper input {
        display: block;
        position: absolute;
        top: 0;
        right: 0;
        cursor: pointer;
        opacity: 0;
        height: auto;
        width: auto;
    }
    .avatar-wrapper {
        min-height: 250px;
        width: 100%;
        margin-top: 15px;
        box-shadow: inset 0 0 5px rgba(0,0,0,.25);
        background-color: #fcfcfc;
    }
    .avatar-form .modal-header .img_up_hd h1 {
        font-size: 14px;
        color: #fff;
        float: left;
    }
    .avatar-btns, .avatar-zooms {
        float: left;
        margin: 10px 3px 0 0;
    }
    .btn-group, .btn-group-vertical {
        position: relative;
        display: inline-block;
        vertical-align: middle;
    }
    .btn-group>.btn:first-child {
        margin-left: 0;
    }

    .btn-group-vertical>.btn, 
    .btn-group>.btn {
        position: relative;
        float: left;
    }
    .fa-rotate-left:before {
        content: "\f0e2";
    }
    .fa-rotate-right:before {
        content: "\f01e";
    }
    .avatar-btns, .avatar-zooms {
        float: left;
        margin: 10px 3px 0 0;
    }
    .custom-btn {
        display: inline-block;
        border-radius: 3px;
        text-align: center;
        -webkit-transition: all 0.3s ease;
        -moz-transition: all 0.3s ease;
        -o-transition: all 0.3s ease;
        transition: all 0.3s ease;
    }
    .custom-btn {
        padding: 11px 15px 10px !important;
        font-size: 11px;
    }
    .custom-btn.save {
        border: 1px solid #e00016 !important;
        background: #e00016;
        color: #fff !important;
    }
    .custom-btn.cancel {
        border: 1px solid #e00016 !important;
        background: #fff;
        color: #e00016;
    }
    .custom-btn:hover {
        background: #e00016;
        color: #fff;
        -webkit-box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.5);
        -moz-box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.5);
        box-shadow: 0 2px 10px 0 rgba(0, 0, 0, 0.5);
    }
    @media (min-width: 480px) {
        .avatar-chooseimg-wrapper {
            font-size: 14px;
        }
        .avatar-form .modal-header .img_up_hd h1 {
            font-size: 16px;
        }
        .avatar-wrapper {
            min-height: 400px;
        }
        .custom-btn {
            padding: 11px 28px;
            font-size: 14px;
        }
        .btn-width {
            min-width: 142px !important;
            width: auto !important;
        }
    }
</style>
<!--*******************cropper modal************************-->
<div class="modal fade" id="avatar-modal" aria-hidden="true" aria-labelledby="avatar-modal-label" role="dialog" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form class="avatar-form" id="my-cropper" action="public/crop.php" enctype="multipart/form-data" method="post">

                <div class="modal-header" style="border-bottom:none !important; min-height:0px;">
                    <div class="img_up_hd"><h1>Upload Image</h1></div>
                    <div class="close_wrapper">
                        <button class="close" data-dismiss="modal" type="button">&times;</button>
                    </div>
                </div>

                <div class="modal-body">
                    <div class="up_img_wrap">
                        <div class="reatiler_box insidegrey_bg">

                            <!-- Upload image and data -->
                            <div class="avatar-upload clearfix">
                                <input class="avatar-src" name="avatar_src" type="hidden">
                                <input class="avatar-data" name="avatar_data" type="hidden">
                                <div class="avatar-chooseimg-wrapper">
                                    <label for="avatarInput">Choose Image</label>
                                    <input class="avatar-input" id="avatarInput" name="avatar_file" type="file" accept="image/x-png, image/png, image/gif, image/jpeg, image/jpg">
                                </div>
                            </div>

                            <!-- Crop and preview -->
                            <div class="row">
                                <div class="col-md-12">
                                    <div class="avatar-wrapper"></div>
                                </div>
                            </div>

                            <div class="row" style="padding-left: 15px;">
                                <div class="clearfix">
                                    <div class="avatar-btns text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-default btn-cropper fa fa-rotate-left" data-method="rotate" data-option="-90" type="button" title="Rotate -90 degrees"></button>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-default btn-cropper fa fa-rotate-right" data-method="rotate" data-option="90" type="button" title="Rotate 90 degrees"></button>
                                        </div>
                                    </div>
                                    <div class="avatar-zooms text-center">
                                        <div class="btn-group">
                                            <button class="btn btn-default btn-cropper fa fa-plus" data-method="zoom" data-option="0.1" type="button" title="Zoom Out"></button>
                                        </div>
                                        <div class="btn-group">
                                            <button class="btn btn-default btn-cropper fa fa-minus " data-method="zoom" data-option="-0.1" type="button" title="Zoom In"></button>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-sm-12 text-center change clearfix" style="margin-top:25px">
                                    <button class="custom-btn btn-width cancel" data-dismiss="modal" type="reset">Cancel</button>
                                    <button class="custom-btn btn-width save sv-btn" type="submit">Save</button>
                                    <img class="myloader" src="public/images/loader.svg" style="display: none;margin-top:10px">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>
<!--*******************cropper modal end********************-->