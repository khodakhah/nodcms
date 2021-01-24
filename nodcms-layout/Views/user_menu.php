<?php if($this->userdata != null ){ ?>
    <?php if(isset($mistakes) && count($mistakes)!=0){ ?>
        <li class="dropdown dropdown-extended dropdown-notification" id="header_task_bar">
            <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
                <i class="icon-info"></i>
                <span class="badge badge-danger"> <?php echo count($mistakes); ?> </span>
            </a>
            <ul class="dropdown-menu">
                <li>
                    <ul class="dropdown-menu-list scroller" style="height: 275px;" data-handle-color="#637283">
                        <?php foreach($mistakes as $item){ ?>
                            <li>
                                <?php echo $item; ?>
                            </li>
                        <?php } ?>
                    </ul>
                </li>
            </ul>
        </li>
    <?php } ?>


<li class="dropdown dropdown-user dropdown-dark">
    <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
        <span class="username username-hide-on-mobile"> <?=$this->session->userdata('username')?> </span>
        <?php if($this->userdata['avatar']!=""){ ?>
            <img alt="" class="img-circle" src="<?php echo base_url($this->userdata['avatar']) ?>"/>
        <?php } ?>
    </a>
    <ul class="dropdown-menu dropdown-menu-default">
        <li><a href="<?php echo base_url("user/dashboard"); ?>"><i class="icon-speedometer"></i><?php echo _l('Dashboard',$this);?></a></li>
        <?php if($this->session->userdata('group')==1){ ?>
            <li><a href="<?php echo base_url("admin"); ?>"><i class="icon-grid"></i><?php echo _l('Control Panel',$this);?></a></li>
            <li><a href="<?php echo base_url("admin/settings"); ?>"><i class="icon-settings"></i><?=_l('Settings',$this);?></a></li>
        <?php } ?>
        <li><a href="<?php echo base_url($this->language['code']); ?>/account-setting"><i class="icon-user"></i><?=_l('Account Setting',$this);?></a></li>
        <li class="divider">
        <li><a href="<?php echo base_url("logout"); ?>"><i class="icon-power"></i><?=_l('Log Out',$this);?></a></li>
    </ul>
</li>
<?php }else{ ?>
    <li class="dropdown  dropdown-dark">
        <a href="javascript:;" class="dropdown-toggle" data-toggle="dropdown" data-hover="dropdown" data-close-others="true">
            <span class="username username-hide-on-mobile">
                <?php echo _l("Account", $this); ?>
            </span>
            <i class="fa fa-angle-down"></i>
        </a>
        <ul class="dropdown-menu dropdown-menu-default">
            <li>
                <a href="<?php echo base_url($lang); ?>/login">
                    <i class="icon-lock-open"></i>
                    <?php echo _l("Login",$this); ?>
                </a>
            </li>
            <?php if($settings['registration']==1){ ?>
            <li>
                <a href="<?php echo base_url($lang); ?>/user-registration">
                    <i class="icon-user-follow"></i>
                    <?php echo _l("Register",$this); ?>
                </a>
            </li>
            <?php } ?>
        </ul>
    </li>
<?php } ?>