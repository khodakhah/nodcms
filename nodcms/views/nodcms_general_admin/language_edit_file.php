<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?>
                <?php if(isset($data['language_name'])){ ?>
                    <?php echo _l("Edit File",$this); ?>:
                    <b>
                        nodcms/language/
                        <?php echo $data['language_name']; ?>
                        <?php if(isset($languages) && count($languages)!=0){ ?>
                        <div class="dropdown btn-group">
                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="chooseLanguage" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="chooseLanguage">
                                <?php foreach($languages as $item){ ?>
                                    <?php if($item['language_id']!=$data['language_id']){ ?>
                                        <li><a href="<?php echo $base_url."edit_lang_file/".$item['language_id']."/".($file_name!=$data['code']?$file_name:$item['code']); ?>"><?php echo $item['language_name']; ?></a></li>
                                    <?php } ?>
                                <?php } ?>
                            </ul>
                        </div>
                        <?php }else{ ?>
                            <?php echo $data['language_name']; ?>
                        <?php } ?>
                        <?php echo (isset($file_name)?'/'.$file_name:''); ?>_lang.php
                        <div class="dropdown btn-group">
                            <button class="btn btn-default btn-sm dropdown-toggle" type="button" id="chooseLanguageFile" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
                                <i class="fa fa-caret-down"></i>
                            </button>
                            <ul class="dropdown-menu" aria-labelledby="chooseLanguageFile">
                                <?php if($file_name!=$data['code']){ ?>
                                    <li><a href="<?php echo $base_url."edit_lang_file/".$data['language_id']."/".$data['code']; ?>"><?php echo $data['code']; ?>_lang.php (<?php echo _l('Frontend',$this); ?>)</a></li>
                                <?php } ?>
                                <?php if($file_name!='backend'){ ?>
                                    <li><a href="<?php echo $base_url."edit_lang_file/".$data['language_id']."/backend"; ?>">backend_lang.php (<?php echo _l('Admin Side',$this); ?>)</a></li>
                                <?php } ?>
                            </ul>
                        </div>
                    </b>
                <?php }else{ ?>
                    <?php echo _l("Add", $this); ?>
                <?php } ?>
            </header>
            <div class="panel-body">
                <div class=" form">
                    <?php
                    mk_hpostform($base_url."edit_lang_file".(isset($data['language_id'])?"/".$data['language_id']:"").(isset($file_name)?"/".$file_name:""));
                    if(isset($lang_list) && count($lang_list)!='') {
                        $i=0;
                        ?>
                        <table class="table">
                            <tr>
                                <th><?php echo _l('Language Key',$this); ?></th>
                                <th><?php echo _l('Show in Website',$this); ?></th>
                            </tr>
                            <?php foreach($lang_list as $key=>$value){ $i++; ?>
                                <tr>
                                    <td style="width: 50%;">
                                        <label style="display: block;" for="data<?php echo $i;?>" class="control-label"><?php echo $key; ?></label>
                                    </td>
                                    <td>
                                        <input style="direction: <?php echo (isset($data['rtl']) && $data['rtl']==1)?'rtl':'ltr'; ?>;" class="form-control" id="data<?php echo $i;?>" name="data[]" type="text" value="<?php echo $value?>"/>
                                    </td>
                                </tr>
                                <?php } ?>
                        </table>
                        <?php
                    }
                    mk_hsubmit(_l('Submit',$this),$base_url.'language',_l('Cancel',$this));
                    mk_closeform();
                    ?>
                </div>
            </div>
        </section>
    </div>
</div>