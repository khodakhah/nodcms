<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?> <?=isset($data['title'])?_l("Edit",$this)." ".$data['title']:_l("Add",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['country_id'])?"/".$data['country_id']:""));
                    mk_htext("data[country_name]",_l('country Name',$this),isset($data['country_name'])?$data['country_name']:'');
                    foreach ($languages as $item) {
                        mk_htext("data[titles][".$item["language_id"]."]",_l('country name',$this)." (".$item["language_name"].")",isset($titles[$item["language_id"]])?$titles[$item["language_id"]]["title_caption"]:"");
                    }

                    mk_hselect("data[language_id]",_l('language',$this),$languages,"language_id","language_name",isset($data['language_id'])?$data['language_id']:null);
                    mk_hselect("data[currency_id]",_l('currency',$this),$currency,"currency_id","title",isset($data['currency_id'])?$data['currency_id']:null);
                    mk_hcheckbox("data[public]",_l('public',$this),(isset($data['public']) && $data['public']==1)?1:null);
                    mk_hsubmit(_l('Submit',$this),$base_url.$page,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
