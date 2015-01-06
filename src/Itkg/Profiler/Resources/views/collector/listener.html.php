<?php include_once __DIR__.'/header.html.php'; ?>
    <h1>Cache collected data</h1>
    <div id="accordion">
        <?php foreach ($collector->getData() as $key => $data) : ?>
            <?php if (!empty($data)) : ?>
                <div class="title"><?= $key ?>
                    <span class="right"><?= sizeof($data) ?> event(s)</span>
                </div>
                <div class="section">
                    <table class="tablesorter">
                        <thead>
                        <tr>
                            <th>Event Name</th>
                            <th>Listeners</th>
                        </tr>
                        </thead>
                        <tbody>

                        <?php foreach ($data as $row) : ?>
                            <tr>
                                <td><div class="tooltip collector-cache-key" title="<?= $row['eventName'] ?>"><?= $row['eventName'] ?></div></td>
                                <td>
                                    <?php foreach ($row['listeners'] as $listener) : ?>
                                        <?= $listener['class'] ?>::<?= $listener['method'] ?><br />
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
        <?php endforeach; ?>
    </div>
<?php include_once __DIR__.'/footer.html.php';
