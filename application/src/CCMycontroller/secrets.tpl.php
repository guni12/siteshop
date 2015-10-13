<?php
if ($theSecret != null): ?>
        <h1><?= $theTitle ?></h1>
        <p><?= $theSecret ?></p>

<?php else: ?>
    <p>No posts exists.</p>
<?php endif; ?>