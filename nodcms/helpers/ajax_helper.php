<?php 
function ajax_addExtensionComment($url){
    ?>
    <script>
        $("#comment_text").keyup(function(){
            var thistext = $(this).val(); if(thistext.match(/\n/g)==null){ $(this).attr('rows',1); }else{ $(this).attr('rows',parseInt(thistext.match(/\n/g).length)+1); }
        });
        $("#comment_send").click(function(){
            $("#comment_text").parent().removeClass("has-error").find(".help-block").remove();
            $.post("<?=$url?>",{"comment":$("#comment_text").val(),"ext_id":$("#comment_text").attr("data-ext-id")},function(msg){
                eval("var data = " + msg);
                if(data.status == 1){
                    alert("hallee");
                    $("#comment_text").val("");
                }else{
                    $("#comment_text").parent().addClass("has-error").append("<p class='help-block'>" + data.errors +"</p>");
                }
            });
        });
    </script>
    <?php
}
