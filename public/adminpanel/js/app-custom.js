$(function() {

    var getFirstImg = null;
    var names = [];
    var count = 1;
    //to upload cover pic of element
    $('body').on('change', '.uploadPic', function(event) {
        var files = event.target.files;
        var output = $(this).parents('li').find('.uploadInputPrev');

        var _parent = $(this).parents('ul');
        var z = 0
        for (var i = 0; i < files.length; i++) {
            var file = files[i];
            names.push($(this).get(0).files[i].name);
            if (file.type.match('image')) {

                var picReader = new FileReader();
                picReader.fileName = file.name
                picReader.addEventListener("load", function(event) {
                    var picFile = event.target;
                    $(output).append("<img class='upPic' src='" + picFile.result + "'" +
                        "title='" + picFile.name + "'/><div  class='post-thumb imgOnly'><div class='inner-post-thumb'><a href='javascript:void(0);' data-id='" + event.target.fileName + "' class='remove-pic'><i class='fa fa-times' aria-hidden='true'></i></a><div></div>");
                });
            } else {
                var picReader = new FileReader();
                picReader.fileName = file.name
                picReader.addEventListener("load", function(event) {

                    var picFile = event.target;
                    $(output).append("<video class='upPic' src='" + picFile.result + "'" +
                        "title='" + picFile.name + "'/></video><div  class='post-thumb'><div class='inner-post-thumb'><a href='javascript:void(0);' data-id='" + event.target.fileName + "' class='remove-pic'><i class='fa fa-times' aria-hidden='true'></i></a><div></div>");
                });
            }

            picReader.readAsDataURL(file);
            $(this).parents('li').removeClass('showInput');
            $(this).parents('.uploadInputLi').hide();

            $(output).show();
            count = 1;
            var prevSize = $(_parent).find('li').length;
            if (prevSize < 2) {
                $(_parent).append('<li class="upendedLi showInput"><div class="uploadInputPrev"></div><div class="uploadInputLi"><a href="javascript:void(0);"><input type="file" class="uploadPic" name="image' + prevSize + '"><i class="fa fa-plus"></i></a></div></li>');
            }
        }
    });


    $('body').on('click', '.remove-pic', function(e) {
        var _parent = $(this).parents('ul');
        e.stopPropagation();
        $(this).parents('li').remove();
        var removeItem = $(this).attr('data-id');
        var yet = names.indexOf(removeItem);

        if (yet != -1) {
            names.splice(yet, 1);
        }
        var prevSize = $(_parent).length;

        if (count == 1) {
            $(_parent).find('li.showInput').remove();
            $(_parent).append('<li><div class="uploadInputPrev"></div><div class="uploadInputLi"><a href="javascript:void(0);"><input type="file" class="uploadPic" name="image"><i class="fa fa-plus"></i></a></div></li>');
            count++;
        }
    });

});