<!doctype html>
<html>
    <head>
        <meta charset="utf-8">
        <title></title>

    </head>
    <body>
        <table cellpadding="0" cellspacing="0" border="0" style="margin:0 auto; min-width:320px; max-width:600px; border:2px solid #cecaca;" width="100%">
            <tr style="background-color:#cecaca; padding:10px 0;">
                <td align="center"><a href="javascript:void(0);"><img src="" alt="Project Name"></a></td>
            </tr>
            <tr>
                <td valign="top" style="padding:20px;"><h4>Hi <?php echo $name; ?></h4>
                    <p style="font-size:14px; line-height:22px;">Click <a href="<?php echo $link ?>">here</a> to reset you password</p>
                </td>
                <td style="color: #383636;
                    font-family: arial;
                    font-size: 16px;
                    line-height: 26px;
                    padding: 35px">
                    <strong>Warm Regards,<br></strong>
                    Project Name
                </td>
            </tr>
        </table>
    </body>
</html>

<style>
    @media only screen and (max-width: 600px) {
        body, table, td, p, a, li, blockquote {
            -webkit-text-size-adjust: none !important;
        }
        table {
            width: 100% !important;
        }
        .responsive-image img {
            height: auto !important;
            max-width: 100% !important;
            width: 100% !important;
        }
    }
</style>
