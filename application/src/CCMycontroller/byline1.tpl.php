<?php if ($byline1['id'] != null): ?>

    <p><?= filter_data($byline1['data'], $byline1['filter']) ?></p>

<?php else: ?>
    <p>No byline exists.</p>
<?php endif; ?>
