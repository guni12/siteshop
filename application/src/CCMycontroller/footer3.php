<?php if ($footer3['id'] != null): ?>

    <p><?= filter_data($footer3['data'], $footer3['filter']) ?></p>

<?php else: ?>
    <p>No byline exists.</p>
<?php endif; ?>