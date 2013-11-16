<h1>Blog</h1>
<p>All nice news and blogposts here.</p>

<?php if($contents != null):?>
  <?php foreach($contents as $val):?>
    <h2><?=esc($val['title'])?></h2>
<p class='smaller-text'><em>Posted on <?=$val['created']?> by <?=$val['owner']?></em></p>
<p><?=filter_data($val['data'], $val['filter'])?></p>
<p class='smaller-text silent'> <a href='<?= create_url("my/oneblog/{$val['id']}") ?>'>view</a></p>

<?php endforeach; ?>
<?php else:?>
  <p>No posts exists.</p>
<?php endif;?>


