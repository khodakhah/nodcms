<div class="page-title">
    <div class="container">
        <h1><?php echo $title; ?></h1>
    </div>
</div>
<div class="container">
    <div class="row">
        <div class="col-lg-8 col-md-8 col-sm-6 col-xs-12">
            <h4><?php echo _l('Some Tips',$this); ?></h4>
            <ul>
                <li>
                    <p><?php echo _l("This form is just for who is already our website's member!",$this); ?></p>
                </li>
                <li>
                    <p>
                        <?php echo _l("If you don't have any account and didn't sign in before, use the below link before use this form!",$this); ?>
                        <br>
                        <a href="<?=base_url().$lang?>/register"><?=_l('Sign Up',$this);?></a>
                    </p>
                </li>
                <li>
                    <p><?php echo _l('You can use your email address or username for sign.',$this); ?></p>
                </li>
            </ul>
            <?php if($this->session->flashdata('message_success')){ ?>
                <div class="alert alert-success"><?php echo $this->session->flashdata('message_success'); ?></div>
            <?php } ?>
        </div>
        <div class="col-lg-4 col-md-4 col-sm-6 col-xs-12">
            <form class="" action="" method="post" id="post_form" style="width: 300px">
                <div class="form-group">
                    <label for="email"><?php echo _l('Username or Email',$this); ?></label>
                    <input type="text" class="form-control" name="data[email]" id="email" placeholder="<?=_l('Email',$this);?>" autofocus>
                </div>
                <div class="form-group">
                    <label for="password"><?php echo _l('Password',$this); ?></label>
                    <input type="password" class="form-control"  name="data[password]" id="password" placeholder="<?=_l('Password',$this);?>">
                </div>
                <div class="form-group">
                    <label class="checkbox">
                        <input type="checkbox" name="keep_login" value="1"> <?=_l('Keep me logged in',$this);?>
                    </label>
                </div>
                <button class="btn btn-lg btn-primary" type="submit"><?=_l('Sign in',$this);?></button>
                <a class="btn btn-link" href="<?=base_url().$lang?>/forget-password"> <?=_l("I forgot My Password",$this)?></a>
                <?php if(isset($login_error) && $login_error!=null){ ?>
                    <div class="alert alert-danger text-center" style="margin: 10px 0;"><?=$login_error?></div>
                <?php } ?>
            </form>
        </div>
    </div>
</div>

<script type="text/javascript" src="<?=base_url()?>assets/flatlab/js/jquery.validate.min.js"></script>
<script>
    $("#post_form").validate({
        rules: {
            "data[email]": {
                required: true,
            },
            "data[password]": {
                required: true
            }
        },
        messages: {
            "data[email]":{
                required: "<?=_l("Please enter a username or email address.",$this)?>",
            },
            "data[password]":{
                required: "<?=_l("Please enter your password.",$this)?>"
            }
        }
    });
</script>