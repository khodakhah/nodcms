/**
 * NodCMS special form functions & plugins
 * Created by Mojtaba on 8/24/2017.
 */
(function ( $ ) {
    /**
     * Submit an ajax form
     */
    $.fn.nodcmsFormAjaxSubmit = function () {
        var the_form = $(this);
        the_form.find(".ckeditor,.ckeditor-quick").each(function () {
            $(this).val(CKEDITOR.instances[$(this).attr("id")].getData());
        });
        $(".summernote").each(function () {
            var thevalue = $(this).summernote('code');
            if(thevalue == '<p><br></p>' || thevalue == '<br>'){
                $(this).val('');
            }else{
                $(this).val($(this).summernote('code'));
            }
        });
        var inputs = {}, index = 0;
        the_form.find('input[type="radio"]:checked,input[type="checkbox"]:checked,input[type="hidden"],input[type="text"],input[type="password"],input[type="email"],input[type="url"],input[type="number"],textarea,select').each(function () {
            if(typeof $(this).attr('name') !== typeof undefined){
                inputs[$(this).attr('name')] = $(this).val();
                index++;
            }
        });

        var submit_button = $("button[onclick=\"$(\'#"+the_form.attr('id')+"\').submit();\"]");
        if(submit_button.length<1)
            submit_button = the_form.find('button[type="submit"]');
        $.ajax({
            url: the_form.attr('action'),
            data: inputs,
            type: the_form.attr('method'),
            dataType:'json',
            beforeSend: function () {
                the_form.find("has-error").removeClass("has-error");
                the_form.find('.form-error').remove();
                submit_button.addClass('disabled').prepend($(' <i class="fa fa-spinner fa-pulse loading-icon"></i>'));
            },
            complete: function () {
                submit_button.removeClass('disabled').find('.loading-icon').remove();
            },
            success: function (result) {
                if(result.status == 'success'){
                    if(typeof result.data!='undefined' &&
                        typeof result.data.callback_success != 'undefined'){
                        eval(result.data.callback_success);
                        return;
                    }
                        // Reset form
                    if(the_form.data('replace-message')==1){
                        var form_message = "<div class='portlet light'>" +
                            "<div class='portlet-body text-center'>" +
                            "<p><i class='fa fa-check-circle fa-5x font-green-meadow'></i></p>" +
                            "<p>"+result.msg+"</p>" +
                            "<a class=\"btn blue-steel\" href=\""+result.url+"\">"+ translate('Continue') +"</a>" +
                            "</div>" +
                            "</div>";
                        the_form.parent().html(form_message);
                        return;
                    }
                    if(the_form.data('reset')==1){
                        the_form.find('input[type="text"]:not(.donotreset),input[type="password"]:not(.donotreset),input[type="email"]:not(.donotreset),input[type="number"]:not(.donotreset),textarea:not(.donotreset),select:not(.donotreset)').each(function () {
                            $(this).val('');
                        });
                        the_form.find('.input-preview').each(function () {
                            $(this).attr('src', $(this).attr('data-preview'));
                        });
                        the_form.find('select').each(function () {
                            $(this).selectedIndex = -1;
                        });
                        if($.isFunction($.removeAllDropzoneFiles))
                            $.removeAllDropzoneFiles();
                    }
                    if(the_form.data('message')==1){
                        var form_message = "<div class='text-center'><p><i class='fa fa-check-circle fa-5x font-green-meadow'></i></p><p>"+result.msg+"</p></div>";
                        if(the_form.data('redirect')==1){
                            $.showInModal(translate('Success'), form_message, $('<a class="btn blue-steel" href="'+result.url+'">'+ translate('Continue') +'</a>'));
                            return;
                        }
                        $.showInModal(translate('Success'), form_message);
                    }
                    else{
                        toastr.success(result.msg, 'Success');
                    }
                    // Redirect page
                    if(the_form.data('redirect')==1){
                        $('<span class="label"><i class="fa fa-spinner fa-pulse"></i> Redirecting...</span>').insertBefore(submit_button);
                        submit_button.remove();
                        the_form.find('.btn-submit').remove();
                        window.location = result.url;
                    }
                    if(the_form.data('close')==1){
                        var $the_modal = the_form.parents('.modal');
                        if($the_modal.length!=0){
                            $the_modal.modal('hide');
                        }
                    }
                    // Execute the success code
                    if(result.hasOwnProperty('data') && result.data.hasOwnProperty('success')){
                        eval(result.data.success);
                    }
                }
                else{
                    if(typeof result.error === 'object'){
                        var error_msg = "";
                        $.each(result.error, function (key, val) {
                            error_msg += key+" => "+val+"<br>";
                            var filed_id = key.replace(/\[/g, '_1').replace(/\]/g, '1_');
                            var error_message = '<div class="form-error font-red"><i class="fa fa-exclamation-circle"></i> ' + val + '</div>';
                            // General inpots
                            $('#'+filed_id+':not(.ckeditor,.summernote,.select2me,.attachment-value)').nodcmsFormAddErrorMessage(error_message);
                            // CKeditor, select2
                            $('#'+filed_id+'.ckeditor, #'+filed_id+'.select2me').next().nodcmsFormAddErrorMessage(error_message);
                            // attachments
                            $('#'+filed_id+'-parent').nodcmsFormAddErrorMessage(error_message);
                        });
                        toastr.error("There is some invalid inputs.", 'Error');
                        // $.showInModal("Error",error_msg);
                        // console.log("Error",error_msg);
                    }
                    else{
                        // $.showInModal(translate('Error')+': '+translate('Ajax failed!'), '<div class="alert alert-danger">' +
                        //     '<h4>'+translate('Error')+'</h4>' +
                        //     '</div>' +
                        //     '<h4>'+translate('Result')+'</h4>' +
                        //     result.error);
                        toastr.error(result.error, 'Error');
                    }
                }
            },
            error: function (xhr, status, error) {
                // console.log(xhr.responseText);
                toastr.error('Send form with ajax failed!', 'Error');
            }
        });
    };

    /**
     * Reset the index of sortable fields
     * @param index
     */
    $.fn.nodcmsFormResetIndex = function (index) {
        $(this).find("input,textarea,select").each(function () {
            var the_name = $(this).data('name').split('[]').join('['+index+']'),
                filed_id = the_name.replace(/\[/g, '_1').replace(/\]/g, '1_');
            $(this).attr('name', the_name).attr('id', filed_id);
        });
    };
    $.fn.nodcmsFormSortable = function ($options) {
        var inputs_index = 0, $sortable_elements = $(this);
        var settings = $.extend({
            sample: function(form_content){ return form_content; },
            add_button: null,
            fields_input: null,
            default_input: null,
            sortable_options: {
                items: " > *",
                stop:function (event,ui) {}
            }
        }, $options );
        settings.sortable_options.stop = function (event,ui) {
            $sortable_elements[settings.sortable_options.items].each(function () {
                $(this).nodcmsFormResetIndex(inputs_index);
                inputs_index++;
            });
        };
        $sortable_elements.sortable(settings.sortable_options);

        if(settings.default_input != null){
            var defaults_inputs = $.parseJSON($(settings.default_input).val());
            $.each(defaults_inputs, function (key, val) {
                $sortable_elements.append($(settings.sample(val)));
            });
            inputs_index = defaults_inputs.length;
        }
        if(settings.add_button != null && settings.fields_input != null){
            settings.add_button.click(function () {
                var $inputs = $(settings.sample(settings.fields_input.val()));
                $inputs.find("input,textarea,select").each(function () {
                    var the_name = $(this).data('name').split('[]').join('['+inputs_index+']'),
                        filed_id = the_name.replace(/\[/g, '_1').replace(/\]/g, '1_');
                    $(this).attr('name', the_name).attr('id', filed_id);
                });
                $sortable_elements.append($inputs);
                inputs_index++;
            });
        }
    };
    $.fn.nodcmsFormAddErrorMessage = function(error_message){
        var theErrorField = $(this);
        if(theErrorField.length<=0)
            return;
        var theGroup = theErrorField.parent();
        while (!theGroup.hasClass('form-group') && theGroup.length>0){
            theGroup = theGroup.parent();
        }
        $(error_message).insertAfter(theErrorField);
        theGroup.addClass("has-error");
        theErrorField.change(function () {
            theGroup.removeClass('has-error');
            theGroup.find('.form-error').remove();
        });
    };
    $.fn.nodcmsRemoveMyFile = function(element_id){
        var $this = $(this);
        $.ajax({
            url: $this.data('action'),
            dataType:'json',
            beforeSend: function () {
                // TODO: Start loading
            },
            complete: function () {
                // TODO: Remove loading
            },
            success: function (result) {
                if(result.status == 'success'){
                    // toastr.success(result.msg, 'Success');
                    $('#' + element_id).remove();
                }
                else{
                    toastr.error(result.error, 'Error');
                }
            },
            error: function (xhr, status, error) {
                console.log(xhr.responseText);
                toastr.error('Send form with ajax failed!', 'Error');
            }
        });
    };
    $.fn.nodcmsInputsToggleClass = function (value, element, classes) {
        if($(this).val()==value){
            $(element).removeClass(classes);
        }else{
            $(element).addClass(classes);
        }
    };

    /**
     * Handel the multi-select-checkboxs
     */
    $.fn.nodcmsFormMultiCheckboxSelect = function () {
        $(this).find('input[type="checkbox"].checkbox-multiselect').each(function () {
            var $this_element = $(this);
            $this_element.click(function () {
                var vals = [];
                $('input[type="checkbox"][data-group="'+$this_element.attr('data-group')+'"].checkbox-multiselect:checked').each(function () {
                    vals.push($(this).val());
                });
                $($this_element.attr('data-group')).val(vals.join(','));
            });
        });
    };

    $.fn.nodcmsForm = function () {
        function parseDate(input, format) {
            format = format || 'yyyy-mm-dd'; // default format
            var parts = input.match(/(\d+)/g),
                i = 0, fmt = {};
            // extract date-part indexes from the format
            format.replace(/(yyyy|dd|mm)/g, function(part) { fmt[part] = i++; });

            return new Date(parts[fmt['yyyy']], parts[fmt['mm']]-1, parts[fmt['dd']]);
        }
        var $this_form = $(this);
        // make on change
        $this_form.find('input[type="text"],input[type="email"],input[type="url"],input[type="password"]').each(function () {
            if(typeof $(this).attr('onchange') !== typeof undefined && $(this).attr('onkeydown') !== false){
                $(this).on('onkeydown', $(this).attr('onchange'));
                $(this).on('onpaste', $(this).attr('onchange'));
                $(this).on('oninput', $(this).attr('onchange'));
            }
        });

        $this_form.find(".survey-points-radio").each(function () {
            var $survey = $(this);
            $survey.find("label").mousemove(function () {
                var current_number = $(this).find("input").val();
                $survey.find("label").each(function () {
                    var $icon = $(this).find("i");
                    if($(this).find("input").attr('value') <= current_number){
                        $icon.attr("class", $icon.data("icon-on"));
                    }else{
                        $icon.attr("class", $icon.data("icon-off"));
                    }
                });
            })
                .mouseleave(function () {
                    if($survey.find("label.selected").length > 0){
                        var current_number = $survey.find("label.selected").find("input").val();
                    }
                    $survey.find("label").each(function () {
                        var $icon = $(this).find("i");
                        if($(this).find("input").attr('value') <= current_number){
                            $icon.attr("class", $icon.data("icon-on"));
                        }else{
                            $icon.attr("class", $icon.data("icon-off"));
                        }
                    });
                })
                .click(function () {
                    // $survey.find("input").not($(this).find('input')).remove("selected");
                    $survey.find("label").not($(this)).removeClass("selected");
                    $(this).addClass("selected");
                });
        });

        // * Datepicker inputs
        $this_form.find('.input-datepicker').each(function () {
            var element = $(this);
            var element_loading = $('<i class="fa fa-spinner fa-pulse"></i>');
            element_loading.insertAfter(element);
            var data = {};
            // * Multi date picker
            if(element.hasClass('multidatepicker'))
                data.multidate = true;
            if(element.hasClass('rangedatepicker')){
                data.inputs = $(".rangedatepicker-inputs");
            }
            $.each($(this).data(), function (key, val) {
                var result = key.replace(/\-([a-z])/g, "$1".toUpperCase());
                data[result] = val;
            });
            var calendar_filter = $('#calendarfilter_'+ $(this).attr('id'));
            if(calendar_filter.length > 0){
                if(calendar_filter.html()!=''){
                    var result = $.parseJSON(calendar_filter.html());
                    data.beforeShowDay = function (date) {
                        var phptime = Math.round(date.getTime()/1000).toString();
                        var day_enabled = true;
                        var in_additional = false;
                        if(result.hasOwnProperty('additional_dates'))
                            in_additional = $.inArray(phptime, result.additional_dates) != -1;
                        var in_disable = false;
                        if(result.hasOwnProperty('disable_dates'))
                            in_disable = $.inArray(phptime, result.disable_dates) != -1;
                        var in_disable_week = false;
                        if(result.hasOwnProperty('disable_days'))
                            in_disable_week = $.inArray(date.getDay(), result.disable_days) != -1;
                        if(in_disable_week){
                            day_enabled = false;
                        }else if(in_disable){
                            day_enabled = false;
                        }
                        if(in_additional){
                            day_enabled = true;
                        }
                        return {
                            enabled: day_enabled
                        };
                    };
                }
            }
            var has_changedate = typeof element.attr('changedate') != "undefined";
            var has_microtime_input = typeof element.data('id') != "undefined";
            element.datepicker(data).on('changeDate', function (e) {
                if(has_microtime_input){
                    var intDates = [];
                    if(element.hasClass('rangedatepicker')){
                        var new_date = parseDate($('#'+element.data('id')+"_from").val(),element.data('format'));
                        intDates[0] = new_date.getTime();

                        new_date = parseDate($('#'+element.data('id')+"_to").val(),element.data('format'));
                        intDates[1] = new_date.getTime();
                        $('#'+element.data('id')).val(intDates.join(','));
                    }else{
                        $.each(e.dates, function (key, val) {
                            intDates[key] = val.getTime();
                        });
                    }
                    if($('#'+element.data('id')+"_microtime").length > 0)
                        $('#'+element.data('id')+"_microtime").val(intDates.join(','));
                }
                if(has_changedate){
                    eval(element.attr('changedate'));
                }
            });
            if(element.val()!=''){
                if(has_changedate){
                    eval(element.attr('changedate'));
                }
            }
            element_loading.remove();
        });

        $this_form.find('input.auto-complete-text').each(function(){
            var save_array = [];
            var save_data = {};
            var last_val = "";
            var found_count = 0;
            var save_limit = 5;
            var match_rules = '[0-9A-Za-z\\_\\-\\.]*';
            $(this).change(function () {
                var this_element = $(this);
                if(!this_element.val().match('/^'+match_rules+'$/')){
                    this_element.parents('form-group').addClass('has-error');
                    this_element.parents('form-group').addClass('has-error');
                    return;
                }
                this_element.parents('form-group').removeClass('has-error');
                var inway = this_element.val().match('/^'+last_val+match_rules+'$/');
                if(inway && found_count <= save_limit){
                    return;
                }
                var find_a_match = false;
                $.each(save_array, function (key, val) {
                    if(!this_element.val().match('/^'+val+match_rules+'$/')){
                        delete save_array[key];
                        delete save_data[key];
                    }else{
                        find_a_match = true;
                    }
                });
                if(find_a_match &&  found_count <= save_limit)
                    return;
                if(save_array.length >= save_limit)
                    return;
                $.ajax({
                    url: this_element.data('url'),
                    type: "post",
                    data: {'text':this_element.val()},
                    dataType: "json",
                    success: function (result) {
                        if(result.status == "success"){
                            save_array = result.data.data;
                            found_count = result.data.data_count;
                        }else{
                            toastr.error(result.error, 'Error');
                        }
                    },
                    fail: function () {
                        toastr.error('Send form with ajax failed!', 'Error');
                    }
                });
                if(save_array.length != 0){
                    // Set  the dropdown
                }else{
                    // Close dropdown
                }
                last_val = this_element.val();
            });
        });

        // Handle switches
        $this_form.find('.make-switch').bootstrapSwitch();

        $this_form.nodcmsFormMultiCheckboxSelect();

        if($.isFunction('TouchSpin')){
            $this_form.find(".touchspin-verticalbuttons").TouchSpin({
                verticalbuttons: true,
                min:($(this).data('min')?$(this).data('min'):0),
                max:($(this).data('max')?$(this).data('max'):null)
            });
        }
        if($.fn.summernote){
            $this_form.find('.summernote').summernote({height: 300});
        }

        if($this_form.data('submit')=="ajax"){
            $this_form.submit(function (e) {
                e.preventDefault();
                $(this).nodcmsFormAjaxSubmit();
            });
        }
    };

}( jQuery ));

function insertAtTexteditor(areaId,text) {
    CKEDITOR.instances[areaId].insertText(text);
}

var onSubmit = function(token) {
    $(function () {
        $('form[data-grecaptcha="1"]').each(function () {
            $(this).find('input[name="google-invisible-reCaptcha-token"]').val(token);
            if($(this).data('submit') == "ajax"){
                $(this).nodcmsFormAjaxSubmit();
            }else{
                $(this).submit();
            }
        });
    });
    grecaptcha.reset();
};
