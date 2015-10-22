<link href="<?=base_url()?>assets/flatlab/assets/advanced-datatable/media/css/demo_table.css" rel="stylesheet" />
<section class="panel">
    <header class="panel-heading">
        <?=$title?>
    </header>
    <div class="panel-body">
        <a href="<?=$base_url?>edit<?=$page?>" class="btn btn-success btn-lg"><?=_l("Create a New",$this)?></a>
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
        <div class="adv-table">
            <table  class="display table table-bordered table-striped" id="data_list">
                <thead>
                    <tr>
                        <th><?=_l("city name",$this)?></th>
                        <th><?=_l("country",$this)?></th>
                        <th><?=_l("public",$this)?></th>
                        <th><?=_l('Action',$this)?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data_list as $data){ ?>
                    <tr class="gradeX">
                        <td><?=$data["city_name"]?></td>
                        <td><?=$data["country_name"]?></td>
                        <td><i class="fa <?=$data["public"]==1?"fa-check":"fa-minus-circle"?>"></i></td>
                        <td>
                            <a href="<?=$base_url?>extensions/<?=$page?>/<?=$data["city_id"]?>" class="btn btn-warning btn-sm" title="<?=_l('Extension Edit',$this)?>"><i title="<?=_l('Extension Edit',$this)?>" class="fa fa-edit"></i></a>
                            <a href="<?=$base_url?>gallery_upload/<?=$page?>/<?=$data["city_id"]?>" class="btn btn-success btn-sm" title="<?=_l('Photo Edit',$this)?>"><i title="<?=_l('Photo Edit',$this)?>" class="fa fa-picture-o"></i></a>
                            <a href="<?=$base_url?>edit<?=$page?>/<?=$data["city_id"]?>" class="btn btn-primary btn-sm" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="fa fa-pencil"></i></a>
                            <a href="<?=$base_url?>delete<?=$page?>/<?=$data["city_id"]?>" class="btn btn-danger btn-sm" title="<?=_l('Delete',$this)?>"><i title="<?=_l('Delete',$this)?>" class="fa fa-trash-o"></i></a>
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