<div class="" id="files-list">
    <div class="card bg-dark font-white" id="upload-box">
        <div class="card-body">
            <h5 class="card-title"><?php echo _l("Add Images", $this); ?></h5>
            <form class="upload_form" method="post" action="<?php echo $upload_url; ?>" enctype="multipart/form-data">
                <div class="drop">
                    <?php echo _l("You can drop your files here to upload.", $this); ?>
                    <a><?php echo _l("Brows your device", $this); ?></a>
                    <input type="file" name="file" multiple />
                </div>
            </form>
        </div>
    </div>

    <?php if(isset($data_list) && count($data_list)!=0){ ?>
        <?php $i=0; foreach($data_list as $data){ $i++; ?>
            <div class="card margin-top-10 mb-3" id="row<?php echo $data['image_id']; ?>" >
                <div class="row no-gutters">
                    <div class="col-4"><img class="card-img" src="<?php echo base_url($data["image_url"]); ?>"></div>
                    <div class="col-8">
                        <div class="card-body">
                            <div class="mb-3 text-right"><button class="btn btn-danger btn-sm" onclick="$.loadConfirmModal('<?php echo GALLERY_ADMIN_URL."imageDelete/{$data['image_id']}"; ?>')"><?php echo _l("Delete", $this); ?></button></div>
                            <div data-role="auto-load" data-url="<?php echo GALLERY_ADMIN_URL."imageSubmit/$data[image_id]"; ?>"></div>
                        </div>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<?php $this->addJsFile("assets/mini-upload-image/js/jquery.knob"); ?>
<?php $this->addJsFile("assets/mini-upload-image/js/jquery.ui.widget"); ?>
<?php $this->addJsFile("assets/mini-upload-image/js/jquery.iframe-transport"); ?>
<?php $this->addJsFile("assets/mini-upload-image/js/jquery.fileupload"); ?>
<?php $this->addCssFile("assets/mini-upload-image/css/style"); ?>
<script>
    $(function () {
        $('.upload_form .drop a').click(function(){
            // Simulate a click on the file input button
            // to show the file browser dialog
            $(this).parent().find('input').click();
        });

        // Initialize the jQuery File Upload plugin
        $('.upload_form').fileupload({
            // This element will accept file drag/drop uploading
            dropZone: $(this).find('.drop'),
            // This function is called when a file is added to the queue;
            // either via the browse button, or via drag/drop:
            add: function (e, data) {
                $(this).addClass("notok");
                var ul = $("#files-list");
                var tpl = $('<div class="card margin-top-10 mb-3"><div class="card-body working">' +
                    '<i class="fas fa-spinner fa-pulse"></i>' +
                    '</div></div>');
                // Add the HTML to the UL element
                data.context = tpl;
                $('#upload-box').after(tpl);

                // Automatically upload the file once it is added to the queue
                var jqXHR = data.submit();
            },

            progress: function(e, data){

                // Calculate the completion percentage of the upload
                var progress = parseInt(data.loaded / data.total * 100, 10);

                // Update the hidden input field and trigger a change
                // so that the jQuery knob plugin knows to update the dial
                data.context.find('span').text('%' + progress);

                if(progress == 100){
                    data.context.removeClass('working');
                }
            },
            done: function(e, data){
                console.log(data.result);
                var resultFile = JSON.parse(data.result);
                if(resultFile.status == "success"){
                    var newTpl = '<div class="card margin-top-10 mb-3" id="row'+resultFile.image_id+'" >' +
                        '<div class="row no-gutters">' +
                        '<div class="col-4">'+
                        '<img class="card-img" src="'+resultFile.file_url+'">' +
                        '</div>' +
                        '<div class="col-8">' +
                        '<div class="card-body">' +
                        '<div data-role="auto-load" data-url="' + resultFile.submit_url + '"></div>' +
                        '</div>' +
                        '</div>' +
                        '</div>' +
                        '</div>';
                    newTpl = $(newTpl);
                    data.context.remove();
                    $('#upload-box').after(newTpl);

                    newTpl.find("div[data-role=\"auto-load\"]").each(function () {
                        $(this).loadIn($(this).data('url'))
                    });

                    $(this).removeClass("notok");
                }else{
                    $(this).addClass("notok");
                    alert(resultFile.errors);
                }
            },
            fail:function(e, data){
                // Something has gone wrong!
                alert("fail");
                data.context.addClass('error');
                $(this).addClass("notok");
            }

        });

        // Prevent the default action when a file is dropped on the window
        $(document).on('drop dragover', function (e) {
            e.preventDefault();
        });
    });
</script>