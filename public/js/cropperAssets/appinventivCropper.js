(function () {
    /*
    * To change this license header, choose License Headers in Project Properties.
    * To change this template file, choose Tools | Templates
    * and open the template in the editor.
    */

    var input = document.getElementById('image-selector');
    var image = document.getElementById('image-to-crop');
    var avatar = document.getElementById('cropped_image_preview');
    var formsubmitButton = document.getElementById('form-submit-button');

    var previews = document.querySelectorAll('.cropped_image_preview');

    var $modal = $('#cropper-modal');
    var $alert = $('.alert');
    var cropper = null;
    var canvas = null;
    var initialAvatarURL = null;


    input.addEventListener('change', function (e) {

        var files = e.target.files;

        var done = function (url) {
            input.value = '';
            image.src = url;
            $alert.hide();
            ShowModal();
        };

        var reader;
        var file;


        if (files && files.length > 0) {
            file = files[0];

            if (URL) {
                done(URL.createObjectURL(file));
            }
            else if (FileReader) {
                reader = new FileReader();
                reader.onload = function (e) {
                    done(reader.result);
                };
                reader.readAsDataURL(file);
            }
        }
    });


    // Cropper will initiate when the modal is fully shown
    $('#cropper_modal').on('shown.bs.modal', function () {

        //Start Cropper
        startCropper();

    }).on('hidden.bs.modal', function () {
        $('.modal-backdrop').remove();
        $(".cropped_image_preview").html("");

        cropper.destroy();
        cropper = null;
    });


    /**
     *
     * @returns {undefined}
     *
     * viewMode : 0,1,2,3
     * aspectRatio : 0, Means 1=> 1:1 or if value is 2 means 1:2
     * movable : true|false
     * rotatable : true|false
     * responsive : true|false
     */
    var startCropper = function () {

        cropper = new Cropper(image, {
            movable: false,
            rotatable: true,
            responsive: true,
            guides: true,
            aspectRatio: 1,
            viewMode: 1, // if 0 : no restrictions

            //start privew. If dont want to use preview remove code from here
            ready: function () {
                croppable = true;
                var clone = this.cloneNode();

                clone.className = '';
                clone.style.cssText = (
                    'display: block;' +
                    'width: 100%;' +
                    'min-width: 0;' +
                    'min-height: 0;' +
                    'max-width: none;' +
                    'max-height: none;'
                );

                each(previews, function (elem) {
                    elem.appendChild(clone.cloneNode());
                });

            },
            crop: function (event) {
                var data = event.detail;
                var cropper = this.cropper;
                var imageData = cropper.getImageData();
                var previewAspectRatio = data.width / data.height;

                each(previews, function (elem) {
                    var previewImage = elem.getElementsByTagName('img').item(0);
                    var previewWidth = elem.offsetWidth;
                    var previewHeight = previewWidth / previewAspectRatio;
                    var imageScaledRatio = data.width / previewWidth;

                    elem.style.height = previewHeight + 'px';
                    previewImage.style.width = imageData.naturalWidth / imageScaledRatio + 'px';
                    previewImage.style.height = imageData.naturalHeight / imageScaledRatio + 'px';
                    previewImage.style.marginLeft = -data.x / imageScaledRatio + 'px';
                    previewImage.style.marginTop = -data.y / imageScaledRatio + 'px';

                });
            }
            //Preview Ends.
        });
    };

    /**
     *
     * @returns {undefined}
     */
    var ShowModal = function () {
        $('#cropper_modal').modal('show');
        //    var show = document.createElement( "div" );
        //    show.className = ' modal-backdrop fade show ';
        //    document.body.appendChild( show );
        //
        //
        //    $( '#cropper_modal' ).addClass( 'show' );
    };


    var each = function (arr, callback) {
        var length = arr.length;
        var i;

        for (i = 0; i < length; i++) {
            callback.call(arr, arr[i], i, arr);
        }

        return arr;
    };

    /**
     * Crop Button Functionality
     */
    document.getElementById("crop_it").addEventListener('click', function () {
        if (cropper) {

            canvas = cropper.getCroppedCanvas({
                width: 500
            });

            initialAvatarURL = avatar.src;

            //        avatar.src = canvas.toDataURL();
            avatar.setAttribute('style', 'background: url(' + canvas.toDataURL() + ' ); background-size:cover; display: block; background-position: center;background-repeat: no-repeat;');

            var $userCameraHolder = $("#user-camera-holder");


            $('#cropper_modal').modal('hide');

            canvas.toBlob(function (blob) {
                var formData = new FormData({
                    width: 200,
                    height: 100
                });

                formData.append('avatar', blob);
                formData.append('csrf_token', $("input[name='csrf_token']").val());

                //Write ajax code here to upload image on server
                $.ajax({
                    url: "transfer-image",
                    method: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    beforeSend: function () {
                        $userCameraHolder.removeClass('fa-camera');
                        $userCameraHolder.addClass('fa-circle-o-notch fa-spin');
                        $(formsubmitButton).attr('disabled', 'disabled');
                    },
                    error: function (err) {
                        $userCameraHolder.addClass('fa-camera');
                        $userCameraHolder.removeClass('fa-circle-o-notch fa-spin');
                        $(formsubmitButton).removeAttr('disabled');
                    },
                    success: function (res) {
                        var obj = JSON.parse(res);
                        $("#user_image").val(obj.url);
                        $userCameraHolder.addClass('fa-camera');
                        $userCameraHolder.removeClass('fa-circle-o-notch fa-spin');
                        $(formsubmitButton).removeAttr('disabled');
                    },
                    complete: function () {
                        console.log("Completed");
                        $userCameraHolder.addClass('fa-camera');
                        $userCameraHolder.removeClass('fa-circle-o-notch fa-spin');
                        $(formsubmitButton).removeAttr('disabled'); 
                    }

                });
                //Ends Ajax

            });
        }
    });
})();
