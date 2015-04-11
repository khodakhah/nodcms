<div class="container">
    <div class="panel">
        <h1 class="panel-heading"><?=_l("Active account",$this)?></h1>
        <div class="panel-body">
            <?php if(isset($access_msg)){ ?>
                <div class="alert alert-success"> <span class="fa fa-check"></span>  <?=$access_msg?></div>
                <div class="text-center"><a class="btn btn-success btn-lg" href="<?=base_url()?>login"><?=_l("Login Now",$this)?></a> <a class="btn btn-info btn-lg" href="<?=base_url()?>register"><?=_l("Register New",$this)?></a></div>
            <?php } ?>
            <?php if(isset($warning_msg)){ ?>
                <div class="alert alert-warning"> <span class="fa fa-warning"></span> <?=$warning_msg?></div>
                <div class="text-center"><a class="btn btn-success btn-lg" href="<?=base_url()?>login"><?=_l("Login Now",$this)?></a> <a class="btn btn-info btn-lg" href="<?=base_url()?>register"><?=_l("Register New",$this)?></a></div>
            <?php } ?>
            <?php if(isset($error_msg)){ ?>
                <div class="alert alert-danger"> <span class="fa fa-warning"></span> <?=$error_msg?></div>
                <div class="text-center"><a class="btn btn-success btn-lg" href="<?=base_url()?>login"><?=_l("Login Now",$this)?></a> <a class="btn btn-info btn-lg" href="<?=base_url()?>register"><?=_l("Register New",$this)?></a></div>
            <?php } ?>
        </div>
    </div>
</div>