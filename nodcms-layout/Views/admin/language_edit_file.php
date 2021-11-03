<section class="portlet light">
    <div class="portlet-title">
        <div class="caption" style="direction: ltr;">
            <?php echo $file_path; ?>
        </div>
    </div>
    <div class="portlet-body">
        <div class="form">
            <?php
            if(isset($lang_list) && count($lang_list)!='') {
                $i=0;
                ?>
                <table class="table table-striped table-bordered table-advance table-hover">
                    <thead>
                    <tr>
                        <th></th>
                        <th><?php echo _l('Language Key',$this); ?></th>
                        <th><?php echo _l('Show in Website',$this); ?></th>
                        <th></th>
                    </tr>
                    </thead>
                    <tbody>
                    <?php foreach($lang_list as $key=>$value){ $i++; ?>
                        <tr class="lang-row" data-number="<?php echo $i; ?>" id="row-<?php echo $i; ?>">
                            <td><?php echo $i; ?>.</td>
                            <td style="width: 50%;">
                                <p id="lbl<?php echo $i;?>"><?php echo $key; ?></p>
                            </td>
                            <td>
                                <p id="caption-<?php echo $i; ?>" data-id="<?php echo $i; ?>" class="caption"><?php echo $value?></p>
                                <textarea style="direction: <?php echo (isset($data['rtl']) && $data['rtl']==1)?'rtl':'ltr'; ?>;" class="form-control hidden" id="data<?php echo $i;?>" name="data[]"><?php echo $value?></textarea>
                            </td>
                            <td>
                                <button class="btn btn-default edit-btn" type="button" data-id="<?php echo $i; ?>" id="edit-btn-<?php echo $i; ?>"><?php echo _l("Edit", $this); ?></button>
                                <button class="btn btn-primary save-btn hidden" type="button" data-id="<?php echo $i; ?>" id="save-btn-<?php echo $i; ?>"><?php echo _l("Save", $this); ?></button>
                                <button class="btn btn-default edit-btn hidden" type="button" data-id="<?php echo $i; ?>" id="cancel-btn-<?php echo $i; ?>"><?php echo _l("Cancel", $this); ?></button>
                            </td>
                        </tr>
                    <?php } ?>
                    </tbody>
                </table>
                <?php
            }
            ?>
        </div>
    </div>
</section>
<script>
    $(function () {
        $("#translation").submit(function (e) {
            e.preventDefault();
            var lang_file_text = "";
            $(".lang-row").each(function () {
                lang_file_text += '$lang["'+ $(this).find('label').text() +'"] = "' + $(this).find('input').val() + '";\n';
            });
            alert(lang_file_text);
        });
        $(".edit-btn, .caption").click(function () {
            var data_id = $(this).data("id");
            $('#data'+data_id+', #save-btn-'+data_id+', #cancel-btn-'+data_id+', #edit-btn-'+data_id+', #caption-'+data_id).toggleClass("hidden");
        });
        $(".save-btn").click(function () {
            var data_id = $(this).data("id");
            var this_button = $(this);
            var post_data = {'key':$('#lbl'+data_id).text(), 'value':$('#data'+data_id).val()};
            $.ajax({
                url: "<?php echo ADMIN_URL."languageTranslation".(isset($data['language_id'])?"/".$data['language_id']:"").(isset($file_name)?"/".$file_name:""); ?>",
                data:post_data,
                method:'post',
                dataType:'json',
                success:function (data) {
                    console.log(data);
                    if(data.status == "success"){
                        $('#caption-'+data_id).text(post_data.value);
                        $('#data'+data_id+', #save-btn-'+data_id+', #cancel-btn-'+data_id+', #edit-btn-'+data_id+', #caption-'+data_id).toggleClass("hidden");
                        toastr.success(data.msg);
                    }else{
                        toastr.error(data.error);
                    }
                },
                fail:function () {
                    toastr.error("Send data fail!");
                },
                beforeSend: function(){
                    this_button.addClass("disabled");
                },
                complete: function(){
                    this_button.removeClass("disabled");
                },
            });
        });
    });
</script>
