
<!DOCTYPE html>
<html lang="en">
    <head>
        <base href="<?php echo base_url(); ?>">
        <meta charset="utf-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0, 
              minimum-scale=1.0, maximum-scale=1.0, user-scalable=no">
        <meta name="description" content="">
        <meta name="author" content="">
        <title>Reusable Admin</title>
        <link rel="icon" type="image/png" sizes="32x32" href="public/adminpanel/images/logoo.jpg">
        
        
    <?php
    foreach ($css as $value) {
        echo "<link href='{$value}' rel='stylesheet'>\n\t";
    }
    ?>
        
        
        <style>
            footer {
                display: none;
            }
        </style>


    </head>

    <body >
        <!--Login page  Wrap-->
        <div class="data-wrap">