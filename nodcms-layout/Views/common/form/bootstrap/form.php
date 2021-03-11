<?php echo $this->render($form_theme); ?>
<?php if(isset($attr['data-grecaptcha']) && $attr['data-grecaptcha']==1){ ?>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
<?php } ?>
<script>
    $(function () {
        <?php if($this->request->isAjax()){ ?>
        var import_js_files = <?php  echo json_encode($this->getAllJsAssets()); ?>;
        var import_css_files = <?php echo json_encode($this->getAllCssAssets()); ?>;
        if(import_js_files.length > 0){
            $.each(import_js_files, function (key, val) {
                var url = $('body').attr('data-base-url') + '/' + val;
                if($('script[src="'+url+'"]').length == 0){
                    $.ajax({
                        url: url,
                        dataType: "script"
                    });
                }
            });
        }
        if(import_css_files.length > 0){
            $.each(import_css_files, function (key, val) {
                var url = $('body').attr('data-base-url') + '/' + val;
                if($('link[href="'+url+'"]').length == 0){
                    $('head').append('<link rel="stylesheet" href="'+url+'" type="text/css" />');
                }
            });
        }
        <?php } ?>

        var nodcmsFormLoad = function () {
            if(!$.fn.nodcmsForm){
                setTimeout(nodcmsFormLoad,1000);
                return;
            }
            $('#<?php echo $form_id; ?>').nodcmsForm();
            $('#<?php echo $form_id; ?>').find('.form-first-loading').remove();
        };
        if(!$.fn.nodcmsForm){
            $('<div class="text-center padding-20 border-1 border-grey margin-bottom-20 form-first-loading"><i class="fas fa-spinner fa-pulse"></i> Form loading...</div>')
                .prependTo('#<?php echo $form_id; ?>');
            setTimeout(nodcmsFormLoad,1000);
        }else{
            $('#<?php echo $form_id; ?>').nodcmsForm();
        }

    });
</script>
