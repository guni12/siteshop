<?php if ($img != null): ?>
<!--<p><?= $img['source'] ?></p>-->
    <img id= <?= $img['title'] ?> src= <?= $img['source'] ?>  alt= <?= $img['alt'] ?> width= <?= $img['width'] ?> height= <?= $img['height'] ?> />
<?php else: ?>
    <p>No images exists.</p>
<?php endif; ?>
    