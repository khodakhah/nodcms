<?php $this->addCssFile("assets/metronic/pages/css/profile","assets/metronic/pages/css/profile-rtl"); ?>
<div class="portlet">
    <div class="portlet-body">
        <div class="row">
            <div class="col-lg-12">
                <div class="profile-sidebar">
                    <!-- PORTLET MAIN -->
                    <div class="portlet light profile-sidebar-portlet bg-default">
                        <!-- SIDEBAR USERPIC -->
                        <div class="profile-userpic">
                            <img class="img-responsive" alt="<?php echo $data["username"]; ?>" src="<?php echo base_url().$data["avatar"]; ?>">
                            <!-- END SIDEBAR USERPIC -->
                            <!-- SIDEBAR USER TITLE -->
                            <div class="profile-usertitle">
                                <div class="profile-usertitle-name"> <?php echo $data["fullname"]; ?> </div>
                                <div class="profile-usertitle-job"> <?php echo $data["group_name"]; ?> </div>
                            </div>
                            <!-- END SIDEBAR USER TITLE -->
                            <!-- SIDEBAR MENU -->
                            <div class="profile-usermenu">

                            </div>
                            <!-- END MENU -->
                        </div>
                    </div>
                    <!-- END PORTLET MAIN -->
                </div>
                <div class="profile-content">
                    <div class="row">
                        <div class="col-md-8">
                            <div class="portlet light bordered bg-grey-cararra">
                                <div class="portlet-title"><div class="caption"><?php echo _l("Account Info", $this); ?></div> </div>
                                <div class="portlet-body">
                                    <div class="">
                                        <div class="row static-info">
                                            <div class="col-sm-4 name"><?php echo _l("Group", $this); ?></div>
                                            <div class="col-sm-8 value">
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
                                            <div class="col-sm-4 name"><?php echo _l("Created", $this); ?></div>
                                            <div class="col-sm-8 value"><?php echo my_int_fullDate($data["created_date"]); ?></div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-sm-4 name"><?php echo _l("First name", $this); ?></div>
                                            <div class="col-sm-8 value"><?php echo $data["firstname"]; ?></div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-sm-4 name"><?php echo _l("Last name", $this); ?></div>
                                            <div class="col-sm-8 value"><?php echo $data["lastname"]; ?></div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-sm-4 name"><?php echo _l("Email", $this); ?></div>
                                            <div class="col-sm-8 value">
                                                <?php echo $data["email"]; ?>
                                                <?php if($data["status"]==1){ ?>
                                                    <i class="fa fa-check font-green"></i>
                                                <?php }else{ ?>
                                                    <i class="fa fa-times font-red"></i>
                                                <?php } ?>
                                            </div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-sm-4 name"><?php echo _l("Phone", $this); ?></div>
                                            <div class="col-sm-8 value"><?php echo $data["mobile"]!=''?$data["mobile"]:'-'; ?></div>
                                        </div>
                                        <div class="row static-info">
                                            <div class="col-sm-4 name"><?php echo _l("Website", $this); ?></div>
                                            <div class="col-sm-8 value">
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
                        <div class="col-md-4">
                            <?php if(isset($count_boxes) && count($count_boxes)!=0){ ?>
                                <?php foreach($count_boxes as $item){ ?>
                                    <?php app_count_progress_box($item, 'bordered bg-grey-cararra', 'blue'); ?>
                                <?php } ?>
                            <?php } ?>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php echo isset($addon_content)?$addon_content:''; ?>
    </div>
</div>
