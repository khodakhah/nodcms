<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?> - <?=isset($data['username'])?_l("Edit",$this):_l("Add",$this)?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url.$page."_manipulate".(isset($data['comment_id'])?"/".$data['comment_id']:""));
                    mk_htext("username",_l('Username',$this),isset($data['username'])?$data['username']:'',"readonly");
                    mk_htextarea("data[content]",_l('content',$this),isset($data['content'])?$data['content']:'');
                    mk_hcheckbox("data[status]",_l('status',$this),(isset($data['status']) && $data['status']==1)?1:null);
                    if($data['sub_id']==0){
                        mk_htextarea("replay[content]",_l('replay',$this),isset($reply_data['content'])?$reply_data['content']:'');
                        mk_hcheckbox("replay[status]",_l('replay status',$this),(isset($reply_data['status']) && $reply_data['status']==1)?1:null);
                    }
                    if(isset($reply_data['comment_id'])) mk_hidden("replay[id]",$reply_data['comment_id']);
                    mk_hidden("replay[extension_id]",$data['extension_id']);
                    mk_hsubmit(_l('Submit',$this),$base_url.$page,_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>
