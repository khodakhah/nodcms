$(function(){
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
            var ul = $(this).find("ul");
            var tpl = $('<li class="working"><input class="tete" type="text" value="0" data-width="48" data-height="48"'+
                ' data-fgColor="#0788a5" data-readOnly="1" data-bgColor="#3e4043" /><p></p><span></span></li>');

            // Append the file name and file size
            tpl.find('p').text(data.files[0].name)
                         .append('<i>' + formatFileSize(data.files[0].size) + '</i>');

            // Add the HTML to the UL element
            data.context = ul.html(tpl);

            // Initialize the knob plugin
            tpl.find('input').knob();

            // Listen for clicks on the cancel icon
            tpl.find('span').click(function(){

                if(tpl.hasClass('working')){
                    jqXHR.abort();
                }

                tpl.fadeOut(function(){
                    tpl.remove();
                });

            });

            // Automatically upload the file once it is added to the queue
            var jqXHR = data.submit();
        },

        progress: function(e, data){

            // Calculate the completion percentage of the upload
            var progress = parseInt(data.loaded / data.total * 100, 10);

            // Update the hidden input field and trigger a change
            // so that the jQuery knob plugin knows to update the dial
            data.context.find('input').val(progress).change();

            if(progress == 100){
                data.context.removeClass('working');
            }
        },
        done: function(e, data){
            eval("var resultFile = " + data.result);
            if(resultFile.status == "success"){
                $("#" + $(this).attr("for")).attr("value",resultFile.file_patch);
                if($(this).attr("uploadtype")=="image")
                    $('#' + $(this).attr('for') + '_uploader_thum').html("<img style='width: 100%' src='" + resultFile.file_url + "'>");
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