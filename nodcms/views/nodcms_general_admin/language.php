<link href="<?=base_url()?>assets/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
<section class="panel">
    <header class="panel-heading">
        <?=_l('Languages',$this)?>
    </header>
    <div class="panel-body">
        <a href="<?=$base_url?>edit<?=$page?>" class="btn btn-success btn-lg"><?=_l("Create a New",$this)?></a>
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="data_list">
                <thead>
                    <tr>
                        <th>#</th>
                        <th><?=_l("Language Name",$this)?></th>
                        <th><?=_l("Language Code",$this)?></th>
                        <th><?=_l("Public",$this)?></th>
                        <th><?=_l("Order",$this)?></th>
                        <th><?=_l('Action',$this)?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php $i=0; foreach($data_list as $data){ $i++; ?>
                    <tr class="gradeX">
                        <td><?php echo $i; ?>.</td>
                        <td>
                            <?php if($data["image"]!=''){ ?>
                                <img style="width: 24px;" src="<?php echo base_url().$data["image"]; ?>">
                            <?php } ?>
                            <?=$data["language_name"]?> <?=$data["default"]==1?"("._l('Default',$this).")":""?>
                        </td>
                        <td><?=$data["code"]?></td>
                        <td><i class="fa <?=$data["public"]==1?"fa-check":"fa-minus-circle"?>"</td>
                        <td><?=$data["sort_order"]?></td>
                        <td>
                            <div class="dropdown btn-group">
                                <button class="btn btn-primary btn-sm dropdown-toggle" type="button" id="action" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                    <i class="fa fa-cog"></i>
                                    <i class="fa fa-caret-down"></i>
                                </button>
                                <ul class="dropdown-menu" aria-labelledby="action">
                                    <li><a href="<?=$base_url?>edit<?=$page?>/<?=$data["language_id"]?>"><i class="fa fa-pencil"></i> <?=_l('Edit',$this)?></a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?=$base_url?>edit_lang_file/<?=$data["language_id"]?>/<?=$data["code"]?>"><i class="fa fa-language"></i> <?=_l('Edit Language',$this)?> (<?=$data["code"]?>_lang.php)</a></li>
                                    <li><a href="<?=$base_url?>edit_lang_file/<?=$data["language_id"]?>/backend"><i class="fa fa-language"></i> <?=_l('Edit Language',$this)?> (backend_lang.php)</a></li>
                                    <li role="separator" class="divider"></li>
                                    <li><a href="<?=$base_url?>delete<?=$page?>/<?=$data["language_id"]?>"><i class="fa fa-trash-o"></i> <?=_l('Delete',$this)?></a></li>
                                </ul>
                            </div>
                        </td>
                    </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
        <?php } ?>
    </div>
</section>

<script type="text/javascript" src="<?=base_url()?>assets/flatlab/assets/data-tables/jquery.dataTables.js"></script>
<script type="text/javascript" src="<?=base_url()?>assets/flatlab/assets/data-tables/DT_bootstrap.js"></script>

<script type="text/javascript" charset="utf-8">
    $(document).ready(function() {
        $('#data_list').dataTable( {
            "aaSorting": [],
            "oLanguage": {
                "sSearch": "<?=_l("Search",$this)?>:",
                "oPaginate":{
                    "sNext": "<?=_l("Next",$this)?>",
                    "sPrevious": "<?=_l("Previous",$this)?>"
                },
                "sZeroRecords": "<?=_l("No matching records found",$this)?>",
                "sLengthMenu": "<?=_l("_MENU_ Records per page",$this)?>",
                "sInfoEmpty": "<?=_l("Showing 0 to 0 of 0 entries",$this)?>",
                "sInfo": "<?=_l("Showing _START_ to _END_ of _TOTAL_ entries",$this)?>"
            }
        } );
    } );
</script>