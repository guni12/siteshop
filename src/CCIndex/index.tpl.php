<?php if (file_exists('application/data/installed.txt')): ?>
    <div id='headcolor'>
        <h2><?= t('Welcome to Siteshop index controller.') ?></h2>
    </div>
    <p><?= t('This is the index page with all the controllers') ?></p>
    <h2><?= t('Download') ?></h2>
    <p><?= t('You can download Siteshop from github.') ?></p>
    <blockquote>
        <code>git clone git://github.com/guni12/siteshop.git</code>
    </blockquote>
    <p><?= t('You can review its source directly on github: ') ?><br /><a href='https://github.com/guni12/siteshop'>https://github.com/guni12/siteshop</a></p>

<p><?= t('Now you are ready to check this MVC out. Good Luck!')?></p>

<? else: ?>

    <div id='headcolor'>
        <h2><?= t('Welcome to Siteshop framework.') ?></h2>
    </div>
<? endif; ?>
