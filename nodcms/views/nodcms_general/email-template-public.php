<!DOCTYPE html>
<html lang="<?php echo $_SESSION['settings']["code"]; ?>">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta name="viewport" content="width=device-width; initial-scale=1.0; maximum-scale=1.0;">
</head>
<body leftmargin="0" topmargin="0" marginheight="0" marginwidth="0" style="direction: <?php echo $_SESSION['settings']["rtl"]=1?'rtl':'ltr'; ?>;">
    <table align="center" border="0" cellpadding="0" cellspacing="0" width="560" style="direction: <?php echo $_SESSION['settings']["rtl"]=1?'rtl':'ltr'; ?>;">
        <tr ><td height="10"></td></tr>
        <tr >
            <td>
                <table class="mainContent" align="center" border="0" cellpadding="0" cellspacing="0" width="528" style="direction: <?php echo $_SESSION['settings']["rtl"]=1?'rtl':'ltr'; ?>;">
                    <tbody>
                    <?php if(isset($_SESSION['settings']["options"]["msg_header"])){ ?>
                        <tr><td><?php echo str_replace(array('[--$company--]','[--$date--]','[--$smail--]'),array($_SESSION['settings']["company"],my_int_date(time()),$_SESSION['settings']["email"]),$_SESSION['settings']["options"]["msg_header"]); ?></td></tr>
                        <tr><td height="10"></td></tr>
                    <?php } ?>
                    <tr><td><?=$body?></td></tr>
                    <?php if(isset($_SESSION['settings']["options"]["msg_footer"])){ ?>
                    <tr><td height="10"></td></tr>
                    <tr><td><?php echo str_replace(array('[--$company--]','[--$date--]','[--$smail--]'),array($_SESSION['settings']["company"],my_int_date(time()),$_SESSION['settings']["email"]),$_SESSION['settings']["options"]["msg_footer"]); ?></td></tr>
                    <?php } ?>
                    </tbody>
                </table>
            </td>
        </tr>
        <tr ><td height="10"></td></tr>
    </table>
</body>
</html>

