<div class="right">

        <select rel="<?= $collector->getName() ?>" id="archives" name="archives">
            <?php foreach ($archives as $key => $archive) : ?>
                <option <?php if ($key == 'current') : ?>selected="selected" <?php endif; ?>value="<?= $archive ?>"><?= $archive ?></option>
            <?php endforeach; ?>
        </select>
    <?php if (sizeof($collector->getData()) > 0) : ?>
        <a rel="<?= $collector->getName() ?>" class="collector-clear-link" href="#">
            <img style="width:20px;" src="data:image/png;base64,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"/>
        </a>
        <a rel="<?= $collector->getName() ?>" class="collector-archive-link" href="#">
            <img  style="width:20px;" src="data:image/png;base64,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"/>
        </a>
    <?php endif; ?>
</div>
