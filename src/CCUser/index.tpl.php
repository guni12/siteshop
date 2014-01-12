<?php include($header); ?>
<h1><?= t('User Controller Index') ?></h1>
<p><?= t('Hi ') ?> <?= $user['name'] ?>.</p>
<p><?= t('Check the menu in the upper right corner to logout or to admin if you are authorised.') ?></p>

<?php if ($link == true): ?>
    <p><?= t('You can see the secret page here:') ?>
        <br />
        <a href='<?= create_url("my/secrets") ?>'><?= t('The secret page') ?></a></p>
<?php else: ?>
    <p></p>
<?php endif; ?>

