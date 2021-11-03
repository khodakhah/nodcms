<div class="portlet">
    <div class="portlet-body">
        <div class="row">
            <div class="col-md-4">
                <img class="img-responsive" src="<?php echo base_url().$data["avatar"]; ?>">
            </div>
            <div class="col-md-8">
                <div class="row static-info">
                    <div class="col-md-5 name"><?php echo _l("Group", $this); ?></div>
                    <div class="col-md-7 value">
                        <?php if($data["group_id"]==1){ ?>
                            <span class="label label-success"><?php echo $data['group_name']; ?></span>
                        <?php }elseif(in_array($data["group_id"],array(2,20))){ ?>
                            <span class="label label-primary"><?php echo $data['group_name']; ?></span>
                        <?php }elseif($data["group_id"]==100){ ?>
                            <span class="label label-warning"><?php echo $data['group_name']; ?></span>
                        <?php }else{ ?>
                            <span class="label label-danger"><?php echo $data['group_name']; ?></span>
                        <?php } ?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name"><?php echo _l("Created", $this); ?></div>
                    <div class="col-md-7 value"><?php echo my_int_fullDate($data["created_date"]); ?></div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name"><?php echo _l("First name", $this); ?></div>
                    <div class="col-md-7 value"><?php echo $data["firstname"]; ?></div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name"><?php echo _l("Last name", $this); ?></div>
                    <div class="col-md-7 value"><?php echo $data["lastname"]; ?></div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name"><?php echo _l("Email", $this); ?></div>
                    <div class="col-md-7 value">
                        <?php echo $data["email"]; ?>
                        <?php if($data["status"]==1){ ?>
                            <i class="fa fa-check font-green"></i>
                        <?php }else{ ?>
                            <i class="fa fa-times font-red"></i>
                        <?php } ?>
                    </div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name"><?php echo _l("Phone", $this); ?></div>
                    <div class="col-md-7 value"><?php echo $data["mobile"]!=''?$data["mobile"]:'-'; ?></div>
                </div>
                <div class="row static-info">
                    <div class="col-md-5 name"><?php echo _l("Website", $this); ?></div>
                    <div class="col-md-7 value">
                        <?php if(isset($data["website"])){ ?>
                            <a href="<?php echo $data["website"]; ?>" target="_blank"><?php echo $data["website"]; ?></a>
                        <?php }else{ ?>
                            -
                        <?php } ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>