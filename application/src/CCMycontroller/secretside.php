<div id='sidecolor'>
    <?php
    if ($theSecretSide != null): ?>

        <h2><?= $theTitleSide ?></h2>
            <p><?= $theSecretSide ?></p>

    <?php else: ?>
        <p>No posts exists.</p>
    <?php endif; ?>
</div>