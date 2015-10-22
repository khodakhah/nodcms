<?php mk_use_uploadbox($this); ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?> <?=isset($data['title'])?_l("Edit",$this)." ".$data['title']:_l("Add",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['page_id'])?"/".$data['page_id']:""));
                    mk_hselect("data[page_type]",_l('page type',$this),$page_type,"id","name",isset($data['page_type'])?$data['page_type']:null,null,'style="width:400px"',null,$this);
                    mk_htext("data[page_name]",_l('page Name',$this),isset($data['page_name'])?$data['page_name']:'');
                    foreach ($languages as $item) {
                        mk_htext("data[titles][".$item["language_id"]."]",_l('page name',$this)." (".$item["language_name"].")",isset($titles[$item["language_id"]])?$titles[$item["language_id"]]["title_caption"]:"");
                    }
                    mk_hcheckbox("data[public]",_l('public',$this),(isset($data['public']) && $data['public']==1)?1:null);
                    mk_hsubmit(_l('Submit',$this),$base_url.$page,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
<?php
mk_popup_uploadfile(_l('Upload Avatar',$this),"avatar",$base_url."upload_image/20/");
?>