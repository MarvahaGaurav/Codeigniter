<div class="view-heading">
    <h3>Top View</h3>
</div>
<div class="image-wrapper">
    <figure>
        <?php
        $projectionTop = str_replace("<?xml version='1.0' encoding='utf-8'?>", '', $projectionTop);
        $projectionTop = str_replace("width='500'", "width='200'", $projectionTop);

        $projectionTop  = str_replace("width='500px'", "width='200px'", $projectionTop);
        $projectionTop  = str_replace("height='500px'", "height='200px'", $projectionTop);
        echo str_replace("height='500'", "height='200'", $projectionTop);
        ?>
    </figure>
</div>

<div class="view-heading">
    <h3>Side View</h3>
</div>
<div class="image-wrapper">
    <figure>
        <?php
        $projectionSide = str_replace("<?xml version='1.0' encoding='utf-8'?>", '', $projectionSide);
        $projectionSide = str_replace("width='500'", "width='200'", $projectionSide);

        $projectionSide  = str_replace("width='500px'", "width='200px'", $projectionSide);
        $projectionSide  = str_replace("height='500px'", "height='200px'", $projectionSide);
        echo str_replace("height='500'", "height='200'", $projectionSide);
        ?>
    </figure>
</div>



<div class="view-heading">
    <h3>Front View</h3>
</div>
<div class="image-wrapper">
    <figure>
        <?php
        $projectionFront = str_replace("<?xml version='1.0' encoding='utf-8'?>", '', $projectionFront);
        $projectionFront = str_replace("width='500'", "width='200'", $projectionFront);

        $projectionFront = str_replace("width='500px'", "width='200px'", $projectionFront);
        $projectionFront = str_replace("height='500px'", "height='200px'", $projectionFront);
        echo str_replace("height='500'", "height='200'", $projectionFront);
        ?>
    </figure>
</div>