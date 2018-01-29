$(function() {
    var previewImage = function(input, block) {
        var fileTypes = ['jpg', 'jpeg', 'png'];
        var extension = input.files[0].name.split('.').pop().toLowerCase(); /*se preia extensia*/
        var isSuccess = fileTypes.indexOf(extension) > -1; /*se verifica extensia*/

        if (isSuccess) {
            var reader = new FileReader();

            reader.onload = function(e) {
                block.css({ 'background-image': 'url(' + e.target.result + ')' });
            };
            reader.readAsDataURL(input.files[0]);
        } else {
            alert('Image not accepted');
        }

    };
    $('#uploadPic').on('change', function() {
        previewImage(this, $('.profile-pic'));
    })

});