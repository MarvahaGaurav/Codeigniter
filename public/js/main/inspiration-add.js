requirejs.config({
  baseUrl: 'public/js',
  waitSeconds: 60,
  paths: {
    jquery: 'jquery.min',
    bootstrap: 'bootstrap.min',
    common: 'web/common',
    jqueryValidator: 'jquery.validate.min',
    jqueryScrollbar: 'plugin/jquery.scrollbar.min',
    imageVideoUploader: 'image.video.uploader',
    select2: 'select2.min'
  },
  shim: {
    //dependencies
    bootstrap: ['jquery'],
    common: ['bootstrap'],
    jqueryValidator: ['jquery'],
    jqueryScrollbar: ['jquery'],
    select2: ['common'],
    imageVideoUploader: ['select2']
  }
});

requirejs(
  [
    'jquery',
    'bootstrap',
    'common',
    'jqueryValidator',
    'jqueryScrollbar',
    'select2'
  ],
  function($) {
    $('#multiple-checked').select2();

    $('form#add-inspiration').validate({
      rules: {
        title: {
          required: true,
          maxlength: 255
        },
        description: {
          required: true,
          maxlength: 255
        }
      }
    });
  },
  function() {}
);
