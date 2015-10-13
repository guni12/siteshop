<h1><?= $title?></h1>
<?php if ($content['id']): ?>
	<?php if ($theTitle != null): ?>
        <h2><?= $theTitle ?></h2>
    	   <?php else: ?>
        <h2><?= $content['title'] ?></h2>
    <?php endif; ?>	

    <p><span class='smaller-text-up'><em> Posted on <?= $content['created'] ?> by <?= $content['owner'] ?></em></span><p>
    
    <?php if ($thePost != null): ?>
        <h6><?= $thePost ?></h6>
    	   <?php else: ?>
        <h6><?= $content['data'] ?></h6>
    <?php endif; ?>
<?php else: ?>
    <p>404: No such page exists.</p>
<?php endif; ?>
