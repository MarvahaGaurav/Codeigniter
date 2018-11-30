<!--Footer-->
<!--Login page  Wrap close-->
<script src="<?php echo base_url() ?>public/js/jquery.min.js"></script>
<script src="<?php echo base_url() ?>public/js/outer-common.js"></script>
<script src="<?php echo base_url() ?>public/js/global-msg.js"></script>
<script src="<?php echo base_url() ?>public/js/jquery.validate.min.js"></script>
<?php if (isset($additional_js) && !empty($additional_js) && is_array($additional_js) ) { ?>
    <?php foreach($additional_js as $js):?>

<script src="<?php echo $js ?>"></script>

    <?php endforeach?>
<?php }?>
</body>
</html>