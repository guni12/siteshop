<?php if ($byline4['id']): ?>

        <p><?= filter_data($byline4['data'], $byline4['filter']) ?></p>

<?php else: ?>
    <p>No byline exists.</p>
<?php endif; ?>


