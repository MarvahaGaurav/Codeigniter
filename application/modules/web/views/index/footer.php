<div id="flash-card" data-message="<?php echo $this->session->flashdata("flash-message") ?>" data-type="<?php echo $this->session->flashdata("flash-type") ?>">
    <strong class="strong-message"></strong><span class="message"></span>
</div>
<script src="public/js/web/global-msg.js"></script>
<script src="public/js/jquery.validate.min.js"></script>
<?php if ( isset($additional_js) && !empty($additional_js) && is_array($additional_js) ) { ?>
    <?php foreach($additional_js as $js):?>

<script src="<?php echo $js ?>"></script>

    <?php endforeach?>
<?php }?>

<script>
    var $flashCard = $("#flash-card");
    var flashMessage = $flashCard.attr("data-message").trim();
    
    if ( flashMessage.length > 1 ) {
        displayFlashCard(flashMessage);
    }

    function displayFlashCard(message) {
        
        $flashCard.addClass("alert alert-" + $flashCard.attr("data-type"));
        $flashCard.find(".strong-message").html(message);
        $flashCard.find(".strong-message").css({
            width: "100%",
            textAlign: "center",
            display: "block"
        });
        // $flashCard.css({
        //     position: "fixed",
        //     top: "15%",
        //     left: "33.33%",
        //     zIndex: "9999",
        //     display: "block",
        //     width: "30%"
        // });

        setTimeout(function(){
            $flashCard.fadeOut(300);
        }, 5000);
    }
</script>
</html>