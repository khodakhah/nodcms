<?php
/*
 *  This file is part of NodCMS.
 *
 *  (c) Mojtaba Khodakhah <info@nodcms.com>
 *  https://nodcms.com
 *
 * For the full copyright and license information, please view
 * the LICENSE file that was distributed with this source code.
 *
 */

$this->addCssFile("assets/nodcms/bootstrap-4.1.3/css/nodcms-admin.min", "assets/nodcms/bootstrap-4.1.3/css/nodcms-admin-rtl.min");
$this->addHeaderJsFile("assets/jquery-3.4.0.min");
$this->addJsFile("assets/plugins/jquery-ui-1.12.1/jquery-ui.min");
$this->addJsFile("assets/plugins/popper/popper.min");
$this->addJsFile("assets/nodcms/bootstrap-4.1.3/js/bootstrap.min");
$this->addJsFile("assets/nodcms/js/common");

?>
<!DOCTYPE html>
<!--[if IE 8]> <html lang="en" class="ie8 no-js"> <![endif]-->
<!--[if IE 9]> <html lang="en" class="ie9 no-js"> <![endif]-->
<!--[if !IE]><!-->
<html lang="<?php $this->language['code']; ?>">
<!--<![endif]-->
<!-- BEGIN HEAD -->
<head>
    <meta charset="utf-8"/>
    <title><?php echo $title; ?></title>

    <?php echo csrf_meta("csrf_meta"); ?>
    <?php echo $this->settings['add_on_header']; ?>

    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta content="width=device-width, initial-scale=1.0" name="viewport"/>
    <meta http-equiv="Content-type" content="text/html; charset=utf-8">
    <meta content="" name="description"/>
    <meta content="" name="author"/>
    <link rel="shortcut icon" href="<?php echo base_url($this->settings["fav_icon"]); ?>">
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <?php $this->fetchAllCSS(); ?>
    <?php $this->fetchAllHeaderJS(); ?>
</head>
<body data-base-url="<?php echo base_url(); ?>" class="bg-grey-mint">
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-5 margin-top-40">
            <div class="text-center margin-bottom-40">
                <a href="<?php echo base_url(); ?>">
                    <img class="img-fluid" src="<?php echo base_url($this->settings['logo_light']); ?>" alt=""/>
                </a>
            </div>
            <div class="card">
                <div class="card-body">
                    <?php echo $content; ?>
                </div>
            </div>
            <div class="font-grey margin-top-20 text-center">
                <?php echo $this->common()->render("copyright"); ?>
            </div>
        </div>
    </div>
</div>

<?php $this->fetchAllJS(); ?>
<?php echo $this->common()->render("common/bootstrap-toastr"); ?>
<?php echo $this->common()->render("common/bootstrap-confirmation"); ?>

</body>
</html>
