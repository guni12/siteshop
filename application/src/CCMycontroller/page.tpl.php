<?php
if ($contents != null):
    //var_dump($contents);
    if ($val['id'] = 12)
        ?>

        <img title ='thoughtful face' alt='portrait of Gunvor' src="<?= base_url(); ?>application/src/CCMycontroller/img/gunvor.jpg" />

    <?php foreach ($contents as $val): ?>
        <h1><?= esc($val['title']) ?></h1>
        <p><?= filter_data($val['data'], $val['filter']) ?></p>

    <?php endforeach; ?>
<?php else: ?>
    <p>No posts exists.</p>
<?php endif; ?>
