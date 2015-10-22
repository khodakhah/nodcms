<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/jquery.knob.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/jquery.ui.widget.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/jquery.iframe-transport.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/jquery.fileupload.js"></script>
<script src="<?php echo base_url(); ?>assets/mini-upload-image/js/script.js"></script>

<script src="<?php echo base_url(); ?>assets/mini-upload-form/js/script.js"></script>


<link href="<?=base_url()?>assets/mini-upload-image/css/style.css" rel="stylesheet" >

<div class="row">
    <aside class="profile-nav col-md-3">
        <section class="panel">
            <div class="user-heading round">
                <a href="#">
                    <?php if($banners["avatar"]=="NULL" || $banners["avatar"]=="" ){?>
                    <img src="<?php echo base_url(); ?>upload_file/avatars/avatar-00.png" alt="<?=_l('Account',$this);?>" />
                    <?php }else{ ?>
                    <img src="<?php echo base_url().$banners["avatar"]; ?>"  alt="<?=_l('Account',$this);?>" />
                    <?php } ?>
                </a>
            </div>
            <ul class="nav nav-pills nav-stacked">
                <li class="active"><a class="select" href="<?php echo base_url(); ?>profile">  <i class="fa fa-user"></i> <?=_l('Account',$this);?></a></li>
                <li><a href="<?php echo base_url(); ?>profile-detail"> <i class="fa fa-pencil-square-o"></i> <?=_l('Edit Details',$this);?></a></li>
                <li><a href="<?php echo base_url(); ?>profile-password"> <i class="fa fa-lock"></i> <?=_l('Chang Password',$this);?> </a></li>
                <?php if(isset($_SESSION["user"]["user_type"]) && $_SESSION["user"]["user_type"]==1){ ?>
                <li><a href="<?php echo base_url(); ?>profile-extension"> <i class="fa fa-code"></i> <?=_l('Manage Extensions',$this);?> </a></li>
                <li><a href="<?php echo base_url(); ?>profile/sale"> <i class="fa fa-money"></i> <?=_l('Your Sales',$this);?></a></li>
                <?php }else{ ?>
                <li><a href="<?php echo base_url(); ?>profile/request"> <i class="fa fa-money"></i> <?=_l('Your Request',$this);?></a></li>
                <?php } ?>
            </ul>
        </section>
    </aside>
    <aside class="profile-info col-lg-9 pull-right">
        <section>
            <div class="panel">
                <div class="panel-heading"><span> <?=_l('Submit an  </span> Extensions',$this);?></div>
                <div class="panel-body">
                    <div class="row">
                        <div class="col-md-8">
                            <form method="POST" class="form-horizontal cmxform tasi-form" id="form_input" action="">
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="name"><?=_l('Extension Name',$this)?></label>
                                    <div class="col-lg-10">
                                        <input name="data[name]" value="<?=$extension['name']?>" type="text" class="form-control" id="name" placeholder="<?=_l('Extension Name',$this)?>" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="enname"><?=_l('Extension English Name',$this)?></label>
                                    <div class="col-lg-10">
                                        <input name="data[enname]" value="<?=$extension['name']?>" type="text" class="form-control" id="enname" placeholder="<?=_l('Extension English Name',$this)?>" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="name"><?=_l('Base Category',$this)?></label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="data[category_id]" onchange="loadSubCategory($(this),'sub_cat')">
                                            <?php foreach($extension_base_category as $item){ ?>
                                                <option value="<?=$item['category_id']?>" <?php if(isset($extension['category_id']) && $item['category_id']==$extension['category_id']) echo "selected"; ?>> <?=_l($item['category_name'],$this)?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="name"><?=_l('Category',$this)?></label>
                                    <div class="col-lg-10">
                                        <div id="sub_cat">
                                            <?php if(isset($sub_category) && count($sub_category)!=0){ ?>
                                                <?php foreach($sub_category as $item){ ?>
                                                <label class="checkbox-inline"><input name="data[categories][]" value="<?=$item['category_id']?>" type="checkbox" <?php if(isset($extension['categories']) && is_array($extension['categories']) && in_array($item['category_id'],$extension['categories'])) echo "checked"; ?>> <?=_l($item['category_name'],$this)?></label>
                                                <?php } ?>
                                            <?php } ?>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="name"><?=_l('License',$this)?></label>
                                    <div class="col-lg-10">
                                        <select class="form-control" name="data[category_id]">
                                            <?php foreach($license as $item){ ?>
                                            <option value="<?=$item['license_id']?>" <?php if(isset($extension['license_id']) && $item['license_id']==$extension['license_id']) echo "selected"; ?>> <?=_l($item['license_name'],$this)?></option>
                                            <?php } ?>
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="price"><?=_l('Price',$this)?></label>
                                    <div class="col-lg-10">
                                        <input name="data[price]" value="<?=$extension['price']?>" type="text" class="form-control" id="price" required >
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="patch1"><?=_l('Android Patch',$this)?></label>
                                    <div class="col-lg-10">
                                        <input name="data[patch1]" value="<?=$extension['patch1']?>" type="text" class="form-control" id="patch1">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="patch2"><?=_l('Application Patch',$this)?></label>
                                    <div class="col-lg-10">
                                        <input name="data[patch2]" value="<?=$extension['patch2']?>" type="text" class="form-control" id="patch2">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="file_volume"><?=_l('File Volume',$this)?></label>
                                    <div class="col-lg-10">
                                        <input name="data[file_volume]" value="<?=$extension['file_volume']?>" type="text" class="form-control" id="file_volume">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="app_maker"><?=_l('Application Maker',$this)?></label>
                                    <div class="col-lg-10">
                                        <input name="data[app_maker]" value="<?=$extension['app_maker']?>" type="text" class="form-control" id="app_maker">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="description"><?=_l('Description',$this)?></label>
                                    <div class="col-lg-10">
                                        <textarea name="data[description]" type="text" class="form-control" id="description"><?=$extension["description"]?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="tag"><?=_l('Tags',$this)?></label>
                                    <div class="col-lg-10">
                                        <textarea name="data[tag]" type="text" class="form-control" id="tag"><?=$extension["tag"]?></textarea>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-2 control-label" for="tag"></label>
                                    <div class="col-lg-10">
                                        <label><input value="1" name="data[public]" type="checkbox" class="checkbox-inline" <?=(isset($extension["public"]) && $extension["public"]==1)?"checked":""?>> <?=_l('Public View',$this)?></label>
                                    </div>
                                </div>
                                <input type="hidden" name="data[image]" value="<?=$extension['image']?>" id="image_avatar" />
                                <input type="hidden" name="data[download_file]" value="<?=$extension['download_file']?>" id="download_file" />
                                <div id="list_download" class="row">
                                    <?php $extension_download_count=0; if (isset($extension_image) && count($extension_image)>0) {?>
                                    <?php foreach($extension_image as $download) {?>
                                        <div class="col-md-4">
                                            <div class="alert alert-success">
                                                <button data-dismiss="alert" class="close close-lg col-md-1" type="button"><i class="fa fa-times"></i></button>
                                                <p><input name="extension_image[<?=$extension_download_count;?>][name]" value="<?=$download['name']?>" placeholder="<?=_l("Image Name",$this)?>" type="text" class="form-control"><input name="extension_image[<?=$extension_download_count;?>][image]" value="<?=$download['image']?>" type="hidden" class="form-control"></p>
                                                <p><img src="<?=base_url().image($download['image'],$settings['default_image'],200,400)?>" style="width: 100%"> </p>
                                            </div>
                                        </div>
                                    <?php $extension_download_count++; } }?>
                                </div>
                                <div class="form-group text-center">
                                    <input type="hidden" name="data[current_id]" value="<?=$current_id?>">
                                    <input class="btn btn-success" name="submit" type="submit" value="<?=_l('Submit',$this);?>">
                                </div>
                            </form>
                        </div>
                        <div class="col-md-4">
                            <div class="form-group">
                                <label><?=_l("Avatar",$this)?></label>
                                <div class="image_thumb"><?php echo "<img src='".base_url().image($extension['image'],$settings['default_image'],220,220)."'>"?></div>
                                <form class="upload_form" method="post" action="<?=base_url()?>uploadimage_avatar" enctype="multipart/form-data" for="image_avatar" uploadtype="image">
                                    <div class="drop">
                                        <?=_l("Drop Here",$this)?>
                                        <a><?=_l("Browse",$this)?></a>
                                        <input type="file" name="Filedata" multiple />
                                    </div>

                                    <ul>
                                        <!-- The file uploads will be shown here -->
                                    </ul>
                                </form>
                            </div>
                            <div class="form-group">
                                <label><?=_l("Images",$this)?></label>
                                <div id="uploadblock">
                                    <form id="upload" class="upload_file" method="post" action="<?php echo base_url(); ?>uploadimage_screenshot" enctype="multipart/form-data">
                                        <div class="drop">
                                            <?=_l("Drop Here",$this)?>
                                            <a><?=_l("Browse",$this)?></a>
                                            <input type="file" name="Filedata" multiple />
                                        </div>
                                        <ul>
                                            <!-- The file uploads will be shown here -->
                                        </ul>
                                    </form>
                                </div>
                            </div>
                            <div class="form-group">
                                <label><?=_l("Download",$this)?></label>
                                <form class="upload_form" method="post" action="<?=base_url()?>mkh-uploaded" enctype="multipart/form-data" for="download_file" uploadtype="file">
                                    <div class="drop">
                                        <?=_l("Drop Here",$this)?>
                                        <a><?=_l("Browse",$this)?></a>
                                        <input type="file" name="upl" multiple />
                                    </div>
                                    <ul>
                                        <!-- The file uploads will be shown here -->
                                    </ul>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    </aside>
</div>

    <script src="<?=base_url()?>assets/flatlab/js/jquery.validate.min.js" ></script>
    <script type="text/javascript">
        var Script = function () {
            $().ready(function() {
                $("#form_input").validate();
            });
        }();
        var extension_download_row = <?=$extension_download_count?>;
        function addminiDownload(dlname,source,thum) {
            html = '<div class="col-md-4">';
            html += '<div class="alert alert-success">';
            html += '<button data-dismiss="alert" class="close close-lg col-md-1" type="button"><i class="fa fa-times"></i></button>';
            html += '<p><input placeholder="<?=_l("Image Name",$this)?>" type="text" name="extension_image['+extension_download_row+'][name]" class="form-control"><input type="hidden" name="extension_image['+extension_download_row+'][image]" value="' + source + '" ></p>';
            html += '<p><img src="' + thum + '" style="width: 100%;"> </p>';
            html += '</div>';
            html += '</div>';
            $('#list_download').append(html);
            extension_download_row++;
        }
        function loadSubCategory(thisElenet,elemetID){
            var catID = thisElenet.val();
            $.ajax("<?=base_url()?>subcategories/" + catID,{
                success: function(htmlOut){
                    $("#" + elemetID).html(htmlOut);
                }
            });
        }
    </script>