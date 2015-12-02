<div class="row">
    <div class="col-lg-6 col-md-6">
        <section class="panel">
            <div class="panel-heading"><?=_l("Insert New Item",$this)?></div>
            <div class="panel-body">
                <?php
                mk_hpostform($base_url.$page."_manipulate".(isset($data['menu_id'])?"/".$data['menu_id']:""));
                mk_hselect_faicon("data[menu_icon]",_l('Icon',$this),$faicons,isset($data['menu_icon'])?$data['menu_icon']:null,null,'style="width:200px"');
                mk_htext("data[menu_name]",_l('menu Name',$this),isset($data['menu_name'])?$data['menu_name']:'');
                foreach ($languages as $item) {
                    mk_htext("data[titles][".$item["language_id"]."]",_l('menu name',$this)." (".$item["language_name"].")",isset($titles[$item["language_id"]])?$titles[$item["language_id"]]["title_caption"]:"");
                }
                mk_hselect("data[sub_menu]",_l('Parent',$this),$parents,"menu_id","menu_name",isset($data['sub_menu'])?$data['sub_menu']:null,"<--"._l("Main Menu",$this)."-->");
                mk_hselect("data[page_id]",_l('Type',$this),$pages,"page_id","page_name",isset($data['page_id'])?$data['page_id']:null,"<--"._l("use link",$this)."-->");
                mk_hurl("data[menu_url]",_l('URL',$this),isset($data['menu_url'])?$data['menu_url']:'',"style='direction:ltr'");
                mk_hnumber("data[menu_order]",_l('order',$this),isset($data['menu_order'])?$data['menu_order']:'');
                mk_hcheckbox("data[public]",_l('public',$this),(isset($data['public']) && $data['public']==1)?1:null);
                mk_hsubmit(_l('Submit',$this),$base_url."edit".$page,_l('Cancel',$this));
                mk_closeform();
                ?>
            </div>
        </section>
    </div>
    <div class="col-lg-6 col-md-6">
        <section class="panel">
            <header class="panel-heading">
                <?=_l("Items list",$this)?>
            </header>
            <div class="panel-body">
                <div class="dd" id="nestable_list_3">
                    <?php if(isset($data_list) && count($data_list)!=0){ ?>
                    <div class="adv-table">
                        <table  class="display table table-bordered table-striped" id="data_list">
                            <thead>
                            <tr>
                                <th><?=_l("Name",$this)?></th>
                                <th><?=_l("Icon",$this)?></th>
                                <th><?=_l("order",$this)?></th>
                                <th><?=_l("public",$this)?></th>
                                <th><?=_l('Action',$this)?></th>
                            </tr>
                            </thead>
                            <tbody>
                            <?php $i=0; foreach($data_list as $item){ $i++; ?>
                                <tr class="gradeX">
                                    <td><?php echo $i; ?>.</td>
                                    <td><?=$item["menu_name"]?></td>
                                    <td class="text-center"><span class="fa <?=$item["menu_icon"]?> fa-2x"></span> </td>
                                    <td><?=$item["menu_order"]?></td>
                                    <td><i class="fa <?=(isset($item["public"]) && $item["public"]==1)?"fa-check":"fa-minus-circle"?>"></i></td>
                                    <td style="width: 100px">
                                        <a href="<?=$base_url?>edit<?=$page?>/<?=$item["menu_id"]?>" class="btn btn-danger btn-sm" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="fa fa-pencil"></i></a>
                                        <a href="<?=$base_url?>delete<?=$page?>/<?=$item["menu_id"]?>" class="btn btn-primary btn-sm" title="<?=_l('Delete',$this)?>"><i title="<?=_l('Delete',$this)?>" class="fa fa-trash-o"></i></a>
                                    </td>
                                </tr>
                            <?php if(isset($item['sub_menu_data']) && count($item['sub_menu_data'])!=0){ ?>
                                <?php $j=0; foreach($item['sub_menu_data'] as $item2){ $j++; ?>
                                    <tr class="gradeX" style="font-style: italic;">
                                        <td><?php echo $i; ?>-<?php echo $j; ?>.</td>
                                        <td><?=$item2["menu_name"]?></td>
                                        <td class="text-center"><span class="fa <?=$item2["menu_icon"]?> fa-2x"></span> </td>
                                        <td><?=$item2["menu_order"]?></td>
                                        <td><i class="fa <?=(isset($item2["public"]) && $item2["public"]==1)?"fa-check":"fa-minus-circle"?>"></i></td>
                                        <td style="width: 100px">
                                            <a href="<?=$base_url?>edit<?=$page?>/<?=$item2["menu_id"]?>" class="btn btn-danger btn-sm" title="<?=_l('Edit',$this)?>"><i title="<?=_l('Edit',$this)?>" class="fa fa-pencil"></i></a>
                                            <a href="<?=$base_url?>delete<?=$page?>/<?=$item2["menu_id"]?>" class="btn btn-primary btn-sm" title="<?=_l('Delete',$this)?>"><i title="<?=_l('Delete',$this)?>" class="fa fa-trash-o"></i></a>
                                        </td>
                                    </tr>
                                <?php } ?>
                            <?php } ?>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                    <?php } ?>
                </div>
            </div>
        </section>

    </div>
</div>