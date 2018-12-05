<!DOCTYPE html>
<html lang="en">
   <head>
     <base href="<?php echo base_url(); ?>">
     <script> var baseUrl = "<?php echo base_url()?>";</script>
      <meta charset="utf-8">
      <meta http-equiv="X-UA-Compatible" content="IE=edge">
      <meta name="viewport" content="width=device-width, initial-scale=1.0,
         minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
      <meta name="description" content="">
      <meta name="author" content="">
      <title>RCC</title>
      <link rel="icon" type="image/png" sizes="32x32" href="public/adminpanel/images/logo.png">
      <!-- Bootstrap Core CSS -->
    <?php
    foreach ($css as $value) {
        echo "<link href='{$value}' rel='stylesheet'>\n\t";
    }
    ?>

</head>

<body>
    <div class="in-data-wrap">
<!--        <div id="pre-page-loader">
            <div id="pre-page-loader-center">
                <div id="pre-page-loader-center-absolute">
                    <div class="object" id="object_one"></div>
                    <div class="object" id="object_two"></div>
                    <div class="object" id="object_three"></div>
                    <div class="object" id="object_four"></div>
                    <div class="object" id="object_five"></div>
                    <div class="object" id="object_six"></div>
                    <div class="object" id="object_seven"></div>
                    <div class="object" id="object_eight"></div>
                    <div class="object" id="object_big"></div>
                </div>
            </div>
        </div>-->

        <!-- alert -->
<!--        <div class="alert alert-success" role="alert">
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
            <h4 class="alert-heading">Well done!</h4>
            <p>Aww yeah, you successfully read this important alert message.</p>
            <p>Whenever you need to, be sure to use margin utilities</p>
        </div>-->
        <!-- //alert -->
