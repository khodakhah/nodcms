
              <!-- page start-->
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
							<li><a href="<?php echo base_url(); ?>profile">  <i class="fa fa-user"></i> <?=_l('Account',$this);?></a></li>
							<li class="active"><a class="select" href="<?php echo base_url(); ?>profile-detail"> <i class="fa fa-pencil-square-o"></i> <?=_l('Edit Details',$this);?></a></li>
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
                  <aside class="profile-info col-lg-9 pull-right col-md-9 col-sx-12">
                      <section class="panel">
                          <div class="bio-graph-heading">
                              <h1 class="yekan"><?php echo isset($banners['firstname'])?$banners['firstname']:""; ?>&nbsp;<?php echo isset($banners['lastname'])?$banners['lastname']:""; ?></h1>
							  <?php echo isset($banners['company'])?$banners['company']:""; ?> 
                          </div>
                          <div class="panel-body bio-graph-info">
						  <link rel="stylesheet" type="text/css" href="<?php echo base_url();?>assets/frontend/fa_css/validationEngine.jquery.css" />
                              <h1 class="def-noraml"><?=_l('Edit Profile',$this);?> </h1>
                              <form class="form-horizontal" role="form" id="detailform" enctype="multipart/form-data" method="post">
                                  <div class="form-group">
                                      <label  class="col-lg-2 control-label"><?=_l('First Name',$this);?></label>
                                      <div class="col-lg-6 pull-right">
                                          <input name="data[firstname]" type="text" class="form-control validate[required]" id="f-name" value="<?php echo isset($banners['firstname'])?$banners['firstname']:""; ?>" placeholder="<?=_l('First Name',$this);?>">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label  class="col-lg-2 control-label"><?=_l('Last Name',$this);?></label>
                                      <div class="col-lg-6 pull-right">
                                          <input type="text" name="data[lastname]" class="form-control validate[required]" id="l-name" value="<?php echo isset($banners['lastname'])?$banners['lastname']:""; ?>" placeholder="<?=_l('Last Name',$this);?>">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label  class="col-lg-2 control-label"><?=_l('User Name',$this);?></label>
                                      <div class="col-lg-6 pull-right">
                                          <input type="text" name="data[username]" readonly="" class="form-control validate[required]" value="<?php echo isset($banners['username'])?$banners['username']:""; ?>" id="c-name" placeholder="<?=_l('User Name',$this);?>">
										  <?php if(isset($error_username)){ ?><p style="margin: 0 0 0 58px;"  class="error"><?php echo _l('Username has been used!',$this);?></p><?php } ?>
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label  class="col-lg-2 control-label"><?=_l('E-mail',$this);?></label>
                                      <div class="col-lg-6 pull-right">
                                          <input type="text" name="data[email]" readonly="" class="form-control validate[required],custom[email]" value="<?php echo isset($banners['email'])?$banners['email']:""; ?>" id="email" placeholder="<?=_l('E-mail',$this);?>">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label  class="col-lg-2 control-label"><?=_l('Mobile',$this);?></label>
                                      <div class="col-lg-6 pull-right">
                                          <input type="text" name="data[post_code]" class="form-control" value="<?php echo isset($banners['post_code'])?$banners['post_code']:""; ?>" id="mobile" placeholder="<?=_l('Mobile',$this);?>">
                                      </div>
                                  </div>
								  <div class="form-group">
                                      <label  class="col-lg-2 control-label"><?=_l('Telephone',$this);?></label>
                                      <div class="col-lg-6 pull-right">
                                          <input type="text"  name="data[phone]" value="<?php echo isset($banners['phone'])?$banners['phone']:""; ?>" class="form-control" id="mobile" placeholder="<?=_l('Telephone',$this);?>">
                                      </div>
                                  </div>
                                  <div class="form-group">
                                      <label  class="col-lg-2 control-label"><?=_l('Cart ID',$this);?></label>
                                      <div class="col-lg-6 pull-right">
                                          <input type="text" name="data[paypal]" value="<?php echo isset($banners['paypal'])?$banners['paypal']:""; ?>" class="form-control" id="url" placeholder="<?=_l('Cart ID',$this);?>">
                                      </div>
                                  </div>
								  
								  <div class="form-group">
                                      <label  class="col-lg-2 control-label"><?=_l('Profile picture',$this);?></label>
                                      <div class="col-lg-6 pull-right">	
										<?php foreach($avatars as $data){?>
										<div class="myavatar col-lg-3 col-md-6" style="height: 130px;">
										  <label for="<?php echo $data; ?>">
										   <?php if('upload_file/avatars/'.$data==$banners['avatar']){$class="select-avatar";}else{$class="";} ?>
										  <img style="width:100%;" src="<?php echo base_url();?>upload_file/avatars/<?php echo $data; ?>" class=" <?php if(isset($class)){ echo $class;} ?>" />
										  </label>
                                          <input name="image" type="radio"  <?php if('upload_file/avatars/'.$data==$banners['avatar']){echo "checked";} ?> value="<?php echo $data; ?>" class="default" id="<?php echo $data;  ?>"  />
										</div>
										<?php } ?>
										  
                                      </div>
                                  </div>
								  <!--
								  <div class="form-group">
                                      <label  class="col-lg-2 control-label">شماره کارت</label>
                                      <div class="col-lg-6 pull-right">
                                          <input type="text" class="form-control" id="url" placeholder="شماره کارت شما ">
                                      </div>
                                  </div>
								  
								  <div class="form-group">
                                      <label  class="col-lg-2 control-label">نام بانک حساب</label>
                                      <div class="col-lg-6 pull-right">
                                          <input type="text" class="form-control" id="url" placeholder="بانک شما ">
                                      </div>
                                  </div>
								  
								  <div class="form-group">
									  <label  class="col-lg-2 control-label">تصوير پروفايل</label>
									  <div class="col-lg-6 pull-right">
										  <input type="file" class="file-pos" id="exampleInputFile">
									  </div>
								  </div>
                                  <div class="form-group">
                                      <label  class="col-lg-2 control-label">درباره من</label>
                                      <div class="col-lg-10 pull-right">
                                          <textarea name="" id="" class="form-control" cols="30" rows="10"></textarea>
                                      </div>
                                  </div> -->

                                  <div class="form-group">
                                      <div class="col-lg-offset-2 col-lg-10 margin-right-bt">
                                          <button type="submit" class="btn btn-success"><?=_l('Update',$this);?></button>
                                          <a href="<?php echo base_url(); ?>/profile-show" class="btn btn-default"><?=_l('Cancel',$this);?></a>
                                      </div>
                                  </div>
                              </form>
                          </div>
                      </section>
                      
                  </aside>
              </div>

              <!-- page end-->


    <!-- js placed at the end of the document so the pages load faster -->
    <script src="<?php echo base_url(); ?>assets/frontend/js/jquery.js"></script>
    <script src="<?php echo base_url(); ?>assets/frontend/js/bootstrap.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/frontend/js/jquery.scrollTo.min.js"></script>
    <script src="<?php echo base_url(); ?>assets/frontend/js/jquery.nicescroll.js" type="text/javascript"></script>
    <script src="<?php echo base_url(); ?>assets/frontend/assets/jquery-knob/js/jquery.knob.js"></script>

    <!--common script for all pages-->
    <script src="<?php echo base_url(); ?>assets/frontend/js/common-scripts.js"></script>
	<script type="text/javascript">
      $(document).ready(function() {
        // Initiate the validation engine.
        $('#detailform').validationEngine('attach', {promptPosition : "centerRight", scroll: false});
      });
    </script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.validationEngine.js"></script>
    <script type="text/javascript" src="<?php echo base_url(); ?>assets/frontend/js/jquery.validationEngine-fa.js"></script>