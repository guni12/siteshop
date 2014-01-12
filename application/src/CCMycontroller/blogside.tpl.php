<div id='sidecolor'>
    <?php if ($contents != null): ?>
        <?php if ($currentMonth != null): ?>
            <span class='smaller-text bold'><?= $thisMonth?></span>
            <ul class ="bloglink">
            <?php foreach ($currentMonth as $val): ?>  
                <li><a href='<?= create_url("my/blog/{$val['id']}") ?>'><?= esc($val['title']) ?></a></li>
           <?php endforeach; ?>
            </ul>
        <?php endif; ?>
        <?php if ($lastMonth != null): ?>
            <span class='smaller-text bold'><?= $previousMonth?></span>
            <ul class ="bloglink">
            <?php foreach ($lastMonth as $val): ?>
                <li><a href='<?= create_url("my/blog/{$val['id']}") ?>'><?= esc($val['title']) ?></a></li>
            <?php endforeach; ?>
            </ul>
        <?php endif; ?>
             <?php if ($twoMonthsAgo != null): ?>
            <span class='smaller-text bold'><?= $thirdMonth?></span>
            <ul class ="bloglink">
            <?php foreach ($twoMonthsAgo as $val): ?>  
                <li><a href='<?= create_url("my/blog/{$val['id']}") ?>'><?= esc($val['title']) ?></a></li>
           <?php endforeach; ?>
            </ul>
        <?php endif; ?>
             <?php if ($restOfMonths != null): ?>
            <span class='smaller-text bold'><?= $older ?></span>
            <ul class ="bloglink">
            <?php foreach ($restOfMonths as $val): ?>  
                <li><a href='<?= create_url("my/blog/{$val['id']}") ?>'><?= esc($val['title']) ?></a></li>
           <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    <?php else: ?>
        <p>No posts exists.</p>
    <?php endif; ?>

</div>