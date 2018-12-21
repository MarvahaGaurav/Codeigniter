<?php
$cal = json_decode($calc['cal'], true);
?>
<div class="s">
    <h5>Light Calculations</h5>
    <h5><?php echo $calc['details']['title']; ?></h5>
</div>
<div class="room-wrapper">
    <h5>
        <?php
        echo $calc['details']['room_name'] . " (" . $calc['details']['count'] . ")";
        echo (isset($calc['details']['level']) && $calc['details']['level'] > 1) ? ", Level - " . $calc['details']['level'] : '';
        ?>
    </h5>
    <div class="pull-left">
        <h5>Room Dimensions</h5>
        <ul>
            <li><div>Room length: </div><div><?php echo $calc['details']['room_length']; ?> m</div></li>
            <li><div>Room width: </div><div><?php echo $calc['details']['room_width']; ?>  m</div></li>
            <li><div>Room height: </div><div><?php echo $calc['details']['room_height']; ?> m</div></li>
            <li><div>Applications: </div><div>Office</div></li>
            <li><div>Mounting height: </div><div>2,400 m</div></li>
            <li><div>Working plane height: </div><div><?php echo $calc['details']['room_height']; ?> m</div></li>
            <li><div>Maintenance factor: </div><div><?php echo $calc['details']['room_height']; ?></div></li>
            <li><div>Ceiling reflections: </div><div><?php echo $calc['details']['rho_ceiling']; ?>%</div></li>
            <li><div>Wall reflection: </div><div><?php echo $calc['details']['rho_wall']; ?>%</div></li>
            <li><div>Floor reflection: </div><div><?php echo $calc['details']['rho_floor']; ?>%</div></li>
        </ul>
        <h5>Calculations Results</h5>
        <ul>
            <li><div>Total luminaire count: </div><div><?php echo $cal['luminaireCountInX'] * $cal['luminaireCountInY']; ?></div></li>
            <li><div>Total luminous flux: </div><div><?php echo round($cal['illuminance'], 2); ?> lm</div></li>
            <li><div>Illuminance: </div><div><?php echo round($cal['illuminance'], 2); ?> lx</div></li>
            <li><div>Total installed effect:</div><div><?php echo round($cal['totalInstalledEffect'], 2); ?> W</div></li>
            <li><div>Specific load:</div><div><?php echo round($cal['specificLoad'], 2); ?> W/m²</div></li>
        </ul>
    </div>
    <div class="pull-left">
        <div class="image-wrapper">
            <figure>
                <img src="<?php echo $calc['details']['image']; ?>" alt="">
            </figure>
        </div>
    </div>
</div>


