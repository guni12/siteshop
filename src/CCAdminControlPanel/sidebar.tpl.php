<div id='sidecolor'>
<h3><?= $header1 ?></h3>

<?php
if ($guestbook != null):
?>

<form action="acp/deleteguestbook" method="post"><input type="submit" value=<?= $text1 ?> /></form>
<br />
<h3><?= $header5 ?></h3>

<?php if ($secret1 != null): ?>
<ul class ='noBullet noStyle'>
    <?php foreach ($secret1 as $val): ?>
    <li><?= $val['id'] ?>. <b><?= esc($val['title']) ?></b> <?= t('by') . ' ' . $val['owner'] ?> <a href='<?= create_url("acp/createcontent/{$val['id']}") ?>'>edit</a> <a href='<?= create_url("my/oneblog/{$val['id']}") ?>'>view</a></li>
        <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No secretpage exists.</p>
<?php endif; ?>
<?php if ($secret2 != null): ?>
<ul class ='noBullet noStyle'>
    <?php foreach ($secret2 as $val): ?>
    <li><?= $val['id'] ?>. <b><?= esc($val['title']) ?></b> <?= t('by') . ' ' . $val['owner'] ?> <a href='<?= create_url("acp/createcontent/{$val['id']}") ?>'>edit</a> <a href='<?= create_url("my/oneblog/{$val['id']}") ?>'>view</a></li>
        <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No secretpage exists.</p>
<?php endif; ?>
<?php if ($footers != null): ?>
<ul class ='noBullet noStyle'>
    <?php foreach ($footers as $val): ?>
    <li><?= $val['id'] ?>. <b><?= esc($val['title']) ?></b> <?= t('by') . ' ' . $val['owner'] ?> <a href='<?= create_url("acp/createcontent/{$val['id']}") ?>'>edit</a> <a href='<?= create_url("my/oneblog/{$val['id']}") ?>'>view</a></li>
        <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No byline exists.</p>
<?php endif; ?>
<?php endif; ?>
<br />
<h3><?= $header2 ?></h3>
<ul>
    <li><a href='<?= create_url('acp/createuser') ?>' title='Create a new user account'><?= $text2 ?></a></li>
</ul>

<h3><?= $header3 ?></h3>
<ul>
    <li><a href='<?= create_url('acp/creategroup') ?>' title='Create a new group account'><?= $text3 ?></a></li>
</ul>

<h3><?= $header4 ?></h3>
<ul>
    <li><a href='<?= create_url('modules/install') ?>' title='Here you can start fresh again'><?= $text4 ?></a>
</ul>
</div>