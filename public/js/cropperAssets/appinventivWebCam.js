navigator.getUserMedia = (navigator.getUserMedia || navigator.webkitGetUserMedia || navigator.mozGetUserMedia || navigator.msGetUserMedia);
var ctxstock = false;
var video;
var mediaStreamTrack;
var webCamCropper;
var canvas;
var ctx;



var webCaminput = document.getElementById( 'image-selector' );


var webCamImage = document.getElementById( 'webcam-image-to-crop' );

//Cropped Image live preview
var cropped_image_preview = document.querySelectorAll( '.webcam_cropped_image_preview' );


var webCamAvatar = document.getElementById( 'cropped_image_preview' );



init();

/**
 *
 * @returns {undefined}
 */
function StartWebCam() {
    if ( navigator.getUserMedia ) {
        navigator.getUserMedia(
                {
                    video: true,
                    audio: false
                },
                function ( localMediaStream ) {
                    video = document.querySelector( 'video' );
                    video.srcObject = localMediaStream;
                    mediaStreamTrack = localMediaStream;
                    ShowWebCamModal();
                },
                function ( err ) {
                    alert( 'Web cam not found' );
                    console.log( "The following error occured: " + err );
                }
        );
    }
    else {
        console.log( "getUserMedia not supported" );
    }
}


/********* WebCam Modal *************/
/**
 *
 * @returns {undefined}
 */
var ShowWebCamModal = function () {
    $( '#webCamModal' ).modal( 'show' );
    $( '#webCamModal' ).addClass( 'show' );
};


/**
 *
 * @returns {undefined}
 */
function hideWebCemModal()
{
    $( '#webCamModal' ).modal( 'hide' );
    stopWebcam();
}

/**
 * @returns {undefined}
 */
$( '#webCamModal' ).on( 'shown.bs.modal', function () {

} ).on( 'hidden.bs.modal', function () {
//    removeOverLay();
//    $( ".webcam_cropped_image_preview" ).html( "" );
} );




$( '#webcam_cropper_modal' ).on( 'shown.bs.modal', function () {

} ).on( 'hidden.bs.modal', function () {
    removeOverLay();
} );



/**
 *
 * @returns {undefined}
 */
var removeOverLay = function () {
    $( '.modal-backdrop' ).remove();
};
/**********WebCam Modal END **********/



/**
 *
 * @returns {undefined}
 */
var ShowCropperModal = function () {

    var show = document.createElement( "div" );
    show.className = 'modal-backdrop fade show ';
    document.body.appendChild( show );

    $( '#webcam_cropper_modal' ).modal( 'show' );
    $( '#webcam_cropper_modal' ).addClass( 'show' );
};


/**
 *
 * @returns {undefined}
 */
function stopWebcam() {
    mediaStreamTrack.getTracks()[0].stop();

}
//---------------------
// TAKE A SNAPSHOT CODE
//---------------------


/**
 *
 * @returns {undefined}
 */
function init() {
    // Get the canvas and obtain a context for
    // drawing in it
    canvas = document.getElementById( "webCamCanvas" );
    ctx = canvas.getContext( '2d' );
    ctx.clearRect( 0, 0, canvas.width, canvas.height );
    myInitCode();
}


/**
 *
 * @returns {undefined}
 */
function snapshot() {

    canvas = document.getElementById( "webCamCanvas" );

    ctx.clearRect( 0, 0, canvas.width, canvas.height );
    ctxstock = true;
    ctx.drawImage( video, 0, 0, canvas.width, canvas.height );

    webCamCropper.replace( canvas.toDataURL() );
    ShowCropperModal();
    hideWebCemModal();
}





function getRoundedCanvas( sourceCanvas ) {
    var canvas = document.createElement( 'myWebCamCanvas' );
    var context = canvas.getContext( '2d' );
    var width = sourceCanvas.width;
    var height = sourceCanvas.height;

    canvas.width = width;
    canvas.height = height;

    context.imageSmoothingEnabled = true;
    context.drawImage( sourceCanvas, 0, 0, width, height );
    context.globalCompositeOperation = 'destination-in';
    context.beginPath();
    context.arc( width / 2, height / 2, Math.min( width, height ) / 2, 0, 2 * Math.PI, true );
    context.fill();

    return canvas;
}



/**
 *
 * @returns {undefined}
 */
function myInitCode() {

    webCamCropper = new Cropper( webCamImage, {
        movable: false,
        rotatable: true,
        responsive: true,
        guides: true,
        aspectRatio: 1,
        viewMode: 2, // if 0 : no restrictions
        dragMode: 'move',
        cropBoxMovable: true,
        minCropBoxWidth: 100,
        minCropBoxHeight: 100,

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

            each( cropped_image_preview, function ( elem ) {
                elem.appendChild( clone.cloneNode() );
            } );
        },
        crop: function ( event ) {
            var data = event.detail;
            var cropper = this.cropper;
            var imageData = cropper.getImageData();
            var previewAspectRatio = data.width / data.height;

            each( cropped_image_preview, function ( elem ) {
                var previewImage = elem.getElementsByTagName( 'img' ).item( 0 );
                var previewWidth = elem.offsetWidth;
                var previewHeight = previewWidth / previewAspectRatio;
                var imageScaledRatio = data.width / previewWidth;

                elem.style.height = previewHeight + 'px';
                previewImage.style.width = imageData.naturalWidth / imageScaledRatio + 'px';
                previewImage.style.height = imageData.naturalHeight / imageScaledRatio + 'px';
                previewImage.style.marginLeft = -data.x / imageScaledRatio + 'px';
                previewImage.style.marginTop = -data.y / imageScaledRatio + 'px';
            } );
        }
    } );


    /**
     *
     * @returns {undefined}
     */
    document.getElementById( "saveCropImage" ).addEventListener( 'click', function () {
        var croppedCanvas;
        croppable = true;

        if ( !croppable ) {
            return;
        }

        // Crop
        croppedCanvas = webCamCropper.getCroppedCanvas();

        webCamAvatar.setAttribute( 'style', 'background: url(' + croppedCanvas.toDataURL() + ' ); background-size:cover; display: block; height: 190px;background-position: center;background-repeat: no-repeat;border-radius: 50%!important;' );
        //Hide crop Modal
        hideCropImgModal();

        croppedCanvas.toBlob( function ( blob ) {

            var webCamFormData = new FormData();

            webCamFormData.append( 'avatar', blob );


            //Write ajax code here to upload image on server

        } );

    } );

}

/**
 *
 * @returns {undefined} 
 */
function hideCropImgModal()
{
    $( '#webcam_cropper_modal' ).modal( 'hide' );
    webCamCropper.destroy();
    webCamCropper = null;
}