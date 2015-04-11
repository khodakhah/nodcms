<div class="row">
    <aside class="profile-nav col-md-3">
        <section class="panel">
            <div class="user-heading round">
                <a href="#">
                    <?php if($banners["avatar"]=="NULL" || $banners["avatar"]=="" ){?>
                    <img src="<?php echo base_url(); ?>upload_file/avatars/avatar-00.png" alt="<?=_l('Account',$this);?>" />
                    <?php }else{ ?>
                    <img src="<?php echo base_url().$banners["avatar"]; ?>"  alt="<?=_l('Account',$this);?>" />
                    <?php } ?>
                </a>
            </div>
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a class="select" href="<?php echo base_url(); ?>profile">  <i class="fa fa-user"></i> <?=_l('Account',$this);?></a></li>
                <li><a href="<?php echo base_url(); ?>profile-detail"> <i class="fa fa-pencil-square-o"></i> <?=_l('Edit Details',$this);?></a></li>
                <li><a href="<?php echo base_url(); ?>profile-password"> <i class="fa fa-lock"></i> <?=_l('Chang Password',$this);?> </a></li>
                <?php if(isset($_SESSION["user"]["user_type"]) && $_SESSION["user"]["user_type"]==1){ ?>
                <li><a href="<?php echo base_url(); ?>profile-extension"> <i class="fa fa-code"></i> <?=_l('Manage Extensions',$this);?> </a></li>
                <li><a href="<?php echo base_url(); ?>profile/sale"> <i class="fa fa-money"></i> <?=_l('Your Sales',$this);?></a></li>
                <?php }else{ ?>
                <li><a href="<?php echo base_url(); ?>profile/request"> <i class="fa fa-money"></i> <?=_l('Your Request',$this);?></a></li>
                <?php } ?>
            </ul>
        </section>
    </aside>
    <div class="profile-nav col-md-9">
        <section class="panel">
            <header class="panel-heading"><?=_l("Your Request",$this)?></header>
            <div class="panel-body">
                <p><?=_l("You can send a request to admin of site, for use make money system. wright your request and click Submit.",$this)?></p>
                <?php if(isset($_SESSION["user"]["user_request"]) && $_SESSION["user"]["user_request"]!=1){ ?>
                <form action="" method="post">
                    <div class="control-group">
                        <label><?=_l("Request text",$this)?></label>
                        <textarea name="request" class="form-control"><?=_l("Hi, Pleas upgrade my account, I want to use make money system in your website.",$this)?></textarea>
                    </div>
                    <div class="control-group text-center" style="margin-top: 10px">
                        <input class="btn btn-success" name="submit" type="submit" value="<?=_l("Submit",$this)?>">
                    </div>
                </form>
                <?php }else{ ?>
                <div class="alert alert-success"><?=_l("Your request send successfully, after admin accept you can send application.",$this)?></div>
                <?php } ?>
            </div>
        </section>
    </div>
</div>
<!--<link href="--><?//=base_url()?><!--assets/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />-->
<link rel="stylesheet" href="<?=base_url()?>assets/flatlab/assets/data-tables/DT_bootstrap.css" />
<link rel="stylesheet" href="<?=base_url()?>assets/flatlab/assets/data-tables/DT_bootstrap-rtl.css" />
