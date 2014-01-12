<?php if ($img != null): ?>

    <img id= <?= $img['title'] ?> src= <?= base_url() ?><?= $img['source'] ?>  alt= <?= $img['alt'] ?> width= <?= $img['width'] ?> height= <?= $img['height'] ?> />
<?php else: ?>
    <p>No images exists.</p>
<?php endif; ?>
    