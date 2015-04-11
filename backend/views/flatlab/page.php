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
                        <th><?=_l("page name",$this)?></th>
                        <th><?=_l("page type",$this)?></th>
                        <th><?=_l("order",$this)?></th>
                        <th><?=_l("preview",$this)?></th>
                        <th><?=_l("public",$this)?></th>
                        <th><?=_l('Action',$this)?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($data_list as $data){ ?>
                    <tr class="gradeX">
                        <td><?=$data["page_name"]?></td>
                        <td><?=_l($page_type[$data["page_type"]]["name"],$this)?></td>
                        <td><?=$data["page_order"]?></td>
                        <td><i class="fa <?=$data["preview"]==1?"fa-check":"fa-minus-circle"?>"></i></td>
                        <td><i class="fa <?=$data["public"]==1?"fa-check":"fa-minus-circle"?>"></i></td>
                        <td>
                            <?php if(allowed_page_fields($data["page_type"],"extension",$page_type)){ ?>
                            <a href="<?=$base_url?>extensions/<?=$page?>/<?=$data["page_id"]?>" class="btn btn-warning btn-sm" title="<?=_l('Extension Edit',$this)?>"><i title="<?=_l('Extension Edit',$this)?>" class="fa fa-edit"></i></a>
                            <?php } ?>
                            <?php if(allowed_page_fields($data["page_type"],"gallery",$page_type)){ ?>
                            <a href="<?=$base_url?>gallery/<?=$page?>/<?=$data["page_id"]?>" class="btn btn-info btn-sm" title="<?=_l('Photo Edit',$this)?>"><i title="<?=_l('Photo Edit',$this)?>" class="fa fa-picture-o"></i></a>
                            <?php } ?>
                            <a href="<?=$base_url?>edit<?=$page?>_options/<?=$data["page_id"]?>" class="btn btn-success btn-sm" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="fa fa-gear"></i> <?=_l('Page Options',$this)?></a>
                            <a href="<?=$base_url?>edit<?=$page?>/<?=$data["page_id"]?>" class="btn btn-primary btn-sm" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="fa fa-pencil"></i></a>
                            <a href="<?=$base_url?>delete<?=$page?>/<?=$data["page_id"]?>" class="btn btn-danger btn-sm btn-delete" title="<?=_l('Delete',$this)?>"><i title="<?=_l('Delete',$this)?>" class="fa fa-trash-o"></i></a>
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
            "aaSorting": [[ 2, "asc" ]],
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
