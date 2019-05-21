<div class="portlet">
    <div class="portlet-title">
        <div class="actions">
            <a href="<?php echo ADMIN_URL; ?>socialLinksForm" class="btn btn-primary">
                <i class="fa fa-plus"></i> <?php echo _l("Add New",$this); ?>
            </a>
        </div>
    </div>
    <div class="portlet-body">
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
            <table class="table table-striped table-bordered table-advance table-hover" id="data_list">
                <thead>
                <tr>
                    <th></th>
                    <th><?php echo _l("Preview",$this)?></th>
                    <th></th>
                </tr>
                </thead>
                <tbody>
                <?php $i=0; foreach($data_list as $item){ $i++; ?>
                    <tr>
                        <td style="width: 20px;"><?=$i?>.</td>
                        <td>
                            <a class="social-icon social-icon-color <?php echo $item["class"]; ?>" href="<?php echo $item["url"]; ?>" target="_blank">&nbsp;</a>
                            <?php echo $item["url"]; ?>
                        </td>
                        <td style="width: 100px">
                            <a href="<?php echo ADMIN_URL; ?>socialLinksForm/<?=$item["id"]?>" class="btn btn-primary btn-sm" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="fa fa-pencil"></i></a>
                            <a href="<?php echo ADMIN_URL; ?>socialLinksDelete/<?=$item["id"]?>" class="btn btn-danger btn-sm" title="<?=_l('Delete',$this)?>"><i title="<?=_l('Delete',$this)?>" class="fa fa-trash-o"></i></a>
                        </td>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        <?php }else{ ?>
            <div class="note note-info"><i class="fa fa-exclamation"></i> <?php echo _l("Empty",$this); ?></div>
        <?php } ?>
    </div>
</div>