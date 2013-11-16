<?php include($header); ?>

<h2><?=t('User details')?></h2>
<?=$form?>

    <p>You were created at <?= $user['created']; 
        if($user['updated']){ 
            echo ' and updated at ' . $user['updated'];
        } ?>.</p>

    <p>You are member of <?= count($user['groups']) ?> group(s).</p>
    <ul>
        <?php foreach ($user['groups'] as $group): ?>
            <li><?= $group['name'] ?>
            <?php endforeach; ?>
    </ul>


  