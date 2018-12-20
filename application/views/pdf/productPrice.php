<h3>Product and prices</h3>
<?php
foreach ($tmp as $rooms) {
    $first        = $rooms[0];
    $totalPerRoom = 0;
    ?>

    <table>
        <tr>
            <th>Room Name</th>
            <th>No. of room</th>
            <th>Product name</th>
            <th>Product code</th>
            <th>Amounts</th>
            <th>Price per unit</th>
            <th>Total price</th>
        </tr>
        <tr>
            <td><?php echo $first['room_name']; ?></td>
            <td><?php echo $first['room_number']; ?></td>
            <td><?php echo $first['product_name']; ?></td>
            <td><?php echo $first['article_code']; ?></td>
            <td><?php echo $first['amount']; ?></td>
            <td>$<?php echo $first['price']; ?></td>
            <td>$<?php echo $first['price'] * $first['amount']; ?></td>
        </tr>
        <?php
        $totalPerRoom = $first['price'] * $first['amount'];
        if (count($rooms) > 1) {
            for ($count = 1; $count < count($rooms); $count ++) {
                $room         = $rooms[$count];
                $totalPerRoom = $totalPerRoom + ($room['price'] * $room['amount']);
                ?>
                <tr>
                    <?php
                    if ($count == 1) {
                        echo ' <td rowspan="' . (count($rooms) ) . '" colspan="2"></td>';
                    }
                    ?>
                    <td><?php echo $room['product_name']; ?></td>
                    <td><?php echo $room['article_code']; ?></td>
                    <td><?php echo $room['amount']; ?></td>
                    <td>$<?php echo $room['price']; ?></td>
                    <td>$<?php echo $room['price'] * $room['amount']; ?></td>
                </tr>
                <?php
            }
        }
        ?>
        <tr>
            <?php
            if (count($rooms) == 1) {
                ?>
                <td rowspan="<?php echo count($rooms); ?>" colspan="2"></td>
                <?php
            }
            ?>
            <td>Installation cost incl materials</td>
            <td></td>
            <td>10</td>
            <td>$120</td>
            <td>$9.600</td>
        </tr>
        <tr>

            <td colspan="6"><strong>Total cost including installation</strong></td>
            <td><strong>$<?php echo $totalPerRoom; ?></strong></td>
        </tr>
    <!--        <tr>
            <td colspan="6"><strong>Return on investment according to TCO (only if activated)</strong></td>
            <td><strong>35 month</strong></td>
        </tr>
        <tr>
            <td colspan="6"><strong>Yearly savings - energy and maintance according to TCO (only if activated)</strong></td>
            <td><strong>$9.600</strong></td>
        </tr>-->
    </table>

    <?php
}
//exit;
?>


<table>
    <tr>
        <th>Product name</th>
        <th>Product code</th>
        <th>Amounts</th>
        <th>Price per unit</th>
        <th>Total price</th>
    </tr>
    <?php
    foreach ($tmp as $rooms) {
        foreach ($rooms as $room) {
            ?>
            <tr>
                <td><?php echo $room['product_name']; ?></td>
                <td><?php echo $room['article_code']; ?></td>
                <td><?php echo $room['amount']; ?></td>
                <td>$<?php echo $room['price']; ?></td>
                <td>$<?php echo $room['price'] * $room['amount']; ?></td>
            </tr>
            <?php
        }
    }
    ?>

    <tr>
        <td>Total Installation cost incl materials</td>
        <td></td>
        <td>10</td>
        <td>€ 120</td>
        <td>€ 3.400</td>
    </tr>
    <tr>
        <td colspan="4"><strong>Total cost including installation</strong></td>
        <td><strong>€ 120</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Discount (only visuable if given)</strong></td>
        <td><strong>€ 2.000</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Total price excl. Vat for "projectname"</strong></td>
        <td><strong>€ 15.980</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>VAT (country specific)</strong></td>
        <td><strong>€ 3.995</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Total price incl. Vat for "projectname"</strong></td>
        <td><strong>€ 19.975</strong></td>
    </tr>
<!--    <tr>
        <td colspan="4"><strong>Return on investment according to TCO (only if activated)</strong></td>
        <td><strong>35 month</strong></td>
    </tr>
    <tr>
        <td colspan="4"><strong>Yearly savings - energy and maintance according to TCO (only if activated)</strong></td>
        <td><strong>€ 9.600</strong></td>
    </tr>-->
</table>

<h5>Download Room overview</h5>

<div class="download-btn">
    <a href="http://smartguide-dev.applaurels.com/public/excels/smart.xlsx"> Download</a>
</div>
