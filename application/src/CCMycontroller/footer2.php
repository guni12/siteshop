<?php if ($footer2['id'] != null): ?>

    <p><?= filter_data($footer2['data'], $footer2['filter']) ?></p>

<?php else: ?>
    <p>No byline exists.</p>
<?php endif; ?>