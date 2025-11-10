<script>
    for (var s=0;s<window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis.length;s++) {
        for (var i=0;i<window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis[s].data.length;i++) {
            if (!window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis[s].data[i]) window.statisticsData[parseInt("{{ $chart }}")].dataHorAxis[s].data[i] = undefined;
        }
    }
</script>