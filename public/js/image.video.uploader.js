var count = 0;
    var getFirstImg = null;
    var names = [];
    $('body').on('change','input[name="thumbnail"]',function(event){
        var ele = $(this);
        var files = event.target.files;
        var uploader = '<li><div class="album-thumnail"><i class="fa fa-plus"></i><input type="file" name="thumbnail" data-container="#album" class="album-uploader" multiple="" id="album-add"></div></li>';
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
                if (file.type.match('image')) {
                    var picReader = new FileReader();
                    picReader.fileName = file.name
                    picReader.addEventListener("load", function (event) {
                    var picFile = event.target;
                    $(ele).after('<div class="albub-item"><img src="'+picFile.result+'"></div><span class="remove-item"><i class="fa fa-times"></i></span>');
                    $('#album').append(uploader);
                });
             } else if(file.type.match('video')){
                var picReader = new FileReader();
                    picReader.fileName = file.name
                    picReader.addEventListener("load", function (event) {
                    var picFile = event.target;
                    $(ele).after('<div class="albub-item"><video src="'+picFile.result+'"></video></div><span class="remove-item"><i class="fa fa-times"></i></span><span class="player"><i class="fa fa-play-circle"></i></span>');
                    $('#album').append(uploader);
                });
             }
            picReader.readAsDataURL(file);
        }
    });

    $('body').on('click','.remove-item',function(){
        $(this).parents('li').remove();
    });
