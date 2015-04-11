<div class="panel">
    <h1 class="panel-heading"><?=$title?></h1>
    <div class="panel-body">
        <?php if(isset($data_list) && count($data_list)!=0){ ?>
        <div class="row">
            <?php foreach($data_list as $data){ ?>
            <div class="col-lg-3 col-md-3 col-sm-4 col-xs-6" imageid="<?=$data['image_id']?>" >
                <p><img class="img-rounded" src="<?=base_url().image($data['image'],$settings['default_image'],300,200)?>" style="width: 100%" alt="Image"></p>
<!--                <p style="height: 200px;overflow: hidden;"><img class="img-rounded" src="--><?//=base_url().$data['image']?><!--" style="width: 100%" alt="Image"></p>-->
                <p class="text-center"><?=$data["name"]?></p>
                <p class="text-center"><?=$data["width"]?> <?=_l("px",$this)?> X <?=$data["height"]?> <?=_l("px",$this)?></p>
                <p class="text-center"><?=$data["size"]?> <?=_l("KG",$this)?></p>
                <p class="text-center">
                    <a href="javascript:;" onclick="removeImage($(this).parent().parent())" class="btn btn-danger btn-shadow"><i class="fa fa-trash-o"></i> <?=_l("Delete",$this)?></a>
                </p>
            </div>
            <?php } ?>
        </div>
        <?php } ?>
    </div>
</div>
<script>
    function removeImage(elemetn){
        var r = confirm("<?=_l('Are you sure to remove this image?',$this)?>");
        if (r == true) {
            $.ajax({
                url: "<?=$base_url?>deleteuploaded_image/" + elemetn.attr("imageid"),
                context: document.body
            }).done(function(data) {
                        eval("var resultFile = " + data);
                        if(resultFile.status == "success"){
                            elemetn.remove();
                        }
                    });
        }
    }
</script>