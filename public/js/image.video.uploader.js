var count = 0;
    var getFirstImg = null;
    var names = [];
    $('body').on('change','input[name="inspiration_image[]"]',function(event){
        var ele = $(this);
        var files = event.target.files;
        var uploader = '<li class="visible-wrapper"><div class="album-thumnail"><i class="fa fa-plus"></i><input type="file" name="inspiration_image[]" data-container="#album" class="album-uploader" id="album-add"></div></li>';
        for (var i = 0; i < files.length; i++) {
            
            var file = files[i];
            if (file.type.match('image')) {
                    if (file.size > 2000000) {
                        $("#media-error-label").html("Please check your file, image cannot exceed 2MB");
                        setTimeout(function(){
                            $("#media-error-label").fadeOut(400);
                        }, 5000);
                        break;
                    }
                    var picReader = new FileReader();
                    picReader.fileName = file.name;
                    picReader.addEventListener("load", function (event) {
                        var picFile = event.target;
                        $(ele).after('<div class="albub-item"><img src="'+picFile.result+'"></div><span class="remove-item"><i class="fa fa-times"></i></span>');
                        $('#album').append(uploader);
                        if ($(".visible-wrapper").length > 4) {
                            $(".visible-wrapper").last().find('input').attr('name', '');
                            $(".visible-wrapper").last().hide();
                        }
                    });
                    picReader.readAsDataURL(file);
             } else if(file.type.match('video')){
                if (file.size > 10000000) {
                    console.log('file too big');
                    $("#media-error-label").html("Please check your file, Video cannot exceed 10MB");
                    setTimeout(function(){
                        $("#media-error-label").fadeOut(400);
                    }, 5000);
                    break;
                }
                var picReader = new FileReader();
                    picReader.fileName = file.name
                    picReader.addEventListener("load", function (event) {
                    var picFile = event.target;
                    $(ele).after('<div class="albub-item"><video src="'+picFile.result+'"></video></div><span class="remove-item"><i class="fa fa-times"></i></span><span class="player"><i class="fa fa-play-circle"></i></span>');
                    $('#album').append(uploader);
                    if ($(".visible-wrapper").length > 4) {
                        $(".visible-wrapper").last().find('input').attr('name', '');
                        $(".visible-wrapper").last().hide();
                    }
                });
                picReader.readAsDataURL(file);
            } else {
                $("#media-error-label").html("File type not supported");
                setTimeout(function(){
                    $("#media-error-label").fadeOut(400);
                }, 5000);
            }
            
            
        }
        
    });

    $('body').on('click','.remove-item',function(){
        if ($(".visible-wrapper").length > 4) {
            $(".visible-wrapper").last().find('input').attr('name', 'inspiration_image[]');
            $(".visible-wrapper").last().show();
        }
        $(this).parents('li').remove();
    });
