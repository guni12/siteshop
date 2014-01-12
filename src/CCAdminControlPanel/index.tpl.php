<div id ='acp'>
<h1><?= $header1 ?></h1>
<p> <?= $text ?></p>

<h2><?= $header2 ?></h2>
<?php if ($blogs != null): ?>
<ul>
    <?php foreach ($blogs as $val): ?>
    <li><?= $val['id'] ?>. <b><?= esc($val['title']) ?></b> by <?= $val['owner'] ?> <a href='<?= create_url("acp/createcontent/{$val['id']}") ?>'>edit</a> <a href='<?= create_url("my/blog/{$val['id']}") ?>'>view</a></li>
        <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>

<h2><?= $header3 ?></h2>
<?php if ($home != null): ?>
<ul class ='noBullet'>
    <?php foreach ($home as $val): ?>
    <li><?= $val['id'] ?>. <b><?= esc($val['title']) ?></b> by <?= $val['owner'] ?> <a href='<?= create_url("acp/createcontent/{$val['id']}") ?>'>edit</a> <a href='<?= create_url("my/oneblog/{$val['id']}") ?>'>view</a></li>
        <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No homepage exists.</p>
<?php endif; ?>
<?php if ($byline != null): ?>
<ul class ='noBullet'>
    <?php foreach ($byline as $val): ?>
    <li><?= $val['id'] ?>. <b><?= esc($val['title']) ?></b> by <?= $val['owner'] ?> <a href='<?= create_url("acp/createcontent/{$val['id']}") ?>'>edit</a> <a href='<?= create_url("my/oneblog/{$val['id']}") ?>'>view</a></li>
        <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No byline exists.</p>
<?php endif; ?>
<br />
<h2><?= $header4 ?></h2>
<ul>
    <li><a href='<?= create_url('acp/createcontent') ?>'><?= $text2 ?></a></li>
</ul>

<h2><?= $header5 ?></h2>
<?php if ($users != null && $joins != null): ?>

<table>
    <tr><th>Id</th><th><?= $acronym ?></th><th><?= $name ?></th><th>Email</th><th><?= $algoritm ?></th><th><?= $created ?></th><th><?= $updated ?></th><th><?= $memberedit ?></th><th class="groups"><?= $groups2 ?></th><th><?= $joinedit ?></th></tr>
    <?php foreach ($users as $val): if($val['id'] > 1){?>       
    <tr><td><?= $val['id'] ?></td><td><?= esc($val['acronym']) ?></td><td><?= $val['name'] ?></td><td><?= $val['email'] ?></td><td><?= $val['algorithm'] ?></td><td><?= $val['created'] ?></td><td><?= $val['updated'] ?></td>
	<td><a href='<?= create_url("acp/edit/{$val['id']}") ?>'><?= $edit ?></a></td><td><?php foreach ($joins[$val['id']-1] as $group):?><?= $group['name'] ?><hr /><?php endforeach; ?></td><td><a href='<?= create_url("acp/joinedit/{$val['id']}") ?>'><?= $groupedit ?></a></td></tr>

    <?php } endforeach; ?>
</table>

<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>


<h2><?= $header6 ?></h2>
    <?php if ($groups != null): ?>
<table><tr><th>Id</th><th><?= $acronym ?></th><th><?= $name ?></th><th><?= $created ?></th><th><?= $updated ?></th><th><?= $edit ?></th></tr>
    <?php foreach ($groups as $val): ?>
    <tr><td><?= $val['id'] ?></td><td><?= $val['acronym'] ?></td><td><?= $val['name'] ?></td><td><?= $val['created'] ?></td><td><?= $val['updated'] ?></td><td> <a href='<?= create_url("acp/groupedit/{$val['id']}") ?>'><?= $edit ?></a></td></tr>
        <?php endforeach; ?>
</table>
<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>
</div>

