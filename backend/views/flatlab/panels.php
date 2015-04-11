<link href="<?=base_url()?>assets/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
<section class="panel">
    <header class="panel-heading">
        <?=$title?>
    </header>
    <div class="panel-body">
        <a href="<?=$base_url?>edit<?=$page?>/0<?=isset($data_type)?"/".$data_type:""?><?=isset($relation_id)?"/".$relation_id:""?>" class="btn btn-success btn-lg"><?=_l("Create a New",$this)?></a>
        <a href="<?=$base_url?><?=isset($data_type)?$data_type:""?>" class="btn btn-default btn-lg"><?=_l("Back",$this)?></a>
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="data_list">
                <thead>
                    <tr>
                        <th><?=_l("panel Name",$this)?></th>
                        <th><?=_l("Language",$this)?></th>
                        <th><?=_l("Public",$this)?></th>
                        <th><?=_l("Status",$this)?></th>
                        <th><?=_l('Action',$this)?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data_list as $data){ ?>
                    <tr class="gradeX">
                        <td><?=$data["panel_name"]?></td>
                        <td><?=$data["language_name"]?></td>
                        <td><i class="fa <?=$data["public"]==1?"fa-check":"fa-minus-circle"?>"></i></td>
                        <td><i class="fa <?=$data["status"]==1?"fa-check":"fa-minus-circle"?>"></i></td>
                        <td>
                            <a href="<?=$base_url?>edit<?=$page?>/<?=$data["panel_id"]?>" class="btn btn-primary btn-sm" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="fa fa-pencil"></i></a>
                            <a href="<?=$base_url?>delete<?=$page?>/<?=$data["panel_id"]?>/<?=isset($data_type)?"/".$data_type:""?><?=isset($relation_id)?"/".$relation_id:""?>" class="btn btn-danger btn-sm" title="<?=_l('Delete',$this)?>"><i title="<?=_l('Delete',$this)?>" class="fa fa-trash-o"></i></a>
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
            "aaSorting": [[ 1, "desc" ]]
        } );
    } );
</script>