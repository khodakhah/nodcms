<div class="card">
    <div class="card-header">
        <div class="text-right">
            <a href="<?php echo ADMIN_URL; ?>userEdit" class="btn blue"><?=_l("Add New",$this)?> <i class="fa fa-plus"></i></a>
        </div>
    </div>
    <div class="card-body">
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
            <table  class="table table-light" id="data_list">
                <thead>
                <tr>
                    <th><?=_l("ID",$this)?></th>
                    <th colspan="2"><?=_l("Username",$this)?></th>
                    <th><?=_l("Email",$this)?></th>
                    <th><?=_l("Full Name",$this)?></th>
                    <th><?=_l("Created",$this)?></th>
                    <th><?=_l("Group",$this)?></th>
                    <th><?=_l("Validated Email",$this)?></th>
                    <th><?=_l('Action',$this)?></th>
                </tr>
                </thead>
                <tbody>
                <?php $i=0; foreach($data_list as $data){ $i++; ?>
                    <tr id="row_<?php echo $data["user_id"]?>">
                        <td><?php echo $data['user_id']; ?></td>
                        <td class="fit">
                            <img style="height: 36px;" class="user-pic" src="<?php echo base_url($data['avatar']!=''?$data['avatar']:"upload_file/images/user.png"); ?>">
                        </td>
                        <td><a href="javascript:;" class="view-details" data-id="<?php echo $data["user_id"]; ?>"><?php echo $data["username"]; ?></a></td>
                        <td><?php echo $data["email"]; ?></td>
                        <td><?php echo $data["fullname"]; ?></td>
                        <td><?php echo timespan($data["created_date"], '', 2); ?></td>
                        <td>
                            <?php if($data["group_id"]==1) { ?>
                                <span class="label label-success"><?php echo $data['group_name']; ?></span>
                            <?php }elseif(in_array($data["group_id"],array(2,20))){ ?>
                                <span class="label label-primary"><?php echo $data['group_name']; ?></span>
                            <?php }elseif($data["group_id"]==100){ ?>
                                <span class="label label-warning"><?php echo $data['group_name']; ?></span>
                            <?php }else{ ?>
                                <span class="label label-danger"><?php echo $data['group_name']; ?></span>
                            <?php } ?>
                        </td>
                        <td><i class="fa <?=$data["status"]==1?"fa-check font-green":"fa-times font-red"?>"></i></td>
                        <td>
                            <a href="<?php echo ADMIN_URL; ?>userEdit/<?php echo $data["user_id"]?>" class="btn blue btn-sm" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="far fa-edit"></i></a>
                            <a id="ban_<?php echo $data["user_id"]?>" href="javascript:$(this).userBan(<?php echo $data["user_id"]?>);" class="btn <?php echo $data["active"]!=1?'red':'yellow-gold'; ?> btn-sm btn-ban">
                                <i title="<?=_l('Ban',$this)?>" class="fa fa-ban"></i>
                            </a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
            <?php echo isset($pagination)?$pagination:""; ?>
        <?php } ?>
    </div>
</div>
<script>
    $(function () {
        $.fn.userBan = function (user_id) {
            var my_url = "<?php echo ADMIN_URL; ?>userDeactive/" + user_id;
            var my_button = $("#ban_"+user_id);
            my_button.addClass("disabled");
            $.ajax({
                url: my_url,
                beforeSend:function(){
                    $('<i class="fa fa-spinner fa-pulse fa-fw"></i>').prependTo(my_button);
                },
                complete:function () {
                    my_button.removeClass("disabled")
                        .find("i.fa-spinner").remove();

                },
                dataType: "json",
                success:function (data) {
                    if(data.status == "success") {
                        my_button.toggleClass("red yellow-gold");
                        toastr.success(data.msg, "<?php echo _l("Success", $this); ?>");
                    }else
                        toastr.error(data.error, "<?php echo _l("Error", $this); ?>");
                },
                fail: function () {
                    toastr.error("Fail ajax!", "<?php echo _l("Error", $this); ?>");
                }
            });
        };
    });
</script>
<script type="text/javascript" charset="utf-8">
    $(function() {
        $('.view-details').click(function () {
            $.loadInModal('<?php echo ADMIN_URL."userProfile/"; ?>'+$(this).data('id'));
        });
    });
</script>
