<?php if ($footer4['id'] != null): ?>

    <p><?= filter_data($footer4['data'], $footer4['filter']) ?></p>

<?php else: ?>
    <p>No byline exists.</p>
<?php endif; ?>