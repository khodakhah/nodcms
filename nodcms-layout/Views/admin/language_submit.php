<?php echo $submit_form; ?>
<script>
    $(function () {
        var language_codes = <?php echo json_encode($language_codes); ?>;
        $.fn.setLanguageValues = function () {
            if(this.val()!="?" && this.val() != 0){
                if(language_codes[this.val()] != 'undefined'){
                    $("#language_title").val(language_codes[this.val()].title);
                    $("#code").attr("readonly","readonly").val(language_codes[this.val()].code);
                }
            }else if(this.val() != 0){
                $("#language_title").val("");
                $("#code").removeAttr("readonly").val("");
            }
            return this;
        };

        $("#languages").change(function () {
            $(this).setLanguageValues();
        });

        if(typeof $("#languages").val()!== 'undefined' && language_codes[$("#languages").val()] != 'undefined'){
            $("#code").attr("readonly","readonly");
        }

    });
</script>
