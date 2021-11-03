(function ( $ ) {
    var sampleToField = function ($item) {
        $item.removeClass("ui-draggable").removeAttr("style");
        $item.find(".portlet-body-label").remove();
        $item.find(".portlet-body:not(.portlet-body-label)").removeClass("hidden");
        $item.find(".portlet-title").removeClass("hidden");
    };
    $.fn.nodcmsFieldBuilder = function($options){
        var settings = $.extend({
            form_content: null,
            index: 0,
            sampleToField: sampleToField,
        }, $options );
        if(settings.form_content == null){
            alert("Please set the 'form_content'!");
            return $(this);
        }
        var $form_elements = $(this),
            $sortable_elements = (typeof settings.form_content == 'object')?settings.form_content:$(settings.form_content),
            input_index = settings.index;
        if($sortable_elements.length <=0){
            alert(settings.form_content+" couldn't find!");
            return $form_elements;
        }
        // Do form content sortable
        $sortable_elements.sortable({
//            connectWith: ".portlet",
            items: ".portlet",
            opacity: 0.8,
            handle : '.portlet-title',
            coneHelperSize: true,
            placeholder: 'portlet-sortable-placeholder',
            tolerance: "pointer",
            forcePlaceholderSize: !0,
            helper: "clone",
            cancel: ".portlet-sortable-empty, .portlet-fullscreen", // cancel dragging if portlet is in fullscreen mode
            revert: true,
            update: function(b, c) {
                if (c.item.prev().hasClass("portlet-sortable-empty")) {
                    c.item.prev().before(c.item);
                }
            },
            stop:function (event,ui) {
                var $item = $(ui.item[0]);
                if(!$item.find(".portlet-title").hasClass("hidden")){
                    return;
                }
                settings.sampleToField($item);
                input_index++;
            }
        });
        // Do tools draggable
        $(".portlet", $form_elements).each(function () {
            var the_item = $(this);
            the_item.draggable({
                connectToSortable: "#sortable_portlets",
                opacity: 1,
                coneHelperSize: true,
                placeholder: 'portlet-sortable-placeholder',
                tolerance: "pointer",
                forcePlaceholderSize: false,
                helper: "clone",
                cancel: ".portlet-sortable-empty, .portlet-fullscreen", // cancel dragging if portlet is in fullscreen mode
                revert: "invalid",
                start:function(event,ui){
//                    console.log(ui);
                    $(ui.helper[0]).css({
                        'width':$("#form-elements").css("width"),
                        'z-index':1000,
                    }).removeClass("bg-inverse bg-grey-mint font-white").find("input,select,textarea").each(function () {
                        if($(this).attr('name')=="field_order"){
                            $(this).val(input_index);
                        }
                        $(this).attr("name",'inputs['+input_index+'][' + $(this).attr("name") + ']');
                        var input_name = $(this).attr("name");
                        var filed_id = input_name.replace(/\[/g, '_1').replace(/\]/g, '1_');
                        $(this).attr("id",filed_id);
                    });
                }
            });
        });
    };

    $.fn.replaceType = function ($sample_id) {
        var this_element = $(this), toolsBox = new $($sample_id), input_index = this_element.find("input").filter(function() {
            return this.name.match(/.*field_order.*/);
        }).val(), modalContent = $('<div class="text-center"></div>');

        $.fn.doReplace = function () {
            var newContent = $(this).clone();
            newContent.find("input:not([type=checkbox],[type=radio]),select,textarea").each(function () {
                if($(this).attr('name')=="field_order"){
                    $(this).val(input_index);
                }
                var new_name = 'inputs['+input_index+'][' + $(this).attr("name") + ']',
                    field_id = new_name.replace(/\[/g, '_1').replace(/\]/g, '1_'),
                    existsField = this_element.find('#'+field_id);
                if(existsField.length > 0 && $(this).attr('name')!="field_type"){
                    $(this).attr('value', existsField.val());
                }
                $(this).attr("name", new_name);
                $(this).attr("id",field_id);
            });
            newContent.find("input[type=checkbox],input[type=radio]").each(function () {
                var new_name = 'inputs['+input_index+'][' + $(this).attr("name") + ']',
                    field_id = new_name.replace(/\[/g, '_1').replace(/\]/g, '1_'),
                    existsField = this_element.find('#'+field_id);
                if(existsField.length > 0 && $(this).attr('name')!="field_type"){
                    $(this).attr('checked', existsField.is(':checked')?"checked":"");
                }
                $(this).attr("name", new_name);
                $(this).attr("id",field_id);
            });
            sampleToField(newContent);
            this_element.html(newContent.html());
        };

        toolsBox.find('.field-sample').each(function () {
            var this_item = $(this);
            $("<button type='button' data-dismiss='modal' class='btn default margin-bottom-10 btn-block'>"+$(this).find(".field-label").text()+"</button>").click(function () {
                this_item.doReplace();
            }).appendTo(modalContent);
        });

        $.showInModal(this_element.data('change-title'), modalContent);

    };

}( jQuery ));