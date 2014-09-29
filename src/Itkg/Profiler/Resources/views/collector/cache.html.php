<?php include_once __DIR__.'/header.html.php'; ?>
<h1>Cache collected data</h1>
<div id="accordion">
    <?php foreach ($collector->getData() as $key => $data) : ?>
        <?php if (!empty($data)) : ?>
            <div class="title"><?= $key ?>
                <span class="right"><?= sizeof($data) ?> cache(s)</span>
            </div>
            <div class="section">
                <table class="tablesorter">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Size (Ko)</th>
                            <th>Number of sets</th>
                            <th>Number of loads</th>
                        </tr>
                    </thead>
                    <tbody>

                    <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><div class="tooltip collector-cache-key" title="<?= $row['name'] ?>"><?= $row['name'] ?></div></td>
                                <td class="numeric"><?= number_format($row['size'] / 1024, 5) ?></td>
                                <td class="numeric"><?= $row['set'] ?></td>
                                <td class="numeric"><?= $row['load'] ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                <?php if (sizeof($stats = $collector->getStatsForKey($key)) > 1) : ?>
                    <canvas id="<?= $key ?>_chart" width="950" height="380"></canvas>

                    <script type="text/javascript">

                        var ctx = document.getElementById("<?= $key ?>_chart").getContext("2d");
                        var _labels = new Array();
                        var _data = new Array();
                        <?php $i = 0; ?>
                         <?php foreach ($stats as $stat) : ?>
                            _labels[<?= $i ?>] = "<?= $stat['date'] ?>";
                            _data[<?= $i ?>]   = <?= $stat['count'] ?>;
                        <?php $i++; ?>
                        <?php endforeach; ?>
                        var data = {
                            labels: _labels,
                            datasets: [
                                {
                                    label: "<?= $key ?>",
                                    fillColor: "rgba(151,187,205,0.2)",
                                    strokeColor: "rgba(151,187,205,1)",
                                    pointColor: "rgba(151,187,205,1)",
                                    pointStrokeColor: "#fff",
                                    pointHighlightFill: "#fff",
                                    pointHighlightStroke: "rgba(151,187,205,1)",
                                    data: _data
                                },
                            ]
                        };

                        var myNewChart = new Chart(ctx).Line(data);

                    </script>
                <?php endif; ?>
            </div>
        <?php endif; ?>
    <?php endforeach; ?>
</div>
<?php include_once __DIR__.'/footer.html.php';