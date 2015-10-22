
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
	<aside class="profile-info col-lg-9 pull-right">
	  <section>
		  <div class="panel panel-primary">
			  <div class="panel-heading bg-head"> <?=_l('Welcome Dear User',$this);?></div>
			  <div class="panel-body">
				
				<div class="row col-lg-12">
					<div class="col-lg-3 text-center">
						<span class="fontbig"><i class="fa fa-user"></i></span><br/>
						<h3><?=_l('Edit Details',$this);?></h3>
						<p></p>
					</div>
					<div class="col-lg-9">
						<p class="text-justify" style="padding-top: 20px;">
							<?=_l('You must complete by editing your account details or change them. Also, by changing your password to the update action.',$this);?>
						</p>
					</div>
				</div>
				<hr style="clear: both;"/>
				
				<div class="row col-lg-12">
					<div class="col-lg-3 text-center">
						<span class="fontbig"><i class="fa fa-code"></i></span><br/>
						<h3><?=_l('Manage Extensions',$this);?></h3>
						<p></p>
					</div>
					<div class="col-lg-9">
						<p class="text-justify"  style="padding-top: 10px;">
							<?=_l('By this section, you can sell your items to put in your store. You can also edit all your templates. Note that sending the items for sale will be conducted according to the standards and in compliance with standards is item you put up for sale.',$this);?>
						</p>
					</div>
				</div>
				<hr style="clear: both;"/>
				
				<div class="row col-lg-12">
					<div class="col-lg-3 text-center">
						<span class="fontbig"><i class="fa fa-money"></i></span><br/>
						<h3><?=_l('Your Sales',$this);?></h3>
						<p></p>
					</div>
					<div class="col-lg-9">
						<p class="text-justify"  style="padding-top: 20px;">
							<?=_l('In this section, you can sell your number along with the number of sales transactions see. In this section, you can also ask for a pony. Settling accounts in less Azsat will be done immediately.',$this);?>
						</p>
					</div>
				</div>
				<hr style="clear: both;"/>
				
				<div class="row col-lg-12">
					<div class="col-lg-3 text-center">
						<span class="fontbig"><i class="fa fa-shopping-cart"></i></span><br/>
						<h3><?=_l('How to Buy',$this);?></h3>
						<p></p>
					</div>
					<div class="col-lg-9">
						<p class="text-justify">
							<?=_l('You can register our site to purchase wholesale items. To purchase any item page dedicated to each item and click the buy button. Before pay Baydaymyl and enter your phone number for support. Then follow button secure online payment system will be connected. After payment has directed to our site and the pages ahead, you can download the purchased item. Note that the download link will be active for 4 hours at a time and you can just download the purchased item.',$this);?>
						</p>
					</div>
				</div>
				
			  </div>
		  </div>
	  </section>
	</aside>
</div>
