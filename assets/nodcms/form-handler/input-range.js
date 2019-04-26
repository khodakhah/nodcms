(function ($) {
    $.fn.makeRangeInput = function (options) {
        var $thisElement = $(this);
        var settings = $.extend({
            input: $thisElement.data('input'),// jQuery-link to input
            //slider: object,                   // jQuery-link to sliders container
            min: $thisElement.data('min'),    // MIN value
            max: $thisElement.data('max'),    // MAX values
            from: 0,                          // FROM value
            from_percent: 10,                 // FROM value in percents
            from_value: 0,                    // FROM index in values array (if used)
            to: 90000,                        // TO value
            to_percent: 90,                   // TO value in percents
            to_value: 0,                      // TO index in values array (if used)
            min_pretty: "1 000",              // MIN prettified (if used)
            max_pretty: "100 000",            // MAX prettified (if used)
            from_pretty: "10 000",            // FROM prettified (if used)
            to_pretty: "90 000",              // TO prettified (if used)
            type: $thisElement.data('type'),
            prefix: "",
            grid: true,
            grid_num: $thisElement.data('grid')
        }, options);
        
        var ionRange = {
            min: settings.min,
            max: settings.max,
            type: settings.type,
            prefix: settings.prefix,
            grid: settings.grid,
            grid_num: settings.grid_num,
            input_values_separator: "-"
        };
        $thisElement.ionRangeSlider(ionRange);
        return $thisElement;
    };
}(jQuery));
$(function () {
    $('.range-input').each(function () {
        $(this).makeRangeInput();
    });
});