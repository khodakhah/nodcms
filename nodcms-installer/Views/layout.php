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

$this->addCssFile("assets/nodcms/bootstrap-4.1.3/css/bootstrap.min");
$this->addCssFile("assets/toastr/toastr.min");

$this->addHeaderJsFile("assets/jquery-3.4.0.min");

$this->addJsFile("assets/nodcms/js/common.min");
$this->addJsFile("assets/nodcms/bootstrap-4.1.3/js/bootstrap.min");
$this->addJsFile("assets/toastr/toastr.min");
$this->addJsFile("assets/plugins/popper/popper.min");
$this->addJsFile("assets/plugins/bootstrap-confirmation/bootstrap-confirmation.min");
?>
<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" lang="en">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta name="description" content="<?php echo isset($description)?$description:""; ?>">
    <meta name="author" content="<?php echo isset($author)?$author:""; ?>">
    <meta name="keyword" content="<?php echo isset($keyword)?$keyword:""; ?>">
    <?php if(isset($this->settings["fav_icon"]) && $this->settings["fav_icon"]!=''){ ?>
        <link rel="shortcut icon" href="<?php echo base_url($this->settings["fav_icon"]); ?>">
    <?php } ?>
    <title><?php echo $title; ?> <?php echo isset($sub_title)?$sub_title:""; ?></title>
    <?php echo $this->settings['add_on_header']; ?>
    <link href="//fonts.googleapis.com/css?family=Open+Sans:400,300,600,700&subset=all" rel="stylesheet" type="text/css"/>
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/all.css">
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.2.0/css/v4-shims.css">
    <?php $this->fetchAllCSS(); ?>
    <?php $this->fetchAllHeaderJS(); ?>
    <?php echo csrf_meta("csrf_meta"); ?>
</head>
<body data-base-url="<?php echo base_url(); ?>" class="page-container-bg-solid page-sidebar-closed-hide-logo <?php echo (count($this->page_sidebar_items)==0 || $this->page_sidebar_closed == true)?' page-full-width page-sidebar-closed':''; ?>">
<div class="container">
    <div class="bg-header navbar-bordered">
        <div class="container-fluid">
            <div class="row">
                <div class="col-md-2 col-sm-6">
                    <a href="<?php echo base_url(); ?>">
                        <img class="img-fluid site-logo" src="<?php echo base_url($this->settings["logo"]); ?>" alt="<?php echo $this->settings["company"]; ?>" title="<?php echo $this->settings["company"]; ?>">
                    </a>
                </div>
                <div class="col">
                    <div class="navbar navbar-expand-lg navbar-light navbar-top">
                        <ul class="navbar-nav flex-column-reverse">
                            <li class="nav-item">
                                <a target="_blank" href="https://chictheme.com/en/article/nodcms-based-applications" class="nav-link" title="<?php echo _l("Help", $this); ?>">
                                    <i class="far fa-question-circle"></i>
                                </a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row no-gutters">
        <?php if(count($this->page_sidebar_items)!=0){ ?>
            <div class="sidebar-col d-print-none">
                <ul class="nav flex-column nodcms-sidebar h-100">
                    <li class="nav-item sidebar-minimizer">
                        <a class="nav-link text-right" href="javascript:;"><i class="fas fa-chevron-left"></i></a>
                    </li>
                    <?php $this->load->view($this->page_sidebar); ?>
                </ul>
            </div>
        <?php } ?>
        <!-- BEGIN CONTENT -->
        <div class="col">
            <div class="page-content">
                <?php if(isset($steps)){ ?>
                <div class="row no-gutters">
                    <?php echo join("\n", $steps); ?>
                </div>
                <?php } ?>
                <div class="d-flex mt-3 mb-3">
                    <h1><?php echo $title; ?></h1>
                    <?php if(isset($sub_title) && !empty($sub_title)) { ?>
                        <h2 class="mt-2 ml-4"><?php echo $sub_title; ?></h2>
                    <?php } ?>
                </div>
                <?php echo isset($content)?$content:''; ?>
                <!-- END DASHBOARD STATS -->
                <div class="clearfix"></div>
            </div>
        </div>
        <!-- END CONTENT -->
    </div>

    <?php if(isset($social_links) && count($social_links)!=0) { ?>
        <div class="container">
            <div class="padding-top-10 padding-bottom-10">
                <?php foreach($social_links as $item) { ?>
                    <a class="btn default" href="<?php echo $item['url']; ?>" target="_blank" title="<?php echo $item['title']; ?>"><i class="fab fa-<?php echo $item['class']; ?>"></i></a>
                <?php } ?>
            </div>
        </div>
    <?php } ?>
    <div class="mt-5">
        <?php echo $this->common()->render("copyright"); ?>
    </div>
