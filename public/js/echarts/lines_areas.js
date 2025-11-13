/* ------------------------------------------------------------------------------
 *
 *  # Echarts - lines and areas
 *
 *  Lines and areas chart configurations
 *
 *  Version: 1.0
 *  Latest update: August 1, 2015
 *
 * ---------------------------------------------------------------------------- */

$(function() {
    // Set paths
    // ------------------------------

    require.config({
        paths: {
            echarts: '/js/plugins/visualization/echarts'
        }
    });


    // Configuration
    // ------------------------------

    require(
        [
            'echarts',
            'echarts/theme/limitless',
            'echarts/chart/bar',
            'echarts/chart/line'
        ],


        // Charts setup
        function (ec, limitless) {
            // Initialize charts
            // ------------------------------
            if (window.statisticsData.length) {
                for(var i=0;i<window.statisticsData.length;i++) {
                    new StackedLines(document.getElementById(window.statisticsData[i].chartId), window.statisticsData[i].legend, window.statisticsData[i].dataHorAxis, ec, limitless);
                }
            }
        }
    );
});


var StackedLines = function(chart, chartLegend, data, ec, limitless) {
    if (chart) {
        var stackedLines = ec.init(chart, limitless);

        //
        // Stacked lines options
        //

        var stackedLinesOptions = {

            // Setup grid
            grid: {
                x: 60,
                x2: 20,
                y: 35,
                y2: 25
            },

            // Add tooltip
            tooltip: {
                trigger: 'axis'
            },

            // Add legend
            legend: {
                data: chartLegend
            },

            // Enable drag recalculate
            calculable: true,

            // Hirozontal axis
            xAxis: [{
                type: 'category',
                boundaryGap: false,
                data: xAxis
            }],

            // Vertical axis
            yAxis: [{
                type: 'value'
            }],

            // Add series
            series: data
        };

        // Apply options
        // ------------------------------

        stackedLines.setOption(stackedLinesOptions);


        // Resize charts
        // ------------------------------

        window.onresize = function () {
            setTimeout(function () {
                stackedLines.resize();
            }, 200);
        }
    }
}