
<header class="header">
    <div class="pull-left">
        <ul>
            <?php #echo ('' != $company['company_name']) ? "<li>" . $company['company_name'] . "</li>" : ''; ?>
            <?php #echo ('' != $company['company_address']) ? "<li>" . $company['company_address'] . "</li>" : ''; ?>
        </ul>
    </div>
    <div class="pull-right">
        <div class="logo">
            <a href="#">
                <?php if ('' != $company['company_image']) { ?>
                    <img src="<?php echo $company['company_image']; ?>" alt="Logo" title="Logo">
                <?php } ?>
            </a>
        </div>
    </div>
</header>