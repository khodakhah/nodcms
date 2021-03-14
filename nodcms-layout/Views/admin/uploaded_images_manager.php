<div class="card-columns" id="files-list">
    <div class="card bg-dark font-white" id="upload-box">
        <div class="card-body">
            <h5 class="card-title"><?php echo _l("Upload Images", $this); ?></h5>
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
            <div class="card" id="row<?=$data['image_id']?>" >
                <img class="card-img-top" src="<?php echo base_url($data["image"]); ?>">
                <div class="card-body">
                    <h5 class="card-title"><?php echo $data['name']; ?></h5>
                    <div class="row no-gutters">
                        <div class="col"><?php echo "$data[width]px X $data[height]px"; ?></div>
                        <div class="col text-right"><?php echo "$data[size]KB"; ?></div>
                    </div>
                    <div class="margin-top-10 card-text">
                        <?php echo _l("URL", $this); ?>:
                    </div>
                    <div class="margin-bottom-10 card-text">
                        <input class="border-0 w-100 input-sm" value="<?php echo base_url($data["image"]); ?>" onClick="this.select();">
                    </div>
                    <div class="margin-bottom-10 card-text">
                        <a class="fancybox btn default btn-sm" rel="group" href="<?php echo base_url($data['image']); ?>" title="<?php echo $data['name']; ?>">
                            <?php echo _l('Preview', $this)?></a>
                        <a href="javascript:;" onclick="$.loadConfirmModal('<?php echo ADMIN_URL."imageDelete/$data[image_id]"; ?>',removeImage)" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> <?=_l("Delete",$this)?></a>
                    </div>
                </div>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<?php $this->addCssFile("assets/plugins/fancybox/source/jquery.fancybox"); ?>
<?php $this->addJsFile("assets/plugins/fancybox/source/jquery.fancybox"); ?>
<script>
    function removeImage(result, myModal){
        $('#row'+result.data.removed).remove();
        myModal.modal('hide');
    }

    $(function(){
        //    fancybox
        jQuery(".fancybox").fancybox();
    });
</script>

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
                var tpl = $('<div class="card"><div class="card-body working">' +
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
                var resultFile = JSON.parse(data.result);
                if(resultFile.status == "success"){
                    var newTpl = '<div class="card" id="row'+resultFile.image_id+'" >'+
                                '<img class="card-img-top" src="'+resultFile.file_url+'">' +
                                '<div class="card-body">' +
                                    '<h5 class="card-title">'+resultFile.name+'</h5>' +
                                    '<div class="row no-gutters">' +
                                        '<div class="col">'+resultFile.width+'px X '+resultFile.height+'px</div>' +
                                        '<div class="col text-right">'+resultFile.size+'KB</div>' +
                                    '</div>' +
                                    '<div class="margin-top-10 card-text">' +
                                        '<?php echo _l("URL", $this); ?>:' +
                                    '</div>' +
                                    '<div class="margin-bottom-10 card-text">' +
                                        '<input class="border-0 w-100 input-sm" value="'+resultFile.file_url+'" onClick="this.select();">' +
                                    '</div>' +
                                    '<div class="margin-bottom-10 card-text">' +
                                '<a class="fancybox btn default btn-sm" rel="group" href="'+resultFile.file_url+'" title="'+resultFile.name+'">' +
                                    '<?php echo _l('Preview', $this)?></a>\n' +
                                '<a href="javascript:;" onclick="$.loadConfirmModal(\'<?php echo ADMIN_URL."imageDelete/"; ?>'+resultFile.image_id+'\',removeImage)" class="btn btn-danger btn-sm"><i class="fa fa-trash-o"></i> <?php echo _l("Delete",$this); ?></a>' +
                            '</div>' +
                        '</div>' +
                    '</div>';
                    newTpl = $(newTpl);
                    data.context.remove();
                    $('#upload-box').after(newTpl);

                    $(this).removeClass("notok");
                }else{
                    $(this).addClass("notok");
                    $.showInModal(translate("Error"), resultFile.error);
                    data.context.remove();
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

        // Helper function that formats the file sizes
        function formatFileSize(bytes) {
            if (typeof bytes !== 'number') {
                return '';
            }

            if (bytes >= 1000000000) {
                return (bytes / 1000000000).toFixed(2) + ' GB';
            }

            if (bytes >= 1000000) {
                return (bytes / 1000000).toFixed(2) + ' MB';
            }

            return (bytes / 1000).toFixed(2) + ' KB';
        }
    });
</script>