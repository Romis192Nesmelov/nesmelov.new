<div class="chart-container">
    <script>
        window.statisticsData.push({
            legend:[],
            dataHorAxis:[],
            chartId:"{{ $chartId }}"
        });

        var xAxis = [],
            xAxisData = [];
    </script>

    @if (isset($data['last_day_in_month']) && $endPos - $startPos == 1)
        @for($i=1;$i<=$data['last_day_in_month'];$i++)
            <script>
                xAxis.push(parseInt("{{ $i }}"));
                xAxisData.push(0);
            </script>
        @endfor
    @else
        @for ($i=$startPos-1;$i<$endPos;$i++)
            <script>
                xAxis.push(window.allMonths[parseInt("{{ $i }}")]);
                xAxisData.push(0);
            </script>
        @endfor
    @endif

    @foreach($legend as $item)
        <script>
            window.statisticsData[window.statisticsData.length-1].legend.push("{{ $item }}");
            window.statisticsData[window.statisticsData.length-1].dataHorAxis.push({
                name: "{{ $item }}",
                type: 'line',
                data: cloneArrayData(xAxisData)
            });
        </script>
    @endforeach
    <div class="chart has-fixed-height" id="{{ $chartId }}"></div>
</div>