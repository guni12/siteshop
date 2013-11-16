<h1>Here you can create and edit</h1>
<p> a user or group or edit content 
    in pages and blog. You can also delete the whole guestbook if needed.</p>

<h2>The blogs</h2>
<?php if ($blogs != null): ?>
<ul>
    <?php foreach ($blogs as $val): ?>
    <li><?= $val['id'] ?>. <b><?= esc($val['title']) ?></b> by <?= $val['owner'] ?> <a href='<?= create_url("acp/createcontent/{$val['id']}") ?>'>edit</a> <a href='<?= create_url("my/oneblog/{$val['id']}") ?>'>view</a>
        <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>

<h2>The pages</h2>
<?php if ($home != null): ?>
<ul>
    <?php foreach ($home as $val): ?>
    <li><?= $val['id'] ?>. <b><?= esc($val['title']) ?></b> by <?= $val['owner'] ?> <a href='<?= create_url("acp/createcontent/{$val['id']}") ?>'>edit</a> <a href='<?= create_url("my/oneblog/{$val['id']}") ?>'>view</a>
        <?php endforeach; ?>
</ul>
<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>

<h2>Actions</h2>
<ul>
    <li><a href='<?= create_url('acp/createcontent') ?>'>Create new content</a>
</ul>

<h2>The guestbook can be deleted here:</h2>

<?php
//var_dump($guestbook);
if ($guestbook != null):
?>

<form action="acp/deleteguestbook" method="post"><input type="submit" value="Delete the guestbook" /></form>

<?php endif; ?>
<br />
<h2>The users</h2>
<?php if ($users != null && $joins != null): ?>

<table>
    <tr><th>Id</th><th>Acronym</th><th>Name</th><th>Email</th><th>Algorithm</th><th>Created</th><th>Updated</th><th>Edit member</th><th class="groups">Groups</th><th>Edit joins</th></tr>
    <?php foreach ($users as $val): if($val['id'] > 1){?>       
    <tr><td><?= $val['id'] ?></td><td><?= esc($val['acronym']) ?></td><td><?= $val['name'] ?></td><td><?= $val['email'] ?></td><td><?= $val['algorithm'] ?></td><td><?= $val['created'] ?></td><td><?= $val['updated'] ?></td>
	<td><a href='<?= create_url("acp/edit/{$val['id']}") ?>'>Edit</a></td><td><?php foreach ($joins[$val['id']-1] as $group):?><?= $group['name'] ?><hr /><?php endforeach; ?></td><td><a href='<?= create_url("acp/joinedit/{$val['id']}") ?>'>GroupEdit</a></td></tr>

    <?php } endforeach; ?>
</table>

<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>


<h2>The groups</h2>
    <?php if ($groups != null): ?>
<table><tr><th>Id</th><th>Akronym</th><th>Namn</th><th>Created</th><th>Updated</th><th>Edit</th></tr>
    <?php foreach ($groups as $val): ?>
    <tr><td><?= $val['id'] ?></td><td><?= $val['acronym'] ?></td><td><?= $val['name'] ?></td><td><?= $val['created'] ?></td><td><?= $val['updated'] ?></td><td> <a href='<?= create_url("acp/groupedit/{$val['id']}") ?>'>Edit</a></td></tr>
        <?php endforeach; ?>
</table>
<?php else: ?>
<p>No content exists.</p>
<?php endif; ?>

<h2>Create a new member</h2>
<ul>
    <li><a href='<?= create_url('acp/createuser') ?>' title='Create a new user account'>Create user</a></li>
</ul>

<h2>Create a new group</h2>
<ul>
    <li><a href='<?= create_url('acp/creategroup') ?>' title='Create a new group account'>Create group</a></li>
</ul>

<h2>Initiate the database here:</h2>
<ul>
    <li><a href='<?= create_url('modules/install') ?>' title='Here you can start fresh again'>Init database, create tables and create default admin user</a>
</ul>