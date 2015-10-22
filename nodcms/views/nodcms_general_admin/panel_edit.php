<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?> <?=isset($data['title'])?_l("Edit",$this)." ".$data['title']:_l("Add",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['panel_id'])?"/".$data['panel_id']:""));
                    mk_hselect("data[language_id]",_l('language',$this),$languages,"language_id","language_name",isset($data['language_id'])?$data['language_id']:null,null,'style="width:200px"');
                    mk_htext("data[panel_name]",_l('panel Name',$this),isset($data['panel_name'])?$data['panel_name']:'');
                    mk_hselect("data[panel_type]",_l('panel type',$this),$panel_type,"type_id","type_name",isset($data['panel_type'])?$data['panel_type']:null,null,'style="width:400px"',"change_type($(this));");

                    mk_hselect("data[article_id]",_l('page name',$this),$articles,"article_id","article_name",isset($data['article_id'])?$data['article_id']:null,_l("All pages",$this),'style="width:400px" data-group="1"');
                    mk_hselect("data[article_filter]",_l('article filter',$this),$article_filter,"article_filter","article_filter_name",isset($data['article_filter'])?$data['article_filter']:null,null,'style="width:400px" data-group="1"');
                    mk_hnumber("data[article_limit]",_l('item limit',$this),isset($data['article_limit'])?$data['article_limit']:'','data-group="1"');
                    mk_htextarea("data[panel_text]",_l('panel description',$this),isset($data['panel_text'])?$data['panel_text']:'','data-group="2"');

                    mk_hnumber("data[panel_order]",_l('panel order',$this),isset($data['panel_order'])?$data['panel_order']:'');
                    mk_hcheckbox("data[status]",_l('status',$this),(isset($data['status']) && $data['status']==1)?1:null);
                    mk_hsubmit(_l('Submit',$this),$base_url.$page.(isset($data_type)?"/".$data_type:'').(isset($relation_id)?"/".$relation_id:""),_l('Cancel',$this));
                    mk_hidden("data[relation_id]",isset($relation_id)?$relation_id:0);
                    mk_hidden("data[data_type]",isset($data_type)?$data_type:'');
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<script>
    <?php if(isset($data['panel_type']) && $data['panel_type']==2){ ?>
    $("*[data-group=2]").parent().parent().hide();
    $("*[data-group=1]").parent().parent().show();
    <?php }else{ ?>
    $("*[data-group=1]").parent().parent().hide();
    $("*[data-group=2]").parent().parent().show();
    <?php } ?>
    function change_type(element){
        if(element.val() != 2){
            $("*[data-group=1]").parent().parent().hide();
            $("*[data-group=2]").parent().parent().show();
        }else{
            $("*[data-group=2]").parent().parent().hide();
            $("*[data-group=1]").parent().parent().show();
        }
    }
</script>