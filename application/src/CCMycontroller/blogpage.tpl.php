<?php if ($content['id']): ?>
    <h1><?= esc($content['title']) ?></h1>
    <p><?= $content->GetFilteredData() ?></p>
    <p class='smaller-text silent'> <a href='<?= create_url("my/blog") ?>'>view all</a></p>
<?php else: ?>
    <p>404: No such page exists.</p>
<?php endif; ?>

