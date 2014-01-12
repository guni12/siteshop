<?php if ($byline2['id']): ?>

        <p><?= filter_data($byline2['data'], $byline2['filter']) ?></p>

<?php else: ?>
    <p>No byline exists.</p>
<?php endif; ?>

