<!DOCTYPE html>
<html lang="en">
<head>
<!-- Define Charset -->
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
</head>
<body>
<table class="container-middle" align="center" border="0" cellpadding="0" cellspacing="0" width="560">
    <tr ><td height="7"></td></tr>
    <tr ><td height="20"></td></tr>
    <tr >
        <td>
            <table align="center" border="0" cellpadding="0" cellspacing="0" width="528">
                <tbody>
                <?php if(isset($title)){ ?>
                    <tr><td><?=$title?></td></tr>
                    <tr><td height="20"></td></tr>
                <?php } ?>
                <tr><td><?=$body?></td></tr>
                </tbody></table>
        </td>
    </tr>
    <tr ><td height="25"></td></tr>
</table>
</body>
</html>

