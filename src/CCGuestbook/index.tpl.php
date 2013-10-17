<h1>Guestbook Example</h1>
<p>Showing off how to implement a guestbook in Siteshop. Now saving to database.</p>

<form action="<?=$form_action?>" method='post'>
  <p>
    <label>Message: <br/>
    <textarea name='newEntry' placeholder="Skriv ett inlägg…" rows='3'></textarea></label>
  </p>
  <p>
    <input type='submit' name='doAdd' value='Add message' />
    <input type='submit' name='doClear' value='Clear all messages' />
    <input type='submit' name='doCreate' value='Create database table' />
  </p>
</form>

<h2>Current messages</h2>

<?php foreach($entries as $val):?>
<div class='well'>
  <p>At: <?=$val['created']?></p>
  <p><?=htmlent($val['entry'])?></p>
</div>
<?php endforeach;?>
