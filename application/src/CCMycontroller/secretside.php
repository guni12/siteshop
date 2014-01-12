<div id='sidecolor'>
    <?php
    if ($content2 != null):

        foreach ($content2 as $val):
            ?>

            <h2><?= esc($val['title']) ?></h2>
            <p><?= filter_data($val['data'], $val['filter']) ?></p>

        <?php endforeach; ?>
    <?php else: ?>
        <p>No posts exists.</p>
    <?php endif; ?>
</div>