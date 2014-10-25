<?php include_once __DIR__.'/header.html.php'; ?>

    <h1>
        XHProf collected data
    </h1>
<div id="accordion">
    <?php foreach ($collector->getData() as $key => $data) : ?>
    <?php if (!empty($data)) : ?>
        <div class="title"><?= $key ?></div>
        <div class="section">

            <table id="xhprof" class="tablesorter">
                <thead>
                <tr>
                    <th class="header">Method</th>
                    <th class="header">Count</th>
                    <th class="header">wait time (ms).</th>
                    <th class="header">CPU time</th>
                    <th class="header">Memory usage (Mo)</th>
                    <th class="header">Peak usage (Mo)</th>
                </tr>
                </thead>
                <tbody>
                <?php foreach ($data as $k => $row) : ?>
                    <tr>
                        <td><div class="tooltip collector-database-query" title="<?= $k ?>"><?= $k ?></div></td>
                        <td class="numeric"><?= $row['ct'] ?></td>
                        <td class="numeric"><?= $row['wt'] ?></td>
                        <td class="numeric"><?= $row['cpu'] ?></td>
                        <td class="numeric"><?= ($row['mu'] / 1000000) ?></td>
                        <td class="numeric"><?= ($row['pmu'] / 1000000) ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

            <?php if (sizeof($stats = $collector->getStatsForKey($key)) > 1) : ?>
                <canvas id="<?= $key ?>_chart" width="950" height="380"></canvas>

                <script type="text/javascript">

                    var ctx = document.getElementById("<?= $key ?>_chart").getContext("2d");
                    var _labels = new Array();
                    var _dataMU = new Array();
                    var _dataPMU = new Array();
                    <?php $i = 0; ?>
                    <?php foreach ($stats as $stat) : ?>
                    _labels[<?= $i ?>] = "<?= $stat['date'] ?>";
                    _dataMU[<?= $i ?>]   = <?= $stat['memory'] / 1000000 ?>;
                    _dataPMU[<?= $i ?>]   = <?= $stat['peak_memory'] / 1000000 ?>;
                    <?php $i++; ?>
                    <?php endforeach; ?>
                    var data = {
                        labels: _labels,
                        datasets: [
                            {
                                label: "Memory Usage (Mo)",
                                fillColor: "rgba(220,220,220,0.2)",
                                strokeColor: "rgba(220,220,220,1)",
                                pointColor: "rgba(220,220,220,1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(220,220,220,1)",
                                data: _dataMU
                            },
                            {
                                label: "Peak Memory Usage (Mo)",
                                fillColor: "rgba(151,187,205,0.2)",
                                strokeColor: "rgba(151,187,205,1)",
                                pointColor: "rgba(151,187,205,1)",
                                pointStrokeColor: "#fff",
                                pointHighlightFill: "#fff",
                                pointHighlightStroke: "rgba(151,187,205,1)",
                                data: _dataPMU
                            },
                        ],
                        legendTemplate : "<ul class=\"<%=name.toLowerCase()%>-legend\"><% for (var i=0; i<datasets.length; i++){%><li><span style=\"background-color:<%=datasets[i].lineColor%>\"></span><%if(datasets[i].label){%><%=datasets[i].label%><%}%></li><%}%></ul>"
                    };

                    var myNewChart = new Chart(ctx).Line(data);

                </script>
            <?php endif; ?>
        </div>
    <?php endif; ?>
<?php endforeach; ?>
</div>
<?php include_once __DIR__.'/footer.html.php';
