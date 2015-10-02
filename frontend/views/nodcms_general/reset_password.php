<div class="container">
    <?php if($this->session->flashdata('message_error') && !isset($data)){ ?>
    <div class="alert alert-danger"><?=$this->session->flashdata('message_error')?></div>
    <?php }else{ ?>
    <div class="panel">
        <h1 class="panel-heading bio-graph-heading"><?=$title?></h1>
        <div class="panel-body">
            <?php if($this->session->flashdata('message_success')){ ?>
            <div class="alert alert-success"><?=$this->session->flashdata('message_success')?></div>
            <p class="text-center">
                <a class="btn btn-danger" href="<?=base_url().$lang?>/login"><span class="fa fa-key"></span> <?=_l("Login now",$this)?></a>
                <a class="btn btn-primary" href="<?=base_url().$lang?>"><span class="fa fa-home"></span> <?=_l("Back to home",$this)?></a>
            </p>
            <?php }else{ ?>
            <h3><?=_l("Choose your new password",$this)?></h3>
            <form id="post_form" action="" method="post">
                <div class="form-group <?=$this->session->flashdata('message_error')?"has-error":""?>">
                    <label for="password" class="control-label text-center"><?=_l("Insert your new password",$this)?></label>
                    <input name="password" id="password" type="password" class="form-control input-lg" placeholder="Password">
                    <?php if($this->session->flashdata('message_error')){ ?><p class="help-block"><?=$this->session->flashdata('message_error')?></p><?php } ?>
                </div>
                <div class="form-group <?=$this->session->flashdata('message_error')?"has-error":""?>">
                    <label for="confirm_password" class="control-label text-center"><?=_l("Insert your new password again",$this)?></label>
                    <input name="confirm_password" id="confirm_password" type="password" class="form-control input-lg" placeholder="Require Password">
                    <?php if($this->session->flashdata('message_error')){ ?><p class="help-block"><?=$this->session->flashdata('message_error')?></p><?php } ?>
                </div>
                <div class="form-group text-center">
                    <button type="submit" class="btn btn-success btn-lg"><?=_l("Submit",$this)?></button>
                </div>
                <?php if(isset($submit_error)){ ?>
                <div class="alert alert-danger"><?=$submit_error?></div>
                <?php } ?>
                <?php } ?>
            </form>
        </div>
    </div>
    <?php } ?>
</div>


<script type="text/javascript" src="<?=base_url()?>assets/flatlab/js/jquery.validate.min.js"></script>
<script>
    $("#post_form").validate({
        rules: {
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