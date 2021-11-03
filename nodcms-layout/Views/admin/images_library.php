<div class="row" id="files-list">
    <div class="col-md-3 col-sm-4 col-xs-6" id="upload-box">
        <form class="upload_form margin-top-20" method="post" action="<?php echo $upload_url; ?>" enctype="multipart/form-data">
            <div class="drop">
                <p><i class="fa fa-upload"></i> <?php echo _l("Upload", $this); ?></p>
                <p><small><?php echo _l("Drop your file here or", $this); ?></small></p>
                <a><?php echo _l("Browse", $this); ?></a>
                <input type="file" name="file" multiple />
            </div>
        </form>
    </div>
    <?php if(isset($images) && count($images)!=0){ ?>
        <?php foreach($images as $data){ ?>
            <div class="col-md-3 col-sm-4 col-xs-6">
                <p style="height:100px;overflow: hidden;"><img src="<?php echo base_url(image($data['image'],$settings['default_image'],300,200)); ?>" style="width: 100%" alt="Image"></p>
                <p class="text-center"><?=$data["width"]?> <?=_l("px",$this)?> X <?=$data["height"]?> <?=_l("px",$this)?></p>
                <p class="text-center"><?=$data["size"]?> <?=_l("MG",$this)?></p>
                <p class="text-center">
                    <button type="button" class="btn blue" onclick="$('#<?php echo $input_id; ?>').attr('value','<?php echo $data['image']?>'); $('#<?php echo $input_id; ?>_image').attr('src','<?php echo base_url($data['image']); ?>');" data-dismiss="modal"><i class="fa fa-copy"></i> <?=_l("Use",$this)?></button>
                </p>
            </div>
        <?php } ?>
    <?php } ?>
</div>

<script>
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
            var tpl = $('<div class="loding col-md-3 col-sm-4 col-xs-6"><div class="working"><i class="fa fa-spin"></i><span></span></div></div>');
            // Append the file name and file size
//            tpl.find('span').text(data.files[0].name)
//                .append(formatFileSize(data.files[0].size));

            // Add the HTML to the UL element
            data.context = tpl;
            tpl.insertAfter('#upload-box');
//            ul.prepend(tpl);

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
            eval("var resultFile = " + data.result);
            if(resultFile.status == "success"){
                var newTpl = '<div class="col-md-3 col-sm-4 col-xs-6">'+
                    '<p style="height:100px;overflow: hidden;"><img src="' + resultFile.file_url + '" style="width: 100%" alt="Image"></p>'+
                    '<p class="text-center">' + resultFile.width + ' <?=_l("px",$this)?> X ' + resultFile.height + ' <?=_l("px",$this)?></p>'+
                    '<p class="text-center">' + resultFile.size + ' <?=_l("MG",$this)?></p>'+
                    '<p class="text-center">'+
                    '<button type="button" class="btn blue" onclick="$(\'#<?php echo $input_id; ?>\').attr(\'value\',\'' + resultFile.file_patch + '\'); $(\'#<?php echo $input_id; ?>_image\').attr(\'src\',\'' + resultFile.file_url + '\');" data-dismiss="modal"><i class="fa fa-copy"></i> <?php echo _l("Use",$this); ?></button>'+
                    '</p>'+
                    '</div>';
                newTpl = $(newTpl);
                data.context.remove();
                newTpl.insertAfter('#upload-box');
                $('#files-list').find('div.empty, div.loading').remove();

                $(this).removeClass("notok");
            }else{
                $(this).addClass("notok");
                toastr.error(resultFile.errors);
                data.context.remove();
            }
        },
        fail:function(e, data){
            // Something has gone wrong!
            toastr.error("'fileupload()' Failed!");
            data.context.remove();
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
</script>