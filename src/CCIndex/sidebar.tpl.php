<?php if (file_exists('application/data/installed.txt')): ?>
<div id='sidecolor'>

    <h4><?= t('Controllers and methods') ?></h4>

    <p><?= t('The following controllers exists. You enable and disable controllers in ')?> 
        <code>application/config.php</code>.</p>

    <ul class ='noBullet'>
        <?php foreach ($controllers as $key => $val): ?>
            <li><a href='<?= create_url($key) ?>'><?= $key ?></a></li>

            <?php if (!empty($val)): ?>
                <ul class ="noBullet">
                    <?php foreach ($val as $method): ?>
                        <li><a href='<?= create_url($key, $method) ?>'><?= $method ?></a></li> 
                    <?php endforeach; ?>		
                </ul>
            <?php endif; ?>

        <?php endforeach; ?>		
    </ul>
</div>
<? else: ?>
<h2><?= t('To get the framwork nice and going...') ?></h2>
<p><?= t('you need to do some installations.') ?> 
<br /><?= t('Go to the ')?><a href="<?=create_url('startup');?>"><?= t('startup-page')?></a><?= t(' to continue.') ?></p>
<? endif; ?>
