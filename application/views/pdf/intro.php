<page size="A4">
    <header class="header">
        <div class="pull-left">
            <ul>
                <?php
                echo isset($company) ? '<li><b></i>' . $company . '</i></b></li>' : '';
                echo isset($line1) ? '<li>' . $line1 . '</li>' : '';
                ?>
            </ul>
            <ul>
                <?php
                echo isset($name) ? '<li><b>' . $name . '</b></li>' : '';
                if ($user_type != 1) {
                    echo isset($company) ? '<li><b></i>' . $company . '</i></b></li>' : '';
                    echo isset($line1) ? '<li>' . $line1 . '</li>' : '';
                    echo isset($line2) ? '<li>' . $line2 . '</li>' : '';
                    echo isset($att) ? '<li>' . $att . '</li>' : '';
                }
                ?>
            </ul>
        </div>
        <div class="pull-right">
            <div class="logo">
                <a href="#"><img src="<?php echo BASE_URL . '/smart/public/images/sg.png'; ?>" alt="Logo" title="Logo"></a>
            </div>
        </div>

    </header>

    <div class="main-heading">
        <div>Offer Regarding
            <div><?php echo $project_name; ?></div>
            <div><?php echo $project_number; ?></div>
        </div>
    </div>
    <div class="main-subtext">
        Offer is valid until : <span id=""><?php echo $valid_date; ?></span>
    </div>
    <div class="main-subtext">
        Contact Person
    </div>
    <div class="main-subtext">
        <span id=""><?php echo $contact_person; ?></span>
    </div>
</page>