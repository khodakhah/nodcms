<script src="<?php echo base_url("assets/plugins/bootstrap-confirmation/bootstrap-confirmation.min.js"); ?>"></script>
<script>
    $(function(){
        $.fn.makeConfirmationBtn = function () {
            // TODO: Make an alternative.
            // Force stop confirmation button. Because it not working with jquery latest version.
            return;

            if(typeof $(this).attr('onclick')!="undefined"){
                var the_action = $(this).attr('onclick');
                $(this).on('confirmed.bs.confirmation', function () {
                    eval(the_action);
                });
                $(this).removeAttr('onclick');
            }
            $(this).confirmation({
                container: 'body',
                btnOkClass: 'btn-xs btn-success',
                btnCancelClass: 'btn-xs btn-danger',
                singleton: true,
                popout: true,
                btnOkIcon: 'fa fa-check',
                btnCancelIcon: 'fa fa-times',
                placement: 'left',
                title:$(this).attr('data-msg'),
                btnOkLabel: '<?php echo _l("Yes please!",$this); ?>',
                btnCancelLabel: '<?php echo _l("No Stop!",$this); ?>'
            });
        };
        $('.btn-ask').each(function(){
            $(this).makeConfirmationBtn();
        });
    });
</script>