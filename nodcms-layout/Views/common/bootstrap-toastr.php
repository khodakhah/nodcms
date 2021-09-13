<link rel="stylesheet" type="text/css" href="<?php echo base_url("assets/plugins/bootstrap-toastr/toastr.min.css"); ?>"/>
<script src="<?php echo base_url("assets/plugins/bootstrap-toastr/toastr.min.js"); ?>"></script>
<script>
    $(function(){
        toastr.options = {
            "closeButton": true,
            "debug": false,
            "positionClass": "toast-top-right",
            "onclick": null,
            "showDuration": "1000",
            "hideDuration": "1000",
            "timeOut": "5000",
            "extendedTimeOut": "1000",
            "showEasing": "swing",
            "hideEasing": "linear",
            "showMethod": "fadeIn",
            "hideMethod": "fadeOut"
        };

        <?php if(session()->has('error')){ ?>
            toastr.error("<?php echo session()->getFlashdata('error'); ?>", '<?php echo _l("Error", $this)?>');
        <?php }elseif(session()->has('success')){ ?>
            toastr.success("<?php echo session()->getFlashdata('success'); ?>", '<?php echo _l("Success", $this)?>');
        <?php } ?>
    });
</script>