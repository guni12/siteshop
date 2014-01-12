<h1><?= $title?></h1>
<?php if ($content['id']): ?>
    <h2><?= esc($content['title']) ?><span class='smaller-text-up'><em> Posted on <?= $content['created'] ?> by <?= $content['owner'] ?></em></span></h2>
    <p><?= $content->GetFilteredData() ?></p>

<?php else: ?>
    <p>404: No such page exists.</p>
<?php endif; ?>