</div>
<?php $this->fetchAllJS(); ?>
<script>
    $(function(){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-full-width",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "5000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };
        <?php if($this->flashdata('success')){ ?>
        toastr['success']("<?php echo $this->flashdata('success'); ?>", "<?php echo _l("Success",$this); ?>");
        <?php } ?>
        <?php if($this->flashdata('error')){ ?>
        toastr['error']("<?php echo $this->flashdata('error'); ?>", "<?php echo _l("Error",$this); ?>");
        <?php } ?>
        <?php if($this->flashdata('message')){ ?>
        toastr['info']("<?php echo $this->flashdata('message'); ?>", "<?php echo _l("Info",$this); ?>");
        <?php } ?>
    });
</script>
<script>
    $(function(){
        $.fn.makeConfirmationBtn = function () {
            if(typeof $(this).attr('onclick')!="undefined"){
                var the_action = $(this).attr('onclick');
                $(this).on('confirmed.bs.confirmation', function () {
                    eval(the_action);
                });
                $(this).removeAttr('onclick');
            }
            var title;
            switch(typeof $(this).attr('data-msg')){
                case "undefined":
                    title = "<?php echo _l("Are you sure?", $this); ?>";
                    break;
                case "":
                    title = "<?php echo _l("Are you sure?", $this); ?>";
                    break;
                default:
                    title = $(this).attr('data-msg');
            }
            $(this).confirmation({
                rootSelector: 'body',
                btnOkClass: 'btn-sm green-soft',
                btnCancelClass: 'btn-sm red-soft',
                popout: true,
                singleton: true,
                btnOkIconClass: 'fa fa-check margin-right-10 margin-left-10',
                btnCancelIconClass: 'fa fa-times margin-right-10 margin-left-10',
                placement: 'left',
                title: title,
                btnOkLabel: '<?php echo _l("Yes please!",$this); ?>',
                btnCancelLabel: '<?php echo _l("No Stop!",$this); ?>'
            });
        };

        $.setCurrencyFormatSettings({
            before_sign: '<?php echo $this->settings["currency_sign_before"] == 1?$this->settings["currency_sign"]:''; ?>',
            after_sign: '<?php echo $this->settings["currency_sign_before"] == 1?'':$this->settings["currency_sign"]; ?>',
            currency_code: '<?php echo $this->settings["currency_code"]; ?>',
            // Only able to be "1.234,56", "1,234.56", "1.234", and "1,234"
            number_format: '<?php echo $this->settings["currency_format"]; ?>'
        });

        $('.btn-ask').each(function(){
            $(this).makeConfirmationBtn();
        });

        <?php if($page!=''){ ?>
        $('#<?php echo $page; ?>').highlight_selected_menu();
        <?php } ?>
    });
</script>
<script>
    $(function () {
        $('.nodcms-sidebar').each(function () {
            var $this = $(this);
            var $parent = $this.parent();
            $this.find('.sidebar-minimizer').click(function () {
                $parent.toggleClass("minimized");
                $(this).find("i").toggleClass("fa-chevron-left fa-chevron-right");

            });
            $this.find(".nav-toggle").each(function () {
                var $navToggle = $(this);
                $(this).parent().addClass("open");
                $navToggle.click(function () {
                    $(this).parent().toggleClass("open");
                });
            });
        });
    });
</script>
<?php $this->fetchAllJS(); ?>
<?php echo $this->settings['add_on_script']; ?>
</body>
</html>
