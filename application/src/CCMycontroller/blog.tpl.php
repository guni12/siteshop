<h1><?= $title?></h1>
<?php if ($content['id']): ?>
	<?php if ($theTitle != null): ?>
        <h2><?= $theTitle ?></h2>
    	   <?php else: ?>
        <h2><?= $content['title'] ?></h2>
    <?php endif; ?>	

    <p><span class='smaller-text-up'><em> Posted on <?= $content['created'] ?> by <?= $content['owner'] ?></em></span><p>
    
    <?php if ($thePost != null): ?>
        <div class = like_h6><?= $thePost ?></div>
    	   <?php else: ?>
        <div class = like_h6><?= $content['data'] ?></div>
    <?php endif; ?>
<?php else: ?>
    <p>404: No such page exists.</p>
<?php endif; ?>
