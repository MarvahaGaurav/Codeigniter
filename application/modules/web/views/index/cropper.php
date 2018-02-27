<style>
    .myloader{
        width: 16%;
        position: absolute;
        margin-top: -29px;
        /*display: none;*/
    }
    .avatar-form .modal-header {
    border-top-left-radius: 6px;
    border-top-right-radius: 6px;
}

.avatar-form .modal-header, .videotrim-wrap .modal-header {
    background: #e4001c;
}
.modal-header {
    padding: 15px;
    border-bottom: 1px solid #e4001c;
}
.avatar-form .modal-body, .videotrim-wrap .modal-body {
    padding: 30px 15px;
}

.modal-body {
    position: relative;
    padding: 15px;
}
.avatar-chooseimg-wrapper, .vdo-upload {
    position: relative;
    padding: 13px 14px 11px;
    background: #e4001c;
    font-size: 16px;
    color: #fff;
    display: inline-block;
    float: left;
    overflow: hidden;
}
.avatar-chooseimg-wrapper label, .vdo-upload label {
    margin-bottom: 0;
}
.avatar-chooseimg-wrapper input, .vdo-upload input {
    display: block;
    position: absolute;
    top: 0;
    right: 0;
    cursor: pointer;
    opacity: 0;
    height: 100%;
}
.avatar-wrapper {
    /* height: 330px; */
    min-height: 400px;
    width: 100%;
    margin-top: 20px;
    box-shadow: inset 0 0 5px rgba(0,0,0,.25);
    background-color: #fcfcfc;
    /* overflow: hidden; */
}
.avatar-form .modal-header .img_up_hd h1, .videotrim-wrap .modal-header .modal-title {
    font-size: 18px;
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

.btn-group-vertical>.btn, .btn-group>.btn {
    position: relative;
    float: left;
}
.fa {
    display: inline-block;
    font: normal normal normal 14px/1 FontAwesome;
    font-size: inherit;
    text-rendering: auto;
    -webkit-font-smoothing: antialiased;
    -moz-osx-font-smoothing: grayscale;
}
.btn-default {
    color: #333;
    background-color: #fff;
    border-color: #ccc !important;
}
.btn {
    display: inline-block;
    padding: 6px 12px;
    margin-bottom: 0;
    font-size: 14px;
    font-weight: 400;
    line-height: 1.42857143;
    text-align: center;
    white-space: nowrap;
    vertical-align: middle;
    -ms-touch-action: manipulation;
    touch-action: manipulation;
    cursor: pointer;
    -webkit-user-select: none;
    -moz-user-select: none;
    -ms-user-select: none;
    user-select: none;
    background-image: none;
    border: 1px solid transparent;
    border-radius: 4px;
}
.fa-rotate-left:before {
    content: "\f0e2";
}
avatar-btns, .avatar-zooms {
    float: left;
    margin: 10px 3px 0 0;
}
#avatar-modal .up_img_wrap .change {
    width: 100%;
    padding-top: 20px;
    clear: both;
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
                            <div class="avatar-upload text-center clearfix">
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

                                    <div class="col-lg-12 col-sm-12 text-center change clearfix" style="margin-top:20px">
                                        <button class="commn-btn custom-btn cancel" data-dismiss="modal" type="reset">Cancel</button>
                                        <button class="commn-btn custom-btn save sv-btn" type="submit">Save</button>
                                        <img class="myloader" src="public/images/loader.svg" style="display: none">
                                    </div>
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