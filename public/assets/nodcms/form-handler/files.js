(function ($) {
    var allDropzone = {}, allDropzoneJquery = {};
    $.removeAllDropzoneFiles = function () {
        $(".dropzone-default").val('').text('');
        $.each(allDropzone, function (key,val) {
            val.removeAllFiles(true);
            allDropzoneJquery[key].find('.dz-preview').remove();
        });
    };
    $.fn.makeDropzone = function () {
        return this.each(function() {
            var $_this = $(this);
            var existingFileCount = 0,maxFiles = $_this.data('max-files'),maxFileSize = $_this.data('max-file-size');
            var dropzone_option = {
                url:$_this.attr('action'),
                // acceptedFiles: $_this.data('accept-files'),
                dictDefaultMessage: "",
                // maxFiles:maxFiles,
                maxFileSize:maxFileSize,
                maxfilesexceeded:function (file) {
                    file.previewElement.remove();
                },
                init: function() {
                    var myDropzone = this;
                    $.addRemoveButtonToDropzone = function (file, result) {
                        $(file.previewElement).find('.cancel-btn').remove();
                        var $parents = $(file.previewElement).parents('.dropzone');
                        $(file.previewElement).attr('id','dz-preview'+result.file_id);
                        var removeButton = Dropzone.createElement('<button data-action="'+$parents.data('remove-url')+result.file_id+'-'+result.remove_key+'" type="button" class="btn red btn-sm btn-block"><i class="fa fa-trash"></i></button>');
                        $(removeButton).click(function() {
                            $(this).nodcmsRemoveMyFile('dz-preview'+result.file_id);
                        });
                        file.previewElement.appendChild(removeButton);
                        var filed_name = $parents.data('list-name').replace(/\[\]/g, '['+existingFileCount+']');
                        existingFileCount++;
                        if(myDropzone.options.maxFiles!=null){
                            myDropzone.options.maxFiles = maxFiles - existingFileCount;
                        }
                        var pathInput = Dropzone.createElement('<input name="'+filed_name+'" type="hidden" value="'+result.file_id+'">');
                        file.previewElement.appendChild(pathInput);
                    };

                    this.on("addedfile", function(file) {
                        var removeButton = Dropzone.createElement("<button class='cancel-btn btn red btn-sm btn-block'><i class='fa fa-times'></i></button>");
                        var _this = this;
                        removeButton.addEventListener("click", function(e) {
                            e.preventDefault();
                            e.stopPropagation();
                            _this.removeFile(file);
                        });

                        // Add the button to the file preview element.
                        file.previewElement.appendChild(removeButton);
                    })
                        .on("complete", function (file, errorMessage) {
                        })
                        .on("error", function (file, errorMessage) {
                            $.showInModal("Error",errorMessage);
                            this.removeFile(file);
                        })
                        .on("success", function (file, finished) {
                            if(finished.charAt(0) != "{"){
                                $.showInModal("Error",finished);
                                this.removeFile(file);
                                return;
                            }
                            var result = JSON.parse(finished);
                            if(result.status == "error"){
                                $.showInModal("Error",result.error);
                                this.removeFile(file);
                                return;
                            }
                            var fileThum = $(file.previewElement).find('img');
                            if(typeof fileThum.attr('src')=='undefined'){
                                myDropzone.emit("thumbnail", file, '/' + result.file_thumbnail);
                                if(fileThum.width() >  fileThum.height()){
                                    fileThum.height('100%');
                                }else{
                                    fileThum.width('100%');
                                }
                            }
                            $.addRemoveButtonToDropzone(file,result);
                        })
                        .on("removedfile", function (file) {
                            existingFileCount=0;
                            if(myDropzone.options.maxFiles!=null){
                                myDropzone.options.maxFiles = maxFiles - existingFileCount;
                            }
                        });

                    var storedFiles = $_this.find(".dropzone-default").length === 0 ? {} : $.parseJSON($_this.find(".dropzone-default").val());
                    $.each(storedFiles, function (key,val) {
                        // Create the mock file:
                        var mockFile = { name: val.name, size: val.size};
                        myDropzone.emit("addedfile", mockFile);
                        myDropzone.emit("thumbnail", mockFile, '/'+ val.file_thumbnail);
                        $.addRemoveButtonToDropzone(mockFile,val);
                        myDropzone.emit("complete", mockFile);
                        var mockThum = $(mockFile.previewElement).find('img');
                        if(mockThum.width()>mockThum.height()){
                            mockThum.height('100%');
                        }else{
                            mockThum.width('100%');
                        }
                    });

                    allDropzone[$_this.attr('id')] = myDropzone;
                }
            };
            if($_this.data('accept-files')!="*"){
                dropzone_option.acceptedFiles = $_this.data('accept-files');
            }

            /**
             * Use dropzone function only after it is loaded.
             *
             * @private
             */
            function _load() {
                if(typeof $.fn.dropzone === "function") {
                    $_this.dropzone(dropzone_option);
                    allDropzoneJquery[$_this.attr('id')] = $_this;
                    return;
                }
                setTimeout(_load, 100);
            }

            _load();
        });
    };
}(jQuery));
$(function () {
    $(".dropzone").makeDropzone();
});