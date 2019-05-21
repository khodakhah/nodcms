<section class="portlet">
    <div class="portlet-body">
        <div class="form">
            <?php
            mk_hpostform($base_url.$page."_manipulate".(isset($data['user_id'])?"/".$data['user_id']:""));
            mk_hselect("data[language_id]",_l('language',$this),$languages,"language_id","language_name",isset($data['language_id'])?$data['language_id']:null,null,'style="width:200px"');
            mk_hselect("data[group_id]",_l('Group',$this),$groups,'group_id','group_name',isset($data['group_id'])?$data['group_id']:'',null,'style="width:250px;"');
            mk_htext("data[username]",_l('Username',$this),isset($data['username'])?$data['username']:'');
            mk_hemail("data[email]",_l('Email',$this),isset($data['email'])?$data['email']:'');
            mk_htext("data[firstname]",_l('First Name',$this),isset($data['firstname'])?$data['firstname']:'');
            mk_htext("data[lastname]",_l('Last Name',$this),isset($data['lastname'])?$data['lastname']:'');
            mk_htext("data[mobile]",_l('Phone Number',$this),isset($data['mobile'])?$data['mobile']:'');
            mk_hpassword("data[password]",_l('Password',$this));
            mk_hcheckbox("data[status]",_l('Validated',$this),(isset($data['status']) && $data['status']==1)?1:null);
            mk_hcheckbox("data[active]",_l('Active',$this),(isset($data['active']) && $data['active']==1)?1:null);
            mk_hsubmit(_l('Submit',$this),$base_url.$page,_l('Cancel',$this));
            mk_closeform();
            ?>
        </div>
    </div>
</section>
