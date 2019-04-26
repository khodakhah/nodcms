<?php if(is_null($content) || (is_string($content) && $content=='') || (is_numeric($content) && $content==0)){ ?>
    <i class="fas fa-times font-grey-salsa"></i>
<?php }else{ ?>
    <i class="fas fa-check font-green"></i>
<?php } ?>
