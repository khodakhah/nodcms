(function ($) {
    $.fn.inputNumberFormat = function (options) {
        var settings = $.extend({
            min: 0,
            max: 0,
        }, options );
        var $this_element = $(this);
        var $input = $this_element.find('.input-value');
        var $input_minus = $this_element.find('.input-minus-btn');
        var $input_plus = $this_element.find('.input-plus-btn');

        var set_input_value = function (value) {
            var number_value = value;
            if(isNaN(number_value)) number_value = parseInt(settings.min);
            if(number_value<settings.min) number_value = settings.min;
            if(settings.max > 0 && number_value>settings.max) number_value = settings.max;
            $input.val(number_value);
        };

        if(typeof $input.attr('onchange')!='undefined'){
            var on_submit = function () {
                eval($input.attr('onchange'));
            }
        }else{
            var on_submit = function () {};
        }
        
        // Input change
        $input.change(function () {
            var number_value = parseInt($(this).val());
            set_input_value(number_value);
            on_submit();
        });
        // Count down
        $input_minus.click(function () {
            var new_number = parseInt($input.val())-1;
            set_input_value(new_number);
            on_submit();
        });
        // Count up
        $input_plus.click(function () {
            var new_number = parseInt($input.val())+1;
            set_input_value(new_number);
            on_submit();
        });

        return this;
    };
}(jQuery));

$(function () {
    $('div.national-number-format').each(function () {
        $(this).inputNumberFormat({
            max: $(this).find('.input-value').data('max'),
            min:  $(this).find('.input-value').data('min'),
        });
    });
});