<?php if ($footer1['id'] != null): ?>

    <p><?= filter_data($footer1['data'], $footer1['filter']) ?></p>

<?php else: ?>
    <p>No byline exists.</p>
<?php endif; ?>