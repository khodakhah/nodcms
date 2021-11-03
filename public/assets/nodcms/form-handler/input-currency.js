(function ($) {
    $.fn.inputCurrencyFormat = function (options) {
        var settings = $.extend({
            divider: '.',
            target: '',
        }, options );
        var $this_element = $(this);
        var type_len = 0;
        var latest_value = "";
        var format = function (input) {
            var the_value = parseFloat(input.replace(settings.divider,"."));
            return the_value.toFixed(2);
        };

        var divider_display = function (input) {
            return input.replace("\.", settings.divider);
        };

        $this_element
            .change(function () {
                var formatted = format($(this).val());
                if(formatted === "NaN"){
                    if(settings.target != '')
                        $(settings.target).val(0);
                    $(this).val('0'+settings.divider+'00');
                    return;
                }
                if(settings.target != '')
                    $(settings.target).val(formatted);
                $(this).val(divider_display(formatted));
                var input = $(this);
                input[0].selectionStart = input[0].selectionEnd = input.val().length;
        });
        if(settings.target != '' && $this_element.val()!=''){
            var formatted = format($(settings.target).val());
            $this_element.val(divider_display(formatted));
        }
        return this;
    };
}(jQuery));

$(function () {
    $('input.currency-format').each(function () {
        $(this).inputCurrencyFormat({
            divider: $(this).data('divider'),
            target: $(this).data('target')
        });
    });
});