<div class="bg-header navbar-bordered">
    <div class="container-fluid">
        <div class="row">
            <div class="col-md-2 col-sm-6">
                <a href="<?php echo base_url(); ?>">
                    <img class="img-fluid site-logo" src="<?php echo base_url($this->settings["logo_light"]); ?>" alt="<?php echo $this->settings["company"]; ?>" title="<?php echo $this->settings["company"]; ?>">
                </a>
            </div>
            <div class="col-md-2 col-sm-6 order-md-12">
                <div class="navbar navbar-expand-lg navbar-light navbar-top">
                    <ul class="navbar-nav">
                        <?php if(isset($languages) && count($languages)>1){ ?>
                            <li class="nav-item dropdown dropdown-language">
                                <a href="javascript:;" class="nav-link" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img alt="" src="<?php echo base_url("upload_file/images/translate.png"); ?>"/>
                                    <?php echo $this->language['language_title']; ?>
                                </a>
                                <div class="dropdown-menu">
                                    <?php foreach($languages as $item) {?>
                                        <?php if($item["language_id"]!=\Config\Services::language()->get()["language_id"]){ ?>
                                            <a class="dropdown-item" href="<?php echo isset($item["lang_url"])?$item["lang_url"]:"javascript:;"; ?>">
                                                <?php echo $item['language_title']; ?>
                                            </a>
                                        <?php } ?>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } ?>
                        <?php if($this->userdata != null ){ ?>
                            <li class="nav-item dropdown dropdown-user">
                                <a href="javascript:;" class="nav-link dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <img alt="<?php echo $this->userdata['username']; ?>" class="rounded-circle user-avatar-img" src="<?php echo get_user_avatar_url($this->userdata); ?>"/>
                                    <span class="hidden-xs"> <?php echo $this->userdata['username']; ?> </span>
                                </a>
                                <div class="dropdown-menu">
                                    <?php if(\Config\Services::modules()->hasMemberDashboard()){ ?>
                                        <a class="dropdown-item" href="<?php echo base_url("user/dashboard"); ?>"><i class="icon-speedometer"></i><?php echo _l('Dashboard',$this);?></a>
                                    <?php } ?>
                                    <?php if(in_array($this->userdata['group_id'],array(1,100))){ ?>
                                        <a class="dropdown-item" href="<?php echo base_url("admin"); ?>"><i class="icon-grid"></i><?php echo _l('Control Panel',$this);?></a>
                                        <a class="dropdown-item" href="<?php echo base_url("admin/settings"); ?>"><i class="icon-settings"></i><?=_l('Settings',$this);?></a>
                                    <?php } ?>
                                    <a class="dropdown-item" href="<?php echo base_url("user/account"); ?>"><i class="icon-user"></i><?=_l('Account Setting',$this);?></a>
                                    <a class="dropdown-item" href="<?php echo base_url("{$this->language['code']}/logout"); ?>"><i class="icon-power"></i><?=_l('Log Out',$this);?></a>
                                </div>
                            </li>
                        <?php }else{ ?>
                            <li class="nav-item dropdown dropdown-user">
                                <a href="javascript:;" class="nav-link" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                                    <?php echo _l("Account", $this); ?>
                                    <i class="fa fa-angle-down"></i>
                                </a>
                                <div class="dropdown-menu">
                                    <a class="dropdown-item" href="<?php echo base_url("{$this->language['code']}/login"); ?>">
                                        <i class="icon-lock-open"></i>
                                        <?php echo _l("Login",$this); ?>
                                    </a>
                                    <?php if($this->settings['registration']==1){ ?>
                                        <a class="dropdown-item" href="<?php echo base_url("{$this->language['code']}/user-registration"); ?>">
                                            <i class="icon-user-follow"></i>
                                            <?php echo _l("Register",$this); ?>
                                        </a>
                                    <?php } ?>
                                </div>
                            </li>
                        <?php } ?>

                    </ul>
                </div>
            </div>
            <div class="col-md-8">
                <?php $this->render("navbar"); ?>
            </div>
        </div>
    </div>
</div>
