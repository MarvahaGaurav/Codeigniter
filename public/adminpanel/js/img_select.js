  $(function() {
      var previewImage = function(input, block) {
          var fileTypes = ['jpg', 'jpeg', 'png'];
          var extension = input.files[0].name.split('.').pop().toLowerCase(); /*se preia extensia*/
          var isSuccess = fileTypes.indexOf(extension) > -1; /*se verifica extensia*/

          if (isSuccess) {
              var reader = new FileReader();

              reader.onload = function(e) {
                  block.css({
                      'background-image': 'url(' + e.target.result + ')'
                  });
              };
              reader.readAsDataURL(input.files[0]);
          } else {
              alert('Image not accepted');
          }

      };
      $('#front_image').on('change', function() {

          previewImage(this, $('.front_image'));
      });
      $('#back_image').on('change', function() {

          previewImage(this, $('.back_image'));
      });
      $('#crop_image').on('change', function() {

          previewImage(this, $('.crop_image'));
      });
      $('#seed_image').on('change', function() {

          previewImage(this, $('.seed-image'));
      });
      $('#uploadPic').on('change', function() {

          previewImage(this, $('.profile-pic'));
      });
      $('#article_image').on('change', function() {

          previewImage(this, $('.article_image'));
      });

  });