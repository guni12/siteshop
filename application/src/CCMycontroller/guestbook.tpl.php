<h1>My Guestbook</h1>
<p>Leave a message for me.</p>

<?= $form->GetHTML(array('class' => 'guestbook-edit')) ?>

<h2>Latest messages</h2>

<?php foreach ($guestbook as $val): ?>

    <div class ="guestbook">
        <p>At: <?= $val['created'] ?></p>
        <p><?= htmlent($val['entry']) ?></p>
        
        <?php if (isset($val['updated'])): ?>
            <p>Updated at <?= $val['updated'] ?> <br />
            <!--It was <?= time_diff($val['updated']) ?> ago.</p>-->
        <?php endif; ?>
        <a href='<?= create_url("my/edit/{$val['id']}") ?>'>Update or delete content</a>  
    </div>
<?php endforeach; ?>