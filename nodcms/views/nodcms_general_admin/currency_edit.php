<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?> <?=isset($data['title'])?_l("Edit",$this)." ".$data['title']:_l("Add",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['currency_id'])?"/".$data['currency_id']:""));
                    mk_htext("data[title]",_l('currency Name',$this),isset($data['title'])?$data['title']:'');
                    mk_htext("data[code]",_l('currency Code',$this),isset($data['code'])?$data['code']:'');
                    mk_htext("data[symbol_left]",_l('Symbol Left',$this),isset($data['symbol_left'])?$data['symbol_left']:'');
                    mk_htext("data[symbol_right]",_l('Symbol Right',$this),isset($data['symbol_right'])?$data['symbol_right']:'');
                    mk_hnumber("data[value]",_l('Value',$this),isset($data['value'])?$data['value']:'');
                    mk_hcheckbox("data[status]",_l('status',$this),(isset($data['status']) && $data['status']==1)?1:null);
                    mk_hcheckbox("data[default]",_l('Default',$this),(isset($data['default']) && $data['default']==1)?1:null);
                    mk_hsubmit(_l('Submit',$this),$base_url.$page,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
