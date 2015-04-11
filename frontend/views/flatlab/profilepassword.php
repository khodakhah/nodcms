<div class="container">
      <section>
          <div class="panel panel-primary">
              <div class="panel-heading bg-head"> <?=_l('Change Passwrod',$this);?></div>
              <div class="panel-body">
                  <?php if($this->session->flashdata('message_success')){ ?><div class="alert alert-success"><span class="fa fa-check"></span> <?=$this->session->flashdata('message_success')?></div><?php } ?>
                  <form class="form-horizontal" method="post" id="post_form" action="">
                      <div class="form-group <?=($this->session->flashdata('old_password_error') || $this->session->flashdata('error'))?"has-error":""?>">
                          <label for="old_password" class="col-lg-2 control-label"><?=_l('Last Password',$this);?></label>
                          <div class="col-lg-6">
                              <input type="password" name="old_password" class="form-control" id="old_password">
                              <?php if($this->session->flashdata('old_password_error')){ ?><p class="help-block"><?=$this->session->flashdata('old_password_error')?></p><?php } ?>
                              <?php if($this->session->flashdata('error')){ ?><p class="help-block"><?=$this->session->flashdata('error')?></p><?php } ?>
                          </div>
                      </div>
                      <hr>
                      <div class="form-group <?=$this->session->flashdata('error')?"has-error":""?>">
                          <label for="password" class="col-lg-2 control-label"><?=_l('New password',$this);?></label>
                          <div class="col-lg-6">
                              <input type="password" name="password" class="form-control" id="password">
                              <?php if($this->session->flashdata('error')){ ?><p class="help-block"><?=$this->session->flashdata('error')?></p><?php } ?>
                          </div>
                      </div>
                      <div class="form-group <?=$this->session->flashdata('error')?"has-error":""?>">
                          <label for="confirm_password" class="col-lg-2 control-label"><?=_l('Password Confirm',$this);?></label>
                          <div class="col-lg-6">
                              <input type="password" name="confirm_password" class="form-control" id="confirm_password">
                              <?php if($this->session->flashdata('error')){ ?><p class="help-block"><?=$this->session->flashdata('error')?></p><?php } ?>
                          </div>
                      </div>

                      <div class="form-group">
                          <div class="col-lg-offset-2 col-lg-10">
                              <button type="submit" class="btn btn-success"><?=_l('Submit',$this);?></button>
                              <a href="<?=base_url().$lang?>" class="btn btn-default"><?=_l('Cancel',$this);?></a>
                          </div>
                      </div>
                  </form>
              </div>
          </div>
      </section>
</div>

<script type="text/javascript" src="<?=base_url()?>assets/flatlab/js/jquery.validate.min.js"></script>
<script>
    $("#post_form").validate({
        rules: {
            old_password: {
                required: true,
                minlength: 6
            },
            password: {
                required: true,
                minlength: 6
            },
            confirm_password: {
                required: true,
                minlength: 6,
                equalTo: "#password"
            }
        },
        messages: {
            old_password: {
                required: "<?=_l("Please provide a password",$this)?>",
                minlength: "<?=_l("Your password must be at least 6 characters long",$this)?>"
            },
            password: {
                required: "<?=_l("Please provide a password",$this)?>",
                minlength: "<?=_l("Your password must be at least 6 characters long",$this)?>"
            },
            confirm_password: {
                required: "<?=_l("Please provide a password",$this)?>",
                minlength: "<?=_l("Your password must be at least 6 characters long",$this)?>",
                equalTo: "<?=_l('Please enter the same password as above',$this)?>"
            }
        }
    });
</script>