<link href="<?=base_url()?>assets/flatlab/assets/dropzone/css/dropzone.css" rel="stylesheet"/>
<?php if(isset($data_list) && count($data_list)!=0){ ?>
<style>
    #my-awesome-dropzone div.default.message{display: none;}
</style>
<?php } ?>
<div class="row">
    <div class="col-lg-12 col-md-12 col-xs-12">
        <section class="panel">
            <header class="panel-heading">
                <?=$title?>
            </header>
            <div class="panel-body">
                <form action="<?=$base_url?>upload_image/10/<?=$data_type?>/<?=$relation_id?>/<?=$gallery_id?>" class="dropzone" id="my-awesome-dropzone">
                    <?php if(isset($data_list) && count($data_list)!=0){ ?>
                    <?php foreach($data_list as $data){ ?>
                        <div class="preview processing image-preview success" galleryid="<?=$data['image_id']?>" onclick="removeImage($(this));">
                            <div class="details">
                                <div class="filename"><span>Click to Remove</span></div>
                                <div class="size"><strong><?=$data['size']?></strong> KB</div>
                                <img src="<?=base_url()?><?=$data['image']?>" alt="costume-facebook-like.jpg">
                            </div>
                            <div class="success-mark"><span>✔</span></div>
                        </div>
                    <?php } ?>
                    <?php } ?>
                </form>
                <div class="text-center" style="margin-top: 10px">
                    <a class="btn btn-primary btn-lg" href="<?=$base_url?>gallery/<?=$data_type?>/<?=$relation_id?>"><?=_l("Back",$this)?></a>
                </div>
            </div>
        </section>
    </div>
</div>
<script src="<?=base_url()?>assets/flatlab/assets/dropzone/dropzone.js"></script>
    <script>
        Dropzone.options.myAwesomeDropzone = {
            dictDefaultMessage: "custom message",
            maxFilesize: <?=$this->config->item('max_upload_size')/1024?>, // MB
            success: function(file,data) {
                eval("var resultFile = " + data);
                if(resultFile.status == "success"){
                    file.previewTemplate.addClass("success");
                    file.previewTemplate.attr("galleryid",resultFile.getid);
                    file.previewTemplate.attr("onclick",'removeImage($(this));');
                }else{
                    file.previewTemplate.addClass("error");
                    file.previewTemplate.attr("onclick",'$(this).remove();');
                    alert(resultFile.errors);
                }
                file.previewTemplate.find(".filename span").text("Click to Remove");
            }

        };

        function removeImage(elemetn){
            var r = confirm("<?=_l('Are you sure to remove this image?',$this)?>");
            if (r == true) {
                $.ajax({
                    url: "<?=$base_url?>deletegallery_image/" + elemetn.attr("galleryid"),
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