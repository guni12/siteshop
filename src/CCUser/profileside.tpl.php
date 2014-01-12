<p><?= t('You were created at') ?> 
    <?= $user['created'];
    if ($user['updated']) {
        echo ' and updated at ' . $user['updated'];
    }
    ?>.</p>
<p><?= t('You are member of !number group(s).', array('!number' => count($user['groups']))) ?></p>
<ul>
        <?php foreach ($user['groups'] as $group): ?>
        <li><?= $group['name'] ?>
<?php endforeach; ?>
</ul>