<form class="form-signin" action="" method="post" id="post_form">
        <h2 class="form-signin-heading"><?=_l('User Login',$this);?></h2>
        <div class="login-wrap">
            <div class="control-group">
                <input type="text" class="form-control" name="data[email]" id="email" placeholder="<?=_l('Email',$this);?>" autofocus>
            </div>
            <div class="control-group">
                <input type="password" class="form-control"  name="data[password]" id="password" placeholder="<?=_l('Password',$this);?>">
            </div>
            <div class="input-group">
                <label class="checkbox">
                    <input type="checkbox" name="keep_login" value="1"> <?=_l('Keep me logged in',$this);?>
                </label>
            </div>
            <div style="margin: 10px 0;">
                <button class="btn btn-lg btn-block btn-success btn-shadow" type="submit"><?=_l('Sign in',$this);?></button>
            </div>
            <div class="row">
                <div class="col-lg-8 col-mc-8 col-sm-8 col-xm-12">
                    <a class="btn btn-block btn-link" href="<?=base_url().$lang?>/forget-password"><span class="fa fa-unlock"></span> <?=_l("Forgot My Password",$this)?></a>
                </div>
                <div class="col-lg-4 col-mc-4 col-sm-4 col-xm-12">
                    <a class="btn btn-block btn-danger btn-shadow" href="<?=base_url().$lang?>/register"><?=_l('Sign Up',$this);?></a>
                </div>
            </div>
            <?php if(isset($login_error) && $login_error!=null){ ?>
                <div class="alert alert-danger text-center" style="margin: 10px 0;"><?=$login_error?></div>
            <?php } ?>
        </div>
</form>

<script type="text/javascript" src="<?=base_url()?>assets/flatlab/js/jquery.validate.min.js"></script>
<script>
    $("#post_form").validate({
        rules: {
            "data[email]": {
                required: true,
                email: true
            },
            "data[password]": {
                required: true
            }
        },
        messages: {
            "data[email]":{
                required: "<?=_l("Please enter a valid email address.",$this)?>",
                email: "<?=_l("Please enter a valid email address.",$this)?>"
            },
            "data[password]":{
                required: "<?=_l("Please enter a valid email address.",$this)?>"
            }
        }
    });
</script>