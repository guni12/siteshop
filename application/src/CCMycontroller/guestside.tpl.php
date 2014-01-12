<h2><?= t('Latest messages') ?></h2>

<?php foreach ($guestbook as $val): ?>

    <div class ="guestbook">
        <p><?= t('At: ') ?><?= $val['created'] ?></p>
        <p><?= htmlent($val['entry']) ?></p>
        <!--<p><?= mb_detect_encoding($val['entry'])?></p>-->
        <?php if (isset($val['updated'])): ?>
            <p><?= t('Updated at ') ?><?= $val['updated'] ?> <br />
            <!--It was <?= time_diff($val['updated']) ?> ago.</p>-->
        <?php endif; ?>
        <a href='<?= create_url("my/edit/{$val['id']}") ?>'><?= t('Update or delete content') ?></a>  
    </div>
<?php endforeach; ?>