<div id="flash-card" data-message="<?php echo $this->session->flashdata("flash-message") ?>" data-type="<?php echo $this->session->flashdata("flash-type") ?>">
    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
    <strong class="strong-message"></strong>
    <span class="message"></span>
</div>
<script data-main="<?php echo base_url("public/js/main/" . (isset($js) && !empty(trim($js)) ? $js : "main")) ?>" src="<?php echo base_url("public/js/require.js") ?>"></script>
</html>