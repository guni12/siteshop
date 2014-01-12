<h1>Guestbook Example</h1>
<p>Showing off how to implement a guestbook in Siteshop. Now saving to database.</p>

<form action="<?= $form_action ?>" method='post'>
    <p>
        <label>Message: <br/>
            <textarea rows='3' name='newEntry' placeholder="Skriv ett inlägg…" class='span4'></textarea></label>
    </p>
    <p>
        <button type='submit' name='doAdd' class='btn btn-primary'><i class='icon-pencil icon-white'></i>Add message</button>
        <button type='submit' name='doClear' class='btn btn-danger'><i class='icon-trash icon-white'></i>Clear all messages</button>
        <button type='submit' name='doCreate' class='btn btn-warning'><i class='icon-wrench icon-white'></i>Create database table</button>
    </p>
</form>

<h2>Current messages</h2>

<?php foreach ($entries as $val): ?>
    <div class='well'>
        <p>At: <?= $val['created'] ?></p>
        <p><?= htmlent($val['entry']) ?></p>
    </div>
<?php endforeach; ?>
