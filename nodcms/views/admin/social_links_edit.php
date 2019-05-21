<?php
mk_hpostform(ADMIN_URL."socialLinksSubmit".(isset($data['id'])?"/".$data['id']:""));
mk_hselect("class",_l('Type',$this), $social_types, "class", "title", isset($data['class'])?$data['class']:null,"-", 'style="width:200px;"');
mk_hidden("title", isset($data['title'])?$data['title']:'');
mk_hurl("url",_l('URL',$this), isset($data['url'])?$data['url']:'',"style='direction:ltr'");
mk_hsubmit(_l('Submit',$this), ADMIN_URL."socialLinks", _l('Cancel',$this));
mk_closeform();
?>
<script>
    $(function () {
        $("#class").change(function(){
            $("#title").val($(this).find(':selected').text());
        });
    });
</script>
