<?php if ($byline3['id']): ?>

        <p><?= filter_data($byline3['data'], $byline3['filter']) ?></p>

<?php else: ?>
    <p>No byline exists.</p>
<?php endif; ?>

