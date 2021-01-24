<!DOCTYPE html>
<html lang="<?=$_SESSION["language"]["code"]?>">

<!-- Mirrored from thevectorlab.net/flatlab/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 09 Feb 2014 08:47:28 GMT -->
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="shortcut icon" href="<?=base_url()?><?=isset($settings["fav_icon"])?$settings["fav_icon"]:""?>">

    <title><?=_l('Administration',$this)?> <?=isset($settings["company"])?$settings["company"]:""?></title>

    <!-- Bootstrap core CSS -->
    <link href="<?=base_url()?>assets/flatlab/css/bootstrap.min.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/flatlab/css/bootstrap-reset.css" rel="stylesheet">
    <!--external css-->
    <link href="<?=base_url()?>assets/flatlab/assets/font-awesome/css/font-awesome.css" rel="stylesheet" />
    <!-- Custom styles for this template -->
    <link href="<?=base_url()?>assets/flatlab/css/style.css" rel="stylesheet">
    <?php if($_SESSION["language"]["rtl"]==1){ ?>
    <link href="<?=base_url()?>assets/flatlab/css/rtl.css" rel="stylesheet">
    <?php } ?>
    <link href="<?=base_url()?>assets/backend_custom/css/style.css" rel="stylesheet">
    <link href="<?=base_url()?>assets/flatlab/css/style-responsive.css" rel="stylesheet" />

    <!-- HTML5 shim and Respond.js IE8 support of HTML5 tooltipss and media queries -->
    <!--[if lt IE 9]>
    <script src="<?=base_url()?>assets/flatlab/js/html5shiv.js"></script>
    <script src="<?=base_url()?>assets/flatlab/js/respond.min.js"></script>
    <![endif]-->
</head>

<body class="login-body">

<div class="container">
    <form class="form-signin" action="<?=base_url()?>admin-sign/login/" method="post">
        <h2 class="form-signin-heading"><?=_l('Sign Panel',$this)?></h2>
        <div class="login-wrap">
            <input name="username" id="username" type="text" class="form-control" placeholder="<?=_l('Username',$this)?>" autofocus>
            <input name="password" id="password" type="password" class="form-control" placeholder="<?=_l('Password',$this)?>">

            <button class="btn btn-lg btn-login btn-block" type="submit"><?=_l('Sign in',$this)?></button>
            <p><?=_l('This is just for Administrators',$this)?></p>
            <?php if($this->session->flashdata('message')!=''){ ?><div class="alert alert-danger"><?=$this->session->flashdata('message')?></div><?php } ?>
        </div>
    </form>

</div>



<!-- js placed at the end of the document so the pages load faster -->
<script src="<?=base_url()?>assets/flatlab/js/jquery.js"></script>
<script src="<?=base_url()?>assets/flatlab/js/bootstrap.min.js"></script>


</body>

<!-- Mirrored from thevectorlab.net/flatlab/login.html by HTTrack Website Copier/3.x [XR&CO'2010], Sun, 09 Feb 2014 08:47:29 GMT -->
</html>
