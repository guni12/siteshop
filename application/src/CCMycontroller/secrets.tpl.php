<?php
if ($content1 != null):

    foreach ($content1 as $val):
        ?>
        <h1><?= esc($val['title']) ?></h1>
        <p><?= filter_data($val['data'], $val['filter']) ?></p>

    <?php endforeach; ?>
<?php else: ?>
    <p>No posts exists.</p>
<?php endif; ?>