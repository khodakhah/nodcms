(function ($) {
    $.fn.handelPlotStatistic = function (options) {
        var $this_element = $(this);
        var default_settings = {
            xaxis: {
                tickLength: 0,
                tickDecimals: 0,
                mode: "categories",
            },
            yaxis: {
                ticks: 5,
                tickDecimals: 0,
                tickColor: "#eee",
                font: {
                    lineHeight: 14,
                    style: "normal",
                    variant: "small-caps",
                    color: "#6F7B8A"
                },
                tickFormatter: format_the_value,
            },
            grid: {
                hoverable: true,
                clickable: true,
                tickColor: "#eee",
                borderColor: "#eee",
                borderWidth: 1
            },
            series: {
                lines: {
                    show: true,
                    lineWidth: 2,
                    fill: true,
                    fillColor: {
                        colors: [{
                            opacity: 0.05
                        }, {
                            opacity: 0.01
                        }]
                    }
                },
                points: {
                    show: true,
                    radius: 3,
                    lineWidth: 1
                },
                shadowSize: 2
            },
        };

        var settings = $.extend(default_settings, options );

        function format_the_value(v) {
            var val = '';
            if(typeof $this_element.data('prefix')!="undefined")
                val += $this_element.data('prefix');
            val += v;
            if(typeof $this_element.data('suffix')!="undefined")
                val += $this_element.data('suffix');
            return val;
        }

        function showChartTooltip(x, y, xValue, yValue) {
            $('<div id="tooltip" class="chart-tooltip">' + format_the_value(yValue) +  '</div>').css({
                position: 'absolute',
                display: 'none',
                top: y - 40,
                left: x - 20,
                border: '0px solid #ccc',
                padding: '2px 6px',
                'background-color': '#fff'
            }).appendTo("body").fadeIn(200);
        }

        var statistic_data = $.parseJSON($this_element.text());
        $this_element.text("");
        $.plot($this_element,statistic_data,settings);
        //
        var previousPoint = null;
        $this_element.bind("plothover", function (event, pos, item) {
            if (item) {
                if (previousPoint != item.dataIndex) {
                    previousPoint = item.dataIndex;
                    $("#tooltip").remove();
                    showChartTooltip(item.pageX, item.pageY, item.datapoint[0], item.datapoint[1]);
                }
            }
        });

        $this_element.bind("mouseleave", function () {
            $("#tooltip").remove();
            previousPoint = null;
        });
    };

    $.fn.handelPlotPieStatistic = function (options) {
        var $this_element = $(this);
        var default_settings = {
            series: {
                pie: {
                    show: true
                }
            }
        };

        var settings = $.extend(default_settings, options );

        var statistic_data = $.parseJSON($this_element.text());
        $this_element.text("");
        $.plot($this_element,statistic_data,settings);
    };
}(jQuery));
$(function () {
    $('.plot-statistics').each(function () {
        $(this).handelPlotStatistic();
    });
    $('.plot-pie-statistics').each(function () {
        $(this).handelPlotPieStatistic();
    });
});